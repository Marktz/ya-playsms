## What You Need (Hardware): ##
  1. Computer
    * A laptop is recommended, as its battery will allow the hotline to run during brief power outages.
  1. Bluetooth USB Dongle
    * If you have a newer laptop Bluetooth may be built-in, in which case you will not need the dongle!
  1. Bluetooth-capable Cellphone
    * This does not have to be some new 3G model—many older phones will work as well.
  1. Broadband Internet Connection
    * Internet is only necessary during setup process, not for day-to-day operation

## Step One: Get Ubuntu ##

> Your computer needs an operating system.  We will be using the free Ubuntu Linux, Desktop Edition.  You can download it from [here](http://www.ubuntu.com/products/WhatIsUbuntu/desktopedition).  I won’t go into how to install Ubuntu—there are many good tutorials on that, such as [this one](http://howtoforge.com/the_perfect_desktop_ubuntu_gutsy_gibbon).

## Step Two: Configure Ubuntu ##

There is one small change we need to make to Ubuntu before we proceed—we need to tell Ubuntu that it can download its software from anywhere:

  1. Go to System->Administration->”Synaptic Package Manager”.
  1. Choose Settings->Repositories.
  1. Check everything listed under “components” and click “OK”.
  1. It may then ask you to reload your list of packages, which you should choose to do.

## Step Three: Get PlaySms ##

  1. Download the latest PlaySms from [here](http://code.google.com/p/ya-playsms) (look under “Featured Downloads”).  When you open the file, it will download everything else that it needs.  PlaySms has many requirements—this may take some time.
  1. PlaySms’s installer will ask you a question about your database—simply accept the defaults.

## Step Four: Configure PlaySms ##

  1. Open Firefox and go to “localhost/playsms”.
  1. You should see the PlaySms login.  You may want to bookmark this page.
  1. The first time you logon, the username/password will be “admin”/”admin”.
  1. In the left-hand menu under “Personal”, click “Preferences”.
  1. Change the password to something other than “admin”.
  1. Change the “Mobile number” to the phone number of the hotline’s phone.
  1. Click “Save”.
  1. In the left-hand menu under “Administration”, click “Main Configuration”.
  1. Change the “Website Title” to the name of your SMS hotline.
  1. Click “Save”.
  1. In the left-hand menu under “Personal”, click “Phonebook”.
  1. Click “Add Number to Group”
  1. Choose the “admin” group.
  1. Enter the name and phone number of the person who will be administrating the hotline.  The hotline can be configured to text this person periodically.
  1. Click “Add”.  If there is more than one administrator, then add their names/numbers to the “admin” group as well.

## Step Five: Attach Your Phone (also called “pairing” or “bonding” the phone) ##

  1. Plug your Bluetooth USB dongle into the computer and restart.  (Skip this step if you have Bluetooth built-in to your computer.)
  1. On your phone, turn on Bluetooth.  How to do this varies from phone to phone.  On Nokias, it is usually under Connect->Bluetooth.
  1. Change your phone’s “visibility” so that it is not hidden.
  1. Give your phone a name, such as “smshotline”.  How to do this varies from phone to phone.  On Nokias, it is usually under Connect->Bluetooth->”My Phone’s Name”
  1. In PlaySms, in the left-hand menu under “Administration”, click “Manage Gateway”.  Then click on “Bind SMSC”.  You may have to wait a moment as the system detects your cellphone.
  1. Choose your phone from the “Select Modem” list and click “Bind”.
  1. You phone will ask you for a password (for “pairing” or “bonding”).  Enter “1234”.
  1. If your phone keeps asking if it’s ok for the computer to connect, you will need to disable the prompting.  How to do this varies from phone to phone.  On Nokias, it is usually under Connect->Bluetooth->”Paired Devices”, then find the computer in the list and set it to “Authorized”.

## Step Six: Test PlaySms ##

  1. In PlaySms, in the left-hand menu under “Personal”, click “Send text SMS”.
  1. Choose a phone number from the list by double-clicking on it, or type in a number by hand (in the field below the phone number list).
  1. Type in a test message into the “your message” field.
  1. Notice how as you type the “characters left” and “SMSes” fields keep track of how long your sms will be.  (This is just like the feedback on many phones.)
  1. Click send.
  1. The destination phone number should get your text message.
  1. Under “Personal”, click on “Outbox”.  This will show all messages sent by the system and whether they succeeded.
  1. If this did not work, please see the Troubleshooting section of this tutorial.

## Step Seven: Enter Autoreplies into PlaySms ##

> While PlaySms has many features, the feature that a hotline will use most will be the “Autoreplies” feature.  When you enter an Autoreply, it tells PlaySms to send a reply text whenever the hotline receives a text containing certain “keywords”.
> You can add/edit Autoreplies in PlaySms by clicking on “Manage SMS autoreplies” under “Features” in the left-hand menu.  PlaySms comes with a sample “help” keyword.  Click the “[+]” sign to preview all of the “scenarios” under that keyword.  For more information about autoreplies, click on the ‘help’ link.

## Step Eight: Maintaining the Hotline ##

> You can have the system text you regularly through the use of the “Autosend” feature (in the left-hand menu under “Features”).  For example, you can have the system text a number whenever the system starts up (useful for knowing if the system is back up after a blackout).  You could also have PlaySms send a daily text to your cell carrier for balance updates or “unlimited text” promotions.

> You can also have system messages be broadcast to the “admin” group.  Go to “Main Configuration” (under “Administration”).  In the “System messages are sent from” field, fill in the number or shortcode whose messages you would like to forward.

> You can combine autosending and the system message forwarding to get balance updates sent to you.  We will use an example from the “Smart” cell carrier in the Philippines.  Create an Autosend that daily sends “?1515” to “214” (this is the “balance check” code for Smart).  Then in the “Main Configuration”, add “BUDDY” to the “system messages” field.
> The autosend will cause Smart to send a balance update text to the system, which is always from “BUDDY”.  The balance update will then be broadcast out to the “admin” group.

## Troubleshooting ##

  * _Problems binding/pairing the phone…_
    * A good program for troubleshooting the phone is the Blueman Bluetooth Manager.  Use to to bond/pair your phone.  Attempt to browse the phone and send/receive a file.

  * _After running for a long time the computer can’t send/receive texts anymore!_
    * It may be that the phone or Kannel (the underlying phone software) is getting confused.  Go to “Manage Gateway” (under “Administration”) and change “Restart Kannel Regularly?” to “Daily”.  Try that for a while.  If that doesn’t work, increase how often it restarts to “Hourly”.
If you are still having problems,

