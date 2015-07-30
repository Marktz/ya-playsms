# Introduction #
The big change in this version is that PlaySms is now distributed as an Ubuntu/Debian Linux package.  Also, it defaults to using kannel.  Various bug fixes and some small features (autosending) have also been done.

Some database changes have also been done, so beware v0.8.1 upgraders.

# Details #
From the changelog:

  * created debian package for ubuntu installation so that it will work out of the box with little to no configuring
  * changed default gateway to kannel
  * removed sms limits, can now send multi-part smses
  * remove restriction on not being able to send quotes
  * removed unnecessary keywords
  * changed sms message entry to keep count of chars left and multi-part smses left, just like writing an sms on a phone does
  * fixed deleting autoreply\_scenarios and auto\_replies
  * added renaming of autoreply code
  * added autosend feature (uses cron on linux systems)
  * added "system from" feature, so that texts from the system (like balance updates) can be forwarded to the 'admin' group
  * added kannel-bug workaround in deb package (kannel doesn't work after reboot on ubuntu)
  * started using pear's db\_dataobject and db\_dataobject\_formbuilder stuff and cleaning up some of the code