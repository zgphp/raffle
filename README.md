ZgPHP Prize Draw App
====================

Picks a random person from people who have RSVP-ed to an event on meetup.com.

Installation
============

Clone from github to your htdocs (or equivalent).

```
git clone https://github.com/ZgPHP/raffle.git raffle
```

Copy `settings-example.php` to `settings.php` fill in the options.

You will need a meetup.com [API key](http://www.meetup.com/meetup_api/key/) and
the ID of the event for which to fetch attendees.

To fetch the data from meetup.com, run:

```
php load.php
```

This will retrieve the event and RSVP data from meetup.com and save it in
`var/event.dat` and `var/rsvps.dat`.

Usage
=====

Open the page from your browser.

Click button or press "i" to start the draw.
