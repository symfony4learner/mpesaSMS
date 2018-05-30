<?php 

namespace App\Service;

use Psr\Log\LoggerInterface;

class SendMessage
{
        
        private $logger;

        public function __construct(LoggerInterface $logger)
        {
        	$this->logger = $logger;
        }

        public function sendMessage($phone_no, $message)
        {
        	$clean_message = str_replace(" ", "+", $message);
        	
        try {
            $ch = curl_init();

            // Check if initialization had gone wrong*    
            if ($ch === false) {
                throw new Exception('failed to initialize');
            }

            curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:13013/cgi-bin/sendsms?username=kanneluser&password=kannelpass&to=$phone_no&text=$clean_message");
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
        $this->logger->info('sent a message to '.$phone_no.': '.$message);
        // return $message;
        return $content;

        }
}