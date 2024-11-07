<?php

namespace Isolated;

require __DIR__ . '/../src/Twilio/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
use Isolated\Twilio\Rest\Client;
$sid = \getenv('TWILIO_ACCOUNT_SID');
$token = \getenv('TWILIO_AUTH_TOKEN');
$client = new Client($sid, $token);
// Create new Signing Key
$signingKey = $client->api->v2010->newSigningKeys->create();
// Switch to guzzle client as the default client
$guzzleClient = new Client($signingKey->sid, $signingKey->secret, $sid, null, new \Isolated\Twilio\Http\GuzzleClient(new \Isolated\GuzzleHttp\Client()));
// The phone number you are querying in E.164 or national format.
// If the phone number is provided in national format, please also specify the country in the optional parameter CountryCode.
// Otherwise, CountryCode will default to US.
$number = "+XXXXXXXXXX";
// Make REST API requests
$phone_number = $guzzleClient->lookups->v1->phoneNumbers($number)->fetch(["type" => ["carrier"]]);
\print_r($phone_number->carrier);
