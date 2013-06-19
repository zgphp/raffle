<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/settings.php';

$eventID = defined("EVENT_ID") ? EVENT_ID : null;
$apiKey = defined("API_KEY") ? API_KEY : null;
$gaAccount = defined("GA_ACCOUNT") ? GA_ACCOUNT : null;

if (empty($eventID)) die("EVENT_ID not defined in settings.php.");
if (empty($apiKey)) die("API_KEY not defined in settings.php.");

// Create a stream to define character encoding
$opts = array('http' => array('header' => 'Accept-Charset: utf-8'));
$context = stream_context_create($opts);

// Build URLs
$baseURL = "http://api.meetup.com";
$eventURL = $baseURL . "/2/event/$eventID?key=$apiKey";
$rsvpURL = $baseURL . "/2/rsvps?event_id=$eventID&rsvp=yes&key=$apiKey";

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
    <title>Izvlačenje ZgPHP meetup #22 nagrada| ZgPHP Meetup    </title>    
    <meta property="fb:admins" content="500311732" />
	<meta property="fb:app_id" content="572203872790464"/>
	<meta property="og:type" content="article" />
	<meta property="og:title" content="Izvlačenje ZgPHP meetup #22 nagrada" />
	<meta property="og:url" content="http://zgphp.org/raffle/" />
	<meta property="og:description" content="Meetup se održava u klubu Mama, Preradovićeva 18, u četvrtak 20. lipnja s početkom od 17:30. Dolazak na meetup možete najaviti na meetup.com. Predavanja će održati Valentin Rep, softverski inženjer..." />
	<meta property="og:site_name" content="ZgPHP Meetup" />
	<meta property="og:image" content="http://zgphp.org/wp-content/uploads/2013/02/zgphp_meetup_header.png" />	
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
