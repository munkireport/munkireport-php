<?php

class plistParser extends XMLReader
{
    public function parse($file) {
        trigger_error(
            'plistParser::parse() is deprecated, please use plistParser::parseFile()',
            E_USER_NOTICE
        );

        return $this->parseFile($file);
    }
    
    public function parseFile($file) {
		if(basename($file) == $file) {
			throw new Exception("Non-relative file path expected", 1);
		}
		$this->open("file://" . $file);
		return $this->process();
	}

    public function parseString($string) {
        $this->XML($string);
        return $this->process();
    }

    private function process() {
		// plist's always start with a doctype, use it as a validity check
		$this->read();
		if($this->nodeType !== XMLReader::DOC_TYPE || $this->name !== "plist") {
			throw new Exception(sprintf("Error parsing plist. nodeType: %d -- Name: %s", $this->nodeType, $this->name), 2);
		}
		
		// as one additional check, the first element node is always a plist
		if(!$this->next("plist") || $this->nodeType !== XMLReader::ELEMENT || $this->name !== "plist") {
			throw new Exception(sprintf("Error parsing plist. nodeType: %d -- Name: %s", $this->nodeType, $this->name), 3);
		}
		
		$plist = array();	
		while($this->read()) {
			if($this->nodeType == XMLReader::ELEMENT) {
				$plist[] = $this->parse_node();
			}
		}
		if(count($plist) == 1 && $plist[0]) {
			// Most plists have a dict as their outer most tag
			// So instead of returning an array with only one element
			// return the contents of the dict instead
			return $plist[0];
		} else {
			return $plist;
		}
    }

	private function parse_node() {
		// If not an element, nothing for us to do
		if($this->nodeType !== XMLReader::ELEMENT) return;
		
		switch($this->name) {
			case 'data': 
				return base64_decode($this->getNodeText());
				break;
			case 'real':
				return floatval($this->getNodeText());
				break;
			case 'string':
				return $this->getNodeText();
				break;
			case 'integer':
				return intval($this->getNodeText());
				break;
			case 'date':
				return $this->getNodeText();
				break;
			case 'true':
				return true;
				break;
			case 'false':
				return false;
				break;
			case 'array':
				return $this->parse_array();
				break;
			case 'dict':
				return $this->parse_dict();
				break;
			default:
				// per DTD, the above is the only valid types
				throw new Exception(sprintf("Not a valid plist. %s is not a valid type", $this->name), 4);
		}			
	}
	
	private function parse_dict() {
		$array = array();
		$this->nextOfType(XMLReader::ELEMENT);
		do {
			if($this->nodeType !== XMLReader::ELEMENT || $this->name !== "key") {
				// If we aren't on a key, then jump to the next key
				// per DTD, dicts have to have <key><somevalue> and nothing else
				if(!$this->next("key")) {
					// no more keys left so per DTD we are done with this dict
					return $array;
				}
			}
			$key = $this->getNodeText();
			$this->nextOfType(XMLReader::ELEMENT);
			$array[$key] = $this->parse_node();
			$this->nextOfType(XMLReader::ELEMENT, XMLReader::END_ELEMENT);
		} while($this->nodeType && !$this->isNodeOfTypeName(XMLReader::END_ELEMENT, "dict"));
		return $array;
	}
	
	private function parse_array() {
		$array = array();
		if( $this->readString() ) // Arjen cater for empty arrays
		{
	        $this->nextOfType(XMLReader::ELEMENT);
			do {
				$array[] = $this->parse_node();
				// skip over any whitespace
				$this->nextOfType(XMLReader::ELEMENT, XMLReader::END_ELEMENT);
			} while($this->nodeType && !$this->isNodeOfTypeName(XMLReader::END_ELEMENT, "array"));
		}
		return $array;
	}
	
	private function getNodeText() {
		$string = $this->readString();
		// now gobble up everything up to the closing tag
		$this->nextOfType(XMLReader::END_ELEMENT);
		return $string;
	}
	
	private function nextOfType() {
		$types = func_get_args();
		// skip to next
		$this->read();
		// check if it's one of the types requested and loop until it's one we want
		while($this->nodeType && !(in_array($this->nodeType, $types))) {
			// node isn't of type requested, so keep going
			$this->read();
		}
	}
	
	private function isNodeOfTypeName($type, $name) {
		return $this->nodeType === $type && $this->name === $name;
	}
}