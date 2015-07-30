# Introduction #
The big change in this version is that database upgrading is now easy (see db/readme.txt) or automatic if you're using the deb package.

Other new things: auto-resending on failure, better inbox/outbox, and autoreply error message.

# Details #
  * can now upgrade old databases! (see `db/readme.txt` and `db/playsms-upgrade_*.sql`)
  * added char count/sms count to autoreply listing
  * added support for autoreplying when the message doesn't match anything (using special `"_UNKNOWN_"` code)
  * added resend-on-fail logic
  * added manual resend from the Outbox
  * can browse into older entries in Inbox/Outbox
  * for admins, can now look at Inbox/Outbox for all users
  * added autosend 'startup'
  * added kannel restarting and full system restarting (unix only)
  * added sms editor to templates page
  * removed length limit in database for many sms fields (they were arbitrarily short??)
  * more DB\_DataObject usage
  * debian package:
    * now uses dbconfig-common, so database installation/upgrading is automated
    * cleaned up cron for autosending