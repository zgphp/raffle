<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/settings.php';

if (!defined("EVENT_ID")) die("EVENT_ID not defined in settings.php.");
if (!defined("API_KEY")) die("API_KEY not defined in settings.php.");

$eventID = EVENT_ID;
$key = "key=" . API_KEY;
$gaAccount = defined("GA_ACCOUNT") ? GA_ACCOUNT : null;

// Create a stream to define character encoding
$opts = array('http' => array('header' => 'Accept-Charset: utf-8'));
$context = stream_context_create($opts);

// Build URLs
$baseURL = "http://api.meetup.com";
$eventURL = $baseURL . "/2/event/$eventID?$key";
$rsvpURL = $baseURL . "/2/rsvps?event_id=$eventID&rsvp=yes&$key";

// Fetch data
$eventJSON = file_get_contents($eventURL, false, $context);
$rsvpsJSON = file_get_contents($rsvpURL, false, $context);

// Decode (amazing, no error checking whatsoever!)
$event = json_decode($eventJSON);
$rsvps = json_decode($rsvpsJSON);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="//netdna.bootstrapcdn.com/bootswatch/2.3.2/superhero/bootstrap.min.css" rel="stylesheet">
    <link href="draw.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
    <script src="draw.js"></script>
</head>
<body>

<div class="wrapper">
    <div class="jumbotron">
        <h1><?= $event->name ?></h1>
        <p class="lead">Senzacionalno izvlačenje nagrada</p>
    </div>

    <div class="attendees">
    <?php foreach ($rsvps->results as $attendee) { ?>
        <?php // Filter out organisers
            if ($attendee->member->name == 'Ivan Habunek') continue;
            if ($attendee->member->name == 'Miro Svrtan') continue;
        ?>
        <div class="attendee">
            <img class="thumb" src="<?= $attendee->member_photo->thumb_link ?>" alt="<?= $attendee->member->name ?>" />
            <?= $attendee->member->name ?><br />
            <span class="small"><?= date('Y-m-d H:i', $attendee->created / 1000) ?></span>
        </div>
    <?php } ?>
    </div>
    <div class="clearboth"></div>
    <div class="draw">
        <button class="btn" id="draw_button">Izvuci dobitnika</button>
    </div>
</div>

<?php if (!empty($gaAccount)) { ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?= $gaAccount ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php } ?>

</body>
</html>