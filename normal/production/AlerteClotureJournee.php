<?php

	include("../modele/connexion.php");
	require __DIR__ . '/vendor/autoload.php';
	use Twilio\Rest\Client;
	//
	$account_sid = getenv('TWILIO_ACCOUNT_SID');
	$auth_token = getenv('TWILIO_AUTH_TOKEN');
	$twilio_number = getenv('TWILIO_NUMBER');
	$client = new Client($account_sid, $auth_token);
	
	$codematiere="Maths";
	$nbreheure="4h";
	$notification = 'CsBordjoNotif_Absence:'.chr(13).$codematiere.':'.$nbreheure; 

	$client->messages->create(
	"+22893227045",
	array(
			"body" => $notification,
			"from" => $twilio_number
		)
	);