<?php

// specify the REST web service to interact with
$url = 'http://localhost/~jmertic/sugarplatformdemo/service/v4/rest.php';

// Open a curl session for making the call
$curl = curl_init($url);

// Tell curl to use HTTP POST
curl_setopt($curl, CURLOPT_POST, true);

// Tell curl not to return headers, but do return the response
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Set the POST arguments to pass to the Sugar server
$parameters = array(
    'user_auth' => array(
        'user_name' => 'admin',
        'password' => md5('sugar'),
        ),
    );
$json = json_encode($parameters);
$postArgs = array(
                'method' => 'login',
                'input_type' => 'JSON',
                'response_type' => 'JSON',
                'rest_data' => $json
                );
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

// Make the REST call, returning the result
$response = curl_exec($curl);

// Make the REST call, returning the result
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.\n");
}

// Convert the result from JSON format to a PHP array
$result = json_decode($response);
if ( !is_object($result) ) {
    var_dump($response);
    die("Error handling result.\n");
}
if ( !isset($result->id) ) {
    die("Error: {$result->name} - {$result->description}\n.");
}

// Get the session id
$sessionId = $result->id;

// Let's add a new Feedback record
$parameters = array(
    'session' => $sessionId,
    'module' => 'pos_Feedback',
    'name_value_list' => array(
        array('name' => 'rating', 'value' => $_REQUEST['rating']),
        array('name' => 'description', 'value' => $_REQUEST['feedback']),
        ),
    );
$json = json_encode($parameters);
$postArgs = array(
                'method' => 'set_entry',
                'input_type' => 'JSON',
                'response_type' => 'JSON',
                'rest_data' => $json
                );
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

// Make the REST call, returning the result
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.\n");
}

// Convert the result from JSON format to a PHP array
$result = json_decode($response);
if ( !is_object($result) ) {
    var_dump($response);
    die("Error handling result.\n");
}
if ( !isset($result->id) ) {
    die("Error: {$result->name} - {$result->description}\n.");
}

// Get the newly created record id
$feedbackId = $result->id;

// Now relate the speaker to the session
$parameters = array(
    'session' => $sessionId,
    'module_name' => 'pos_Feedback',
    'module_id' => $feedbackId,
    'link_field_name' => 'pos_sessions_pos_feedback',
    'related_ids' => array($_REQUEST['talk_id']),
    );
$json = json_encode($parameters);
$postArgs = array(
                'method' => 'set_relationship',
                'input_type' => 'JSON',
                'response_type' => 'JSON',
                'rest_data' => $json
                );
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);

// Make the REST call, returning the result
$response = curl_exec($curl);
if (!$response) {
    die("Connection Failure.\n");
}

// Convert the result from JSON format to a PHP array
$result = json_decode($response);
if ( !is_object($result) ) {
    var_dump($response);
    die("Error handling result.\n");
}

header('Location: feedbacksubmitted.html');
