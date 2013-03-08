<?php
$logon_not_required = TRUE;
require_once '../lib/bootstrap.php';
require_once('recaptchalib.php');

Template::check_ajax_key();

global $aes_key;
global $privatekey;
global $recaptcha_url;

if (isset($_GET['challengeData']) && isset($_GET['responseData']) &&
    isset($_GET['email'])){

    $postArray = array(
        'privatekey' => $privatekey,
        'remoteip' => $_SERVER['REMOTE_ADDR'],
        'challenge' => $_GET['challengeData'],
        'response' => $_GET['responseData'],
    );

    $postData = http_build_query($postArray);
    $response = do_post_request($recaptcha_url, $postData);

    $responseReply = explode("\n",$response);
   

    if(strtolower($responseReply[0]) == "true"){
       
        // check if email exists in database
        $db = new Database();
        $row = $db->get_row_by_key('affiliates', 'email', $_GET['email']);

        if(isset($row)){
            // generate confirmation code and add to db
            $confirm_code = substr(md5(makePin()),0,16);
            //$current_date = date("YmdHis");
            $current_date = time();
            $result = $db->update_by_key('affiliates','email',$_GET['email'],
                array('confirm_code' => $confirm_code,
                      'reset_stamp' => $current_date,
                      'password_reset' => 1));

            // send generated confirmation code with link to user email
            $to = $_GET['email'];
            $subject = "Your Password Reset Information";
            $headers = 'From: resetpass@zenobooks.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            $message = 'This reset code will expire in 48 hours.' .
                       'Your password confirmation code: ' . $confirm_code;
            if (mail($to,$subject,$message,$headers, '-f resetpass@zenobooks.com')){
                echo json_encode(1);
            } else {
                echo json_encode(5);
            }

          
        } else {
            echo json_encode(2);
        }
        unset($db);

    } else {
        echo json_encode(3);
    }

} else {
    echo json_encode(4);
}


