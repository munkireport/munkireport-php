<?php

namespace munkireport\lib;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;

class Request
{
    private $options;
    
    public function __construct()
    {
        $this->options = [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A',
            ],
            'timeout'  => conf('request_timeout', 5)
        ];
        
        // Add proxy
        $proxy = conf('proxy');
        if (isset($proxy['server'])) {
            $proxy['server'] = str_replace('tcp://', '', $proxy['server']);
            $proxy['port'] = isset($proxy['port']) ? $proxy['port'] : 8080;
            $this->options['proxy'] = 'http://' . $proxy['server'].':'.$proxy['port'];

            // Authenticated proxy
            if (isset($proxy['username']) && isset($proxy['password'])) {
            // Encode username and password
                $auth = base64_encode($proxy['username'].':'.$proxy['password']);
                $this->options['headers']["Proxy-Authorization"] = "Basic $auth";
            }
        }
    }
    
    public function get($url, $options = [])
    {
        $client = new Client();
        try {
            $response = $client->request('GET', $url, array_merge($this->options, $options));
            return $response->getBody();
        } catch (TransferException $e) {
            if(conf('debug')){
                $this->dump_exception($e);
                exit();
            }
        }
    }
    
    private function dump_exception($e)
    {
        printf("<pre>REQUEST:\n%s</pre>", Psr7\str($e->getRequest()));
        if ($e->hasResponse()) {
            printf("<pre>RESPONSE:\n%s</pre>", htmlentities(Psr7\str($e->getResponse())));
        }
    }
}
