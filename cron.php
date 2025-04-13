<?php

/**
* @author       Customized by sanljiljan, original by Asim Zeeshan (http://www.asim.pk/)
* @web          http://codecanyon.net/item/real-estate-agency-portal/6539169
* @date         23th November, 2014
* @copyright    No Copyrights on that file, but please link back in any way
*/
 
/*
|---------------------------------------------------------------
| CASTING argc AND argv INTO LOCAL VARIABLES
|---------------------------------------------------------------
|
*/

//
// Example cron job command for cpanel:
// php -q /home/geniuscr/public_html/lux/cron.php  /cronjob/research/output
//
// Other solutions can be found on:
// http://stackoverflow.com/questions/19269566/how-to-set-cron-job-url-for-codeigniter
//

$argc = array();
$argv = array();

if(isset($_SERVER['argc']))
    $argc = $_SERVER['argc'];

if(isset($_SERVER['argv']))
    $argv = $_SERVER['argv'];

$array_keys = array_keys($_GET);

// INTERPRETTING INPUT
if ($argc > 1 && isset($argv[1]))
{
    $_SERVER['PATH_INFO']   = $argv[1];
    $_SERVER['REQUEST_URI'] = $argv[1];
}
else if(isset($array_keys[1]))
{
    $_SERVER['PATH_INFO']   = $array_keys[1];
    $_SERVER['REQUEST_URI'] = $array_keys[1];
}
else
{
    $_SERVER['PATH_INFO']   = '/cronjob/index';
    $_SERVER['REQUEST_URI'] = '/cronjob/index';
}
 
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