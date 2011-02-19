<?php

function email_is_valid($email){
    return preg_match('/[^\@]+\@.+\.[^\.]{2,}/m', $email);
}

function is_ajax(){
    return strtolower( @$_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest';
}

define('MCAPI_KEY', '');
define('MCAPI_LIST_ID', '');

$response = array('ok' => false, 'message' => 'No email found.');
if(isset($_POST['email']) && email_is_valid($_POST['email'])){
    
    $email = $_REQUEST['email'];
    require_once 'wp-includes/MCAPI.class.php';
    
    $mcapi = new MCAPI(MCAPI_KEY);
    
    $answer = $mcapi->listSubscribe(MCAPI_LIST_ID, $email, array('FNAME'=>'',
                                                                 'LNAME'=>'', 
                                                                 'INTERESTS'=>''));
    
    if($mcapi->errorCode){
        $response['message'] = $mcapi->errorMessage;
    } else {
        $response['message'] = 'Thank you!';
        $response['ok'] = true;
    }
        
} else {
    $response['message'] = 'Please provide a valid email.';
}
if(is_ajax()){
    echo json_encode($response);
    exit;
}
else {
    if(! empty($_SERVER['HTTP_REFERER'])){
        header('location: ' . @$_SERVER['HTTP_REFERER'] . '?message=' . urlencode($response['message']));
	exit;
    }
    echo $response['message'];
}


