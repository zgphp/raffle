<?php

require __DIR__ . '/settings.php';

// Read settings
$eventID = defined("EVENT_ID") ? EVENT_ID : null;
$apiKey = defined("API_KEY") ? API_KEY : null;
$gaAccount = defined("GA_ACCOUNT") ? GA_ACCOUNT : null;
$ignoreIDs = defined("IGNORE_MEMBERS") ? explode(',', IGNORE_MEMBERS) : array();

// Check settings
if (empty($eventID)) die("EVENT_ID not defined in settings.php.");
if (empty($apiKey)) die("API_KEY not defined in settings.php.");

echo <<<END
Preparing raffle data
=====================
Event ID: $eventID
API Key: $apiKey\n\n
END;

// Create a stream to define character encoding
$opts = array('http' => array('header' => 'Accept-Charset: utf-8'));
$context = stream_context_create($opts);

// Build URLs
$baseURL = "http://api.meetup.com";
$eventURL = $baseURL . "/2/event/$eventID?key=$apiKey";
$rsvpURL = $baseURL . "/2/rsvps?event_id=$eventID&rsvp=yes&key=$apiKey";

// Fetch data
echo "Loading $eventURL...\n";

$eventJSON = file_get_contents($eventURL, false, $context);
if ($eventJSON === false) {
    die("Failed loading event from $eventURL");
}

echo "Loading $rsvpURL...\n";

$rsvpsJSON = file_get_contents($rsvpURL, false, $context);
if ($rsvpsJSON === false) {
    die("Failed loading rsvps from $rsvpsURL");
}

echo "Decoding...\n";

// Decode
$event = json_decode($eventJSON);
if ($event === false) {
    $err = json_last_error();
    die("Failed decoding event JSON. Error code: $err");
}

$rsvps = json_decode($rsvpsJSON);
if ($event === false) {
    $err = json_last_error();
    die("Failed decoding rsvps JSON. Error code: $err");
}

echo "Filtering...\n";

// Remove ignored members
foreach ($rsvps->results as $key => $rsvp) {
    if (in_array($rsvp->member->member_id, $ignoreIDs)) {
        unset($rsvps->results[$key]);
    }
}

// Save serialized
$len = file_put_contents('var/event.dat', serialize($event));
echo "Wrote var/event.dat: " . round($len/1024,1) . "KB\n";

$len = file_put_contents('var/rsvps.dat', serialize($rsvps));
echo "Wrote var/rsvps.dat: " . round($len/1024,1) . "KB\n";

echo "\nDONE\n";
