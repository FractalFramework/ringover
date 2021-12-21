<?php

#called by url
/*
/get/cities
/post/cities
/del/cities/:city_id
/del/cities/:city_id/weather
/post/cities/:city_id/weather
/del/cities/:city_id/weather/:weather_id
*/

require '../lib/core.php';
require '../app/operations.php';

$act=get('action');
$id=get('id');
$type=get('type');
$prm=json_decode(get('params'),true);

$op=new operations;
$res=$op->$act($prm);

echo $res;