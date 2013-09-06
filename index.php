<?php

// ini_set('display_errors', 0);

$event = unserialize(file_get_contents('var/event.dat'));
$rsvps = unserialize(file_get_contents('var/rsvps.dat'));

if (empty($event)) die("Unable to load event data.");
if (empty($rsvps)) die("Unable to load rsvps data.");

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
            // Skip those without a full name (crazy detection, no?)
            if (strpos($attendee->member->name, ' ') === false) {
                continue;
            }
        ?>

        <div class="attendee" id="<?= $attendee->rsvp_id ?>">
            <?= $attendee->member->name ?><br />
        </div>

        <div class="modal hide fade" id="win-<?= $attendee->rsvp_id ?>">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Pobjednik izvlačenja je:</h3>
                <?php if (isset($attendee->member_photo->photo_link)) { ?>
                <img class="photo" src="<?= $attendee->member_photo->photo_link ?>" alt="<?= $attendee->member->name ?>" />
                <?php } else { ?>
                <img class="photo" src="http://placehold.it/300x300?text=<?= urlencode("?") ?>" alt="<?= $attendee->member->name ?>" />
                <?php } ?>
                <h3><?= $attendee->member->name ?></h3>
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn">Zatvori</a>
            </div>
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

<script src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="draw.js"></script>

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
