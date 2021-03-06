<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
* @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.bestofjoomla.com
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
?>

Changelog
------------
This is a non-exhaustive (but still near complete) changelog for
the Kunena 1.x, including beta and release candidate versions.
The Kunena 1.x is based on the FireBoard releases but includes some
drastic technical changes.
Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Kunena 1.0.7

26-January-2009 fxstein
+ JomSocial userlist integration for Kunena userlist link in front stats
- Remove old unused legacy code
^ Fixed broken PDF display
^ Corrected upgrade logic order

26-January-2009 Matias
# default_ex: Link to first unread message was sometimes broken
^ view: Message is marked new only if thread hasn't been read
+ kunena.credits.php: Added myself
# Stats should work again (typos fixed)
* My Profile: My Avatar didn't have security check for anonymous users

25-January-2009 fxstein
+ Basic JomSocial Integration
^ updated jquery to latest 1.3.1 minimized
^ fb_link class changes to CKunenaLinks
# Minor typo in include paths fixed
^ kunena.credits.php: Updated credits page
^ Various links updated
+ Kunena logos added to default and default_ex tamplates
# smile.class.php: parser references fixed

25-January-2009 Matias
# Stats: Visible even if they were disabled
# Stats: Wrong count in topics and messages
# Stats: Today/yesterday stats didn't include messages between 23:59
  and 00:01.
^ Stats: Optimized SQL queries for speed and saved 11-20 queries
! DATABASE UPDATED: new keys added to fb_messages and fb_users
# Emoticons: Broken "more emoticons" pop up in IE7.
# Forum Tools: Fixed CSS rules in default_ex
^ Anonymous user cannot be admin, saves many SQL queries
# Removing moved thread (or written by anonymous user) didn't
  work in showcat
+ view: Make new messages visible (green topic icon).
+ default_ex: Show number of new messages (just like in category view).
+ default_ex: Jump to first new message by clicking new message indicator.
! Current behaviour is "first message after logout or mark all forums read".
^ showcat, latestx: Use faster query to find all messages in a thread.
# Message posted notification page redirects after you click a link

24-January-2009 Matias
# Fixed over 100 xhtml bugs
^ No default size for [img]
^ Category parent list: jump to Board Categories with "Go" button
^ Forum stats show users in alphabetical order

01-January-2009 fxstein
+ Initial fork from FireBoard 1.0.5RC3

