<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\SMSIn;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\SendMessage;

class SMSInController extends Controller
{
    private $service_fee = '50'; // what you are charging for service
    public $expected_sms_origin = '0736600033'; // you need the message to come from MPESA, not some con-men's number.
    private $example_message = 'BS49OR20Z Confirmed.You have received Ksh55.00 from MICHAEL SOMEONE 254705285959 on 15/10/11 at 11:52 AM New M-PESA balance is Ksh100.00';

    /**
     * @Route("/sms/receive", name="receive_sms")
     */
    public function receiveAction(Request $request, SendMessage $sendMessage)
    {
    	//access the get parameters, %p for sms_origin and %a for message
    	$message = $request->query->get('message');
    	$sms_origin = $request->query->get('sms_origin');

        // check whether message is from mpesa, if not, reply from here
        if($sms_origin != $this->expected_sms_origin){
            $reply = $this->notMpesaResponse();
            $full_phone_number = "+".$sms_origin;
            $formatted_number  = str_replace("+254", "0", $full_phone_number);
            $concatenated_reply = str_replace(" ", "+", $reply);
        } else {
            // date time for when this message was received
            $now = new \DateTime("now");
            // split the message into parts and access the different variables
            $parts = $this->getMessageParts($message); //replace $message with $example for testing

            // set values to a new SMSIn entity
            $smsIn = new SMSIn();
            $smsIn->setConfirmationCode($parts['confirmation_code']);
            $smsIn->setClientName($parts['full_name']);
            $smsIn->setPhoneNumber($parts['sender']);
            $smsIn->setAmountReceived($parts['amount_received']);
            $smsIn->setReceivedOn($now);
            $smsIn->setSmsOrigin($sms_origin);
            $smsIn->setWholeSms("xyz");

            // get the number with 254 prefix
            $full_phone_number = "+".$parts['sender'];

            // replace 254 with 0
            $formatted_number  = str_replace("+254", "0", $full_phone_number);

            // calculate the balance based on amount receved and service fee
            $balance = $parts['amount_received'] - $this->service_fee;

            // formulate reply based on amount paid
            list($tokens, $reply, $balance_left, $status) = $this->makeReply($parts['full_name'], $balance, $parts['amount_received'], $this->service_fee, $parts['sender']);
            
            // replace spaces in reply with + signs
            $concatenated_reply = str_replace(" ", "+", $reply);

            // save to table
            $smsIn->setStatus($status);
            $smsIn->setBalance($balance_left);
            $this->save($smsIn);
        }

        $send = $sendMessage->sendMessage($formatted_number, $concatenated_reply);
        $this->addFlash('success', "Message sent");

        return $this->render('sms_in/index.html.twig', ['message' => $message]);
	}
    /**
     * @Route("/sms/messages", name="list_messages")
     */
    public function listAction(Request $request, SendMessage $sendMessage)
    {
        // display all entries from the mpesa table.
        $data = [];
        $messages = $this->em()->getRepository('App:SMSIn')
            ->findAll();
        $data['messages'] = $messages;
        return $this->render('sms_in/messages.html.twig', $data);
    }

    /**
     * @Route("/sms/clear", name="clear_messages")
     */
    public function clearAction(Request $request, SendMessage $sendMessage)
    {
        // this is destructive! it will clear the database and the log file.
        $messages = $this->em()->getRepository('App:SMSIn')
            ->findAll();
        foreach($messages as $message){
            $this->em()->remove($message);
            $this->em()->flush();
        }
        $date = date('Y-m-d');
        $path_to_log = $this->container->get('kernel')->getLogDir();
        $mpesa_log = "sms-".$date.".log";

        file_put_contents($path_to_log."/".$mpesa_log, "");
        return $this->render('sms_in/delete.html.twig');
    }

	
    private function save($entity){
        $this->em()->persist($entity);
        $this->em()->flush();        
    } 

    private function notMpesaResponse(){
        // response if message is not from mpesa
        $reply = "We expect messages from ".$this->expected_sms_origin.". Call 0705285959/0736600033 for assistance.";
        return $reply;
    }

    private function makeReply($full_name, $balance, $amount_received, $service_fee, $sender){
        // response depending on amount sent
        $reply = "";
        $tokens = 0;
        $status = "";

        $last_entry = $this->em()->getRepository('App:SMSIn')
            ->findOneBy(
                array('phone_number' => $sender),
                array('id' => 'DESC'),
                1
            );
        $bal_cf = $last_entry ? $last_entry->getBalance() : 0;
        $balance += $bal_cf;

        if($balance < 0){
            // cash is not enough
            $reply = "We have received ".$amount_received." Kenya shillings from ".$full_name." Send Ksh ".abs($balance)." to complete your payment then we'll send you the code";
            $status = "to_add";
            $balance_left = $amount_received + $bal_cf;
        } elseif ($balance >= 0) {
            $status = "enough";
            // cash is enough, initial token 
            $tokens = 1;
            // if amount is still greater than service fee, add tokens
            while($balance >= $this->service_fee){
                $balance -= $this->service_fee;
                $tokens++;
            }
            // get amount left after adding all qualified tokens
            $balance_left = $balance % $this->service_fee;

            //get the activation codes from database and make them as a list.
            $random_codes = $this->em()->getRepository('App:Code')
                ->getRandomCodes($tokens);

            $codes = [];

            foreach($random_codes as $code){ 
                $codes[] = $code->getRandomCode(); 
                $code->setStatus('used');
                $this->save($code);
            }

            $string_of_codes = implode(", ", $codes);

            $data['rand_str'] = $string_of_codes;
            
            // formulate reply with tokens and balance
            $reply = "We have received ".$amount_received." Kenya shillings from ".$full_name." You have purchased ".$tokens." token(s):" . $string_of_codes . ". We owe you Ksh ". $balance_left;    
        }
        return [$tokens, $reply, $balance_left, $status];
    }
    
    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function getMessageParts($message){
        // split message by spaces
        $splitted_message = explode(" ", $message);
        $parts = [];
        $amount_received_str = $splitted_message[4];
        $sender = is_numeric($splitted_message[8]) ? $splitted_message[8] : $splitted_message[9];
        $f_name = $splitted_message[6];
        $l_name = $splitted_message[7];

        // add to parts array
        $parts['confirmation_code'] = $splitted_message[0];
        $parts['amount_received'] = str_replace(["Ksh", ","], "", $amount_received_str);
        $parts['sender'] = $sender;
        $parts['full_name'] = $f_name . " " . $l_name;

        return $parts;
    }
}