<?php

include __DIR__.'/../vendor/autoload.php';
require '../src/Instagram.php';

/////// CONFIG ///////
$username = '';
$password = '';
$debug = true;
$truncatedDebug = false;

$photo = '';     // path to the photo
$caption = '';     // caption
//////////////////////

$i = new \InstagramAPI\Instagram($debug, $truncatedDebug);

$i->setUser($username, $password);

try {
    $i->login();
} catch (Exception $e) {
    $e->getMessage();
    exit();
}

try {
    $i->uploadTimelinePhoto($photo, $caption);
} catch (Exception $e) {
    echo $e->getMessage();
}
