=== GetFreeCarCheck Free Car Check ===
Contributors: getfreecarcheck
Tags: car check, mot history, number plate, car reliability, ulez
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A free UK car check box for your site: MOT history, mileage, ULEZ, tax and recalls by number plate, or any model's MOT reliability.

== Description ==

Give your visitors a free UK car check. Two boxes, both free, with no key, no sign-up and no limits:

* **Free car check:** a number plate box. A visitor's check opens in a new tab with the car's full MOT history (every fail and advisory), its mileage record, ULEZ, car tax and recalls.
* **Car reliability card:** one model's first-time MOT fail rate against the average car of the same age, its most common MOT fails and a plate box, from over 100 million DVSA MOT tests.

Made for car dealers, garages, car blogs, reviews, forums and clubs.

Add a box with a shortcode:

* `[getfreecarcheck]`
* `[getfreecarcheck_reliability make="ford" model="fiesta"]`

or from Appearance → Widgets ("Free Car Check (GetFreeCarCheck)").

The boxes add no script to your pages and load lazily, so they won't affect your theme or your speed scores.

== External services ==

This plugin shows content from GetFreeCarCheck (https://getfreecarcheck.co.uk), a UK car check service, inside an iframe. Each time a page with a box loads, the visitor's browser requests that box from getfreecarcheck.co.uk, sending the usual request details (IP address, browser) and your site's host name so you can ask how many checks your box started. When a visitor enters a number plate, it is sent to getfreecarcheck.co.uk to run their check. No data is sent from your WordPress server.

* Terms of use: https://getfreecarcheck.co.uk/terms
* Privacy policy: https://getfreecarcheck.co.uk/privacy

The MOT figures contain public sector information licensed under the Open Government Licence v3.0, from the DVSA and the VCA.

== Frequently Asked Questions ==

= Is it free? =

Yes. Both boxes are free with no limits.

= Does it add a link to my site? =

Only if you choose to. Settings → GetFreeCarCheck has an optional credit line under each box; it is off until you turn it on.

= Which makes and models does the reliability card cover? =

Over 550 UK models. Use the make and model as you would write them, for example make="Mercedes-Benz" model="C Class".

== Changelog ==

= 1.0.0 =
* First release: the free car check box, the car reliability card, shortcodes and a widget.
