<?php

/* TIMEZONE SPECIFIC INFORMATION (DO NOT TOUCH) */

date_default_timezone_set('UTC');

$currentversion = '5.8.0';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SOFTWARE SPECIFIC INFORMATION (DO NOT TOUCH) */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* CCAUTH START */

define('USE_CCAUTH','0');

$ccactiveauth = array('Facebook','Google','Twitter');

$guestsMode = '1';
$guestnamePrefix = 'Guest';
$guestsList = '2';
$guestsUsersList = '2';


/* CCAUTH END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'integration.php');

if(USE_CCAUTH == '1'){
  include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'ccauth.php');
  $guestsMode = '0';
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* BASE URL START */

define('BASE_URL','/cometchat/');

/* BASE URL END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* COOKIE */

$cookiePrefix = 'cc_';        // Modify only if you have multiple CometChat instances on the same site

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LANGUAGE START */

$lang = 'en';

/* LANGUAGE END */

if (!empty($_COOKIE[$cookiePrefix."lang"])) {
  $lang = preg_replace("/[^A-Za-z0-9\-]/", '', $_COOKIE[$cookiePrefix . "lang"]);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$trayicon = array();

/* ICONS START */



$trayicon[] = array('home','Home','/','','','','','','');

$trayicon[] = array('chatrooms','Chatrooms','modules/chatrooms/index.php','_lightbox','600','300','','1','');

$trayicon[] = array('realtimetranslate','Translate Conversations','modules/realtimetranslate/index.php','_lightbox','280','310','','1','');

$trayicon[] = array('translate2','Translate This Page','modules/translate2/index.php','_lightbox','280','310','','1','');

$trayicon[] = array('games','Single Player Games','modules/games/index.php','_lightbox','465','300','','1','');

$trayicon[] = array('announcements','Announcements','modules/announcements/index.php','_lightbox','280','310','','1','');

$trayicon[] = array('share','Share This Page','modules/share/index.php','_lightbox','350','50','','1','');

$trayicon[] = array('scrolltotop','Scroll To Top','javascript:jqcc.cometchat.scrollToTop();','','','','','','');

$trayicon[] = array('broadcastmessage','Broadcast Message','modules/broadcastmessage/index.php','_lightbox','385','300','','1','');

$trayicon[] = array('facebook','Facebook Fan Page','modules/facebook/index.php','_lightbox','500','460','','1','');



/* ICONS END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* PLUGINS START */



$plugins = array('smilies','clearconversation','chattime','games','audiochat','avchat','block','broadcast','chathistory','filetransfer','handwrite','report','save','screenshare','writeboard','whiteboard','transliterate','stickers');



/* PLUGINS END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* EXTENSIONS START */



$extensions = array('mobileapp','desktop','mobilewebapp','jabber');



/* EXTENSIONS END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* CHATROOMPLUGINS START */

$crplugins = array('chattime','style','filetransfer','smilies');

/* CHATROOMPLUGINS END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'smilies'.DIRECTORY_SEPARATOR.'config.php');

/* SMILEYS START */

$smileys_default = array (
);

/* SMILEYS END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* EMOJI START */

$smileys = array_merge($smileys_default,$emojis);

/* EMOJI END */

$smileys_sorted = $smileys;
krsort($smileys_sorted);
uksort($smileys_sorted, "cmpsmileyskey");
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* BANNED START */

$bannedWords = array();
$bannedUserIDs = array();
$bannedUserIPs = array();
$bannedMessage = 'Sorry, you have been banned from using this service. Your messages will not be delivered.';

/* BANNED END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADMIN START */



define('ADMIN_USER','wwwquakb');

define('ADMIN_PASS','Admin@123');



/* ADMIN END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SETTINGS START */

$hideOffline = '1';			// Hide offline users in Who's Online list?
$autoPopupChatbox = '0';			// Auto-open chatbox when a new message arrives
$messageBeep = '1';			// Beep on arrival of message from new user?
$beepOnAllMessages = '1';			// Beep on arrival of all messages?
$minHeartbeat = '3000';			// Minimum poll-time in milliseconds (1 second = 1000 milliseconds)
$maxHeartbeat = '12000';			// Maximum poll-time in milliseconds
$searchDisplayNumber = '10';			// The number of users in Whos Online list after which search bar will be displayed
$thumbnailDisplayNumber = '40';			// The number of users in Whos Online list after which thumbnails will be hidden
$typingTimeout = '10000';			// The number of milliseconds after which typing to will timeout
$idleTimeout = '30000000000000000';			// The number of seconds after which user will be considered as idle
$displayOfflineNotification = '1';			// If yes, user offline notification will be displayed
$displayOnlineNotification = '1';			// If yes, user online notification will be displayed
$displayBusyNotification = '1';			// If yes, user busy notification will be displayed
$notificationTime = '5000';			// The number of milliseconds for which a notification will be displayed
$announcementTime = '15000';			// The number of milliseconds for which an announcement will be displayed
$scrollTime = '1';			// Can be set to 800 for smooth scrolling when moving from one chatbox to another
$armyTime = '0';			// If set to yes, time will be shown in 24-hour clock format
$disableForIE6 = '0';			// If set to yes, CometChat will be hidden in IE6
$hideBar = '0';			// Hide bar for non-logged in users?
$disableForMobileDevices = '1';			// If set to yes, CometChat bar will be hidden in mobile devices
$startOffline = '0';			// Load bar in offline mode for all first time users?
$fixFlash = '0';			// Set to yes, if Adobe Flash animations/ads are appearing on top of the bar (experimental)
$lightboxWindows = '1';			// Set to yes, if you want to use the lightbox style popups
$sleekScroller = '1';			// Set to yes, if you want to use the new sleek scroller
$desktopNotifications = '1';			// If yes, Google desktop notifications will be enabled for Google Chrome
$windowTitleNotify = '1';			// If yes, notify new incoming messages by changing the browser title
$floodControl = '0';			// Chat spam control in milliseconds (Disabled if set to 0)
$windowFavicon = '0';			// If yes, Update favicon with number of messages (Supported on Chrome, Firefox, Opera)
$prependLimit = '10';			// Number of messages that are fetched when load earlier messages is clicked
$blockpluginmode = '0';			// If set to yes, show blocked users in Who's Online list
$lastseen = '0';			// If set to yes, users last seen will be shown


/* SETTINGS END */

$notificationsFeature = 1;      // Set to yes, only if you are using notifications

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* APIKEY START */

$apikey = 'b8f960840534a45598f763dca22882b3';     // API key for RESTful APIs for User Management on custom coded sites


/* APIKEY END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



/* MEMCACHE START */

define('MEMCACHE','1');       // Set to 0 if you want to disable caching and 1 to enable it.
define('MC_SERVER','localhost');  // Set name of your memcache  server
define('MC_PORT','11211');      // Set port of your memcache  server
define('MC_USERNAME','');           // Set username of memcachier  server
define('MC_PASSWORD','');           // Set password your memcachier  server
define('MC_NAME','files');      // Set name of caching method if 0 : '', 1 : memcache, 2 : files, 3 : memcachier, 4 : apc, 5 : wincache, 6 : sqlite & 7 : memcached

/* MEMCACHE END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* COLOR START */

$color = 'glass';

/* COLOR END */

$color_original = $color;

if (!empty($_COOKIE[$cookiePrefix."color"])) {
  $color = preg_replace("/[^A-Za-z0-9\-]/", '', $_COOKIE[$cookiePrefix."color"]);
}

if (!empty($_REQUEST["cc_theme"]) && ($_REQUEST["cc_theme"] == 'synergy')) {
  $color = preg_replace("/[^A-Za-z0-9\-]/", '', $_REQUEST["cc_theme"]);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* THEME START */



$theme = 'standard';



/* THEME END */

$theme_original = $theme;

if (!empty($_COOKIE[$cookiePrefix."theme"])) {
  $theme = preg_replace("/[^A-Za-z0-9\-]/", '', $_COOKIE[$cookiePrefix."theme"]);
}

if (!empty($_REQUEST["cc_theme"])) {
  $theme = preg_replace("/[^A-Za-z0-9\-]/", '', $_REQUEST["cc_theme"]);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DISPLAYSETTINGS START */

define('DISPLAY_ALL_USERS','1');

/* DISPLAYSETTINGS END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DISABLEBAR START */

define('BAR_DISABLED','0');

/* DISABLEBAR END */

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* COMET START */

define('USE_COMET','0');        // Set to 0 if you want to disable transport service and 1 to enable it.
define('KEY_A','');
define('KEY_B','');
define('KEY_C','');
define('IS_TYPING','0');        // Set to 0 if you want to disable is Typing... feature and 1 to enable it.
define('MESSAGE_RECEIPT','0');  // Set to 0 if you want to disable message receipts feature and 1 to enable it.
define('TRANSPORT','cometservice');
define('CS_TEXTCHAT_SERVER','');

/* COMET END */

define('COMET_CHATROOMS','1');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */

define('REFRESH_BUDDYLIST','60');   // Time in seconds after which the user's "Who's Online" list is refreshed
define('DISABLE_SMILEYS','0');      // Set to 1 if you want to disable smileys
define('DISABLE_LINKING','0');      // Set to 1 if you want to disable auto linking
define('DISABLE_YOUTUBE','1');      // Set to 1 if you want to disable YouTube thumbnail
define('CACHING_ENABLED','0');      // Set to 1 if you would like to cache CometChat
define('GZIP_ENABLED','0');       // Set to 1 if you would like to compress output of JS and CSS
define('DEV_MODE','1');         // Set to 1 only during development
define('ERROR_LOGGING','1');      // Set to 1 to log all errors (error.log file)
define('ONLINE_TIMEOUT',USE_COMET?REFRESH_BUDDYLIST*2:($maxHeartbeat/1000*2.5));
                    // Time in seconds after which a user is considered offline
define('DISABLE_ANNOUNCEMENTS','0');  // Reduce server stress by disabling announcements
define('DISABLE_ISTYPING','1');     // Reduce server stress by disabling X is typing feature
define('CROSS_DOMAIN','0');       // Do not activate without consulting the CometChat Team
if (CROSS_DOMAIN == 0){
  define('ENCRYPT_USERID', '1');      //Set to 1 to encrypt userid
}else{
  define('ENCRYPT_USERID', '0');
  define('CC_SITE_URL', '');         // Enter Site URL only if Cross Domain is enabled.
}
define('DB_SESSION','0');      // If set to 1, sessions will be stored in the database.

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Pulls the language file if found

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'en.php');
if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$lang.'.php')) {
  include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$lang.'.php');
}

if (!defined('DB_AVATARFIELD')) {
  define('DB_AVATARTABLE','');
  define('DB_AVATARFIELD',"''");
}

$channelprefix = (preg_match('/www\./', $_SERVER['HTTP_HOST']))?$_SERVER['HTTP_HOST']:'www.'.$_SERVER['HTTP_HOST'];
$channelprefix = md5($channelprefix.BASE_URL);

if(defined('TAPATALK')&&TAPATALK==1){
  hooks_setTapatalk($plugins);
}
