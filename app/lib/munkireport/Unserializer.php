<?php

namespace munkireport;

/**
 * Replacement for unserialize
 *
 * Based on the python unserializer
 * Does not convert objects
 *
 */
class Unserializer
{
    
    private $position, $str;
    
    public function __construct($s)
    {
        $this->position = 0;
        $this->str = $s;
    }

    public function await($symbol, $n = 1)
    {
        #result = $this->take(len(symbol))
        $result = substr($this->str, $this->position, $n);
        $this->position += $n;
        if ($result != $symbol) {
            throw new \Exception(sprintf('Next is `%s` not `%s`', $result, $symbol), 1);
        }
    }
    
    public function take($n = 1)
    {
        $result = substr($this->str, $this->position, $n);
        $this->position += $n;
        return $result;
    }
    
    public function take_while_not($stopsymbol, $typecast = '')
    {
        
        $stopsymbol_position = strpos($this->str, $stopsymbol, $this->position);
        if ($stopsymbol_position === false) {
            throw new \Exception(sprintf('No `%s`', $stopsymbol), 1);
        }
        $result = substr($this->str, $this->position, $stopsymbol_position - $this->position);

        $this->position = $stopsymbol_position + 1;
        if ($typecast) {
            settype($result, $typecast);
        }
        return $result;
    }

    public function get_rest()
    {
        return substr($this->str, $this->position);
    }

    public function unserialize()
    {
                
        $t = $this->take();
        

        if ($t == 'N') {
            $this->await(';');
            return null;
        }
        
        $this->await(':');
        
        switch ($t) {
            case 'i':
                return $this->take_while_not(';', 'int');
            
            case 'd':
                return $this->take_while_not(';', 'float');
            
            case 'b':
                return (bool) $this->take_while_not(';', 'int');
            
            case 's':
                $size = $this->take_while_not(':', 'int');
                $this->await('"');
                $result = $this->take($size);
                $this->await('";', 2);
                return $result;
            
            case 'a':
                $size = $this->take_while_not(':', 'int');
                return $this->parse_hash_core($size);
            
            case 'O':
                // No object conversion
                throw new \Exception("No object conversion allowed", 1);
            
            default:
                throw new \Exception(sprintf('Unknown type `%s`', $t), 1);
        }
    }

    public function parse_hash_core($size)
    {
        $result = array();
        $this->await('{');
        $is_array = true;
        for ($i=0; $i < $size; $i++) {
            $k = $this->unserialize();
            $v = $this->unserialize();
            $result[$k] = $v;
            if ($is_array && $k !== $i) {
                $is_array = false;
            }
        }
        if ($is_array) {
            $result = array_values($result);
        }
        $this->await('}');
        return $result;
    }
}
