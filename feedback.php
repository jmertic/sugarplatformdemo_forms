<?php
// Grab the list of sessions for the dropdown


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

// Retieve the contact record we just created
$parameters = array(
    'session' => $sessionId, 
    'module_name' => 'pos_Sessions', 
    'query' => "pos_sessions.status = 'Accepted'", 
    'order_by' => 'name', 
    'offset' => '',
    'select_fields' => array('name'),
    'link_name_to_fields_array' => array(),
    );

$json = json_encode($parameters);
$postArgs = array(
                'method' => 'get_entry_list',
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
if ( !isset($result->result_count) ) {
    die("Error: {$result->name} - {$result->description}\n.");
}

$options = '';
foreach ( $result->entry_list as $entry ) {
    $options .= '<option value="'.$entry->id.'" >'.$entry->name_value_list->name->value.'</option>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Give Session Feedback for MyaCon</title>
<link rel="stylesheet" type="text/css" href="css/view.css" media="all">
<script type="text/javascript" src="js/view.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="images/top.png" alt="">
	<div id="form_container">
	
		<h1><a>Give Session Feedback for MyaCon</a></h1>
		<form id="form_131330" class="appnitro"  method="post" action="savefeedback.php">
					<div class="form_description">
			<h2>Give Session Feedback for MyaCon</h2>
			<p>Let us know what you thought about the sessions you saw at MyaCon this year. All feedback is anonymous.</p>
		</div>						
			<ul >
			
					<li id="li_2" >
		<label class="description" for="element_2">Session Title </label>
		<div>
		<select class="element select large" id="element_2" name="talk_id"> 
			<?php echo $options; ?>
		</select>
		</div> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Rating </label>
		<span>
			<input id="element_3_1" name="rating" class="element radio" type="radio" value="1" />
<label class="choice" for="element_3_1">1</label>
<input id="element_3_2" name="rating" class="element radio" type="radio" value="2" />
<label class="choice" for="element_3_2">2</label>
<input id="element_3_3" name="rating" class="element radio" type="radio" value="3" />
<label class="choice" for="element_3_3">3</label>
<input id="element_3_4" name="rating" class="element radio" type="radio" value="4" />
<label class="choice" for="element_3_4">4</label>
<input id="element_3_5" name="rating" class="element radio" type="radio" value="5" />
<label class="choice" for="element_3_5">5</label>

		</span> 
		</li>		<li id="li_1" >
		<label class="description" for="element_1">Feedback </label>
		<div>
			<textarea id="element_1" name="feedback" class="element textarea medium"></textarea> 
		</div> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="131330" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>
	</div>
	<img id="bottom" src="images/bottom.png" alt="">
	</body>
</html>