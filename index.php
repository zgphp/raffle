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

?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Izvlačenje <?= $event->name ?> nagrada | ZgPHP Meetup    </title>
    <meta property="fb:admins" content="500311732" />
    <meta property="fb:app_id" content="572203872790464"/>
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Izvlačenje <?= $event->name ?> nagrada" />
    <meta property="og:url" content="http://zgphp.org/raffle/" />
    <meta property="og:description" content="<?= strip_tags($event->description) ?>" />
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
        <?php
            // Filter out organisers
            if (in_array($attendee->member->member_id, $ignoreIDs)) {
                continue;
            }
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

    <hr />

    <h2>Pravila</h2>

    <ul>
        <li>Pravo na učestvovanje u nagradnoj igri imaju svi koji su prijavili dolazak na <?= $event->name ?> putem <a href="<?= $event->event_url ?>">meetup.com</a> (RSVP) i prisutni su na meetupu.</li>
        <li>Svaki sudionik može dobiti samo jednu nagradu, ako se jedna osoba izvuče više puta, izvlačenje će se ponoviti.</li>
        <li>Sudionici koji su se prijavili više puta neće sudjelovati u nagradnoj igri.</li>
        <li>Sudionici koji su prijavili dolazak nakon početka meetupa (18:00) neće sudjelovati u nagradnoj igri.</li>
        <li>Organizatori ne sudjeluju u nagradnoj igri.</li>
    </ul>

    <h2>Izvorni kod</h2>

    <p>Izvorni kod ove aplikacije dostupan je na <a href="https://github.com/ZgPHP/raffle" target="_blank">Githubu</a>.</p>
    <p>Ukoliko uočite ikakve nepravilnosti, molimo da nas obavijestite, ili još bolje ispravite ih i pošaljete pull request.</p>
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
