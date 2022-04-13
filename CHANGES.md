Version 2022041300 (Release 3.8)
* Added compatibility with Moodle 4.0
* Fix critical issues found in CI

-----

Version 2022022300 (Release 3.7.7.1)
* Added forced check to see if bcmath php extension is enabled (which is required as of version 3.7.6)

-----

Version 2022022201 (Release 3.7.7)
* Fix on selecting user while trying to delete user data in GDPR cleanup

-----

Version 2022022200 (Release 3.7.6 - Unreleased)
* Fixed rounding bug sometimes causing differences of 1 cent.

-----

Version 2021110500 (Release 3.7.5)
* Hid error that causes infinite mails if site admin deletes instance with open transactions.

-----

Version 2021102000 (Release 3.7.4)
* Fixed tables that have errors when moving to other pages in admin interface.
* Coding standards fixes
* Modified CI deployment

-----

Version ??? (Release 3.7.3 (build ???))
* Added CANCEL-FAILURE from Pay as valid ending status

-----
Version 2021020102 (Release 3.7.2 (build 2021020102))
* Introduced new method to thank users for buying a course (thank you Gottmer.nl)

-----
Version 2021020101 (Release 3.7.1 (build 2021020101))
* Add 1 to coupon usage (as indicated by Hoda Farazandeh).

-----
Version 2021020100 (Release 3.7.0 (build 2021020100))
* Added privacy language strings
* Validated working for 3.9 / 3.10
* Changed pix_url  to image_url (thought that was done already, alas....)

-----
Version 2017111300 (Release 3.0.0 (build 2017111300))
* Implemented privacy API
* Added CHANGELOG.md
* Added README.md
* Added LICENSE.md
* Fixed a lot of errors related to Moodle coding standards.
* Added option to allow only specific cohorts (customint5)
* Generic code overhaul
* Modified MINIMUM Moodle requirement to 3.4 (due to privacy API)
* Messages are now instances of core\message\message

-----
