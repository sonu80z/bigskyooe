<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Asset Version for Cache-Busting
|--------------------------------------------------------------------------
|
| Increment this when you deploy new CSS/JS. Browsers will cache assets
| until version changes, giving huge speed gains on repeat visits.
|
*/
defined('ASSET_VERSION') OR define('ASSET_VERSION', time());

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
defined('SMTP_HOST')      OR define('SMTP_HOST', "tls://bernstein.metanet.ch");
defined('SMTP_PORT')      OR define('SMTP_PORT', 465);
defined('SMTP_USER')      OR define('SMTP_USER', "noreply@digitalsurety.ch");
defined('SMTP_PASS')      OR define('SMTP_PASS', "1%cThl54");
*/
defined('SMTP_HOST')      OR define('SMTP_HOST', "smtp-mail.outlook.com");
defined('SMTP_PORT')      OR define('SMTP_PORT', 587);
defined('SMTP_USER')      OR define('SMTP_USER', "no-reply@digitalsurety.ch");
defined('SMTP_PASS')      OR define('SMTP_PASS', "L@1fke!1221");

defined('SMTP_REPLYTO')   OR define('SMTP_REPLYTO', "Digital Surety Support");
defined('SERVER_NAME')   OR define('SERVER_NAME', 'Digital Surety');

define ('EMAIL_CONFIG', serialize (array(
    "protocol" => "mail",
    "smtp_crypto" => "tls",
    "smtp_host" => SMTP_HOST,
    "smtp_port" => SMTP_PORT,
    "smtp_timeout" => 7,
    "smtp_user" => SMTP_USER,
    "smtp_pass" => SMTP_PASS,
    "priority" => 0,
    "mailtype" => "html",
    "newline" => "\r\n",
    "charset" => "utf-8",
    "wordwrap" => TRUE
)));

/*****************************************  system log comments  *****************************************/
define ('LOGAS_COMMENTS', serialize (array(
    "inviteA" => "invited email address",
    "approveA" => "approved admin username",
    "declineA" => "declined admin usename",
    "backupD" => "backup database",
    "loginA" => "login admin",
    "logoutA" => "logout admin",
    "profileA" => "change profile",
    "passwordA" => "changed password",
    "2faEA" => "2FA enabled",
    "2faDA" => "2FA disabled",
    "2faRA" => "2FA reset",
    "approveW" => "approved wire instruction",
    "declineW" => "declined wire instruction",
    "importWA" => "imported wallet address",
    "deleteWA" => "deleted one wallet address",
    "deleteW" => "deleted wire instruction",
    "createAT" => "created new ticket",
    "approveAT" => "approved Tikcet",
    "declineAT" => "declined ticket",
    "completeAT" => "completed ticket",
    "cancelAT" => "canceled ticket",
    "addAC" => "added comment",
    "inviteAU" => "invited user",
    "approveAU" => "approved user",
    "declineAU" => "declined user",
    "inviteAI" => "invited influencer",
    "approveAI" => "approved influencer",
    "declineAI" => "declined influencer",
    "loginU" => "login user",
    "logoutU" => "logout user",
    "profileU" => "changed profile",
    "passwordU" => "changed password",
    "addUW" => "added wire instruction ",
    "deleteUW" => "deleted bank instrucion",
    "addUDW" => "added wallet address ",
    "deleteUDW" => "deleted wallet address",
    "inviteUU" => "invited user",
    "inviteUI" => "invited influencer",
    "createUT" => "created ticket",
    "approveUT" => "approved ticket",
    "declineUT" => "declined ticket",
    "resubmitUT" => "resubmitted ticket",
    "cancelUT" => "canceled ticket",
    "addUC" => "added comment",
    "approveUIT" => "approved ticket created via admin"
)));


