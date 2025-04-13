<?php

/**
* @author       by sanljiljan
* @web          http://codecanyon.net/item/real-estate-agency-portal/6539169
* @date         20th December, 2014
* @copyright    No Copyrights on that file, but please link back in any way
*/
 
/*
|---------------------------------------------------------------
| CASTING argc AND argv INTO LOCAL VARIABLES
|---------------------------------------------------------------
|
*/

//
// Example for search seo optimization via mod rewrite for codeigniter
//
//.htaccess file content:
//Options +FollowSymlinks
//RewriteEngine on
//RewriteRule ^(.*)\.htm$ /q2uri.php?search=$1 [NC]
//
//Then usage should be:
//zagreb.htm will be rewrited to:
//index.php?search=zagreb

$_SERVER['PATH_INFO']   = '/';
$_SERVER['REQUEST_URI'] = '/index.php?search='.$_GET['search'];
$_SERVER['SCRIPT_NAME'] = '/index.php';
 
/*
|---------------------------------------------------------------
| PHP SCRIPT EXECUTION TIME ('0' means Unlimited)
|---------------------------------------------------------------
|
*/
set_time_limit(0);

require_once('index.php');
 
/* End of file test.php */

?>