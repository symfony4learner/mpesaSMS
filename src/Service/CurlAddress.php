<?php 

namespace App\Service;

class CurlAddress
{
	public function curl($address)
	{
	    try {
	        $ch = curl_init();

	        // Check if initialization had gone wrong*    
	        if ($ch === false) {
	            throw new Exception('failed to initialize');
	        }

	        curl_setopt($ch, CURLOPT_URL, $address);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        // curl_setopt(/* ... */);

	        $content = curl_exec($ch);

	        // Check the return value of curl_exec(), too
	        if ($content === false) {
	            throw new Exception(curl_error($ch), curl_errno($ch));
	        }

	        /* Process $content here */

	        // Close curl handle
	        curl_close($ch);
	    } catch(Exception $e) {

	        trigger_error(sprintf(
	            'Curl failed with error #%d: %s',
	            $e->getCode(), $e->getMessage()),
	            E_USER_ERROR);

	    }
		return $content;
	}


}