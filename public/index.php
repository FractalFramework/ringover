<?php

require '../lib/core.php';
require '../app/operations.php';

$op=new operations;
$res=$op->picto(1);

$sql=new controller;
$cities=$sql->get_cities(); //pr($cities);
//$weather=$sql->get_weather(1); //pr($weather);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <script src="/../js/ajax.js"></script>
    <title>Weather Api</title>
</head>
<body>
    <div class="container">
        <h1>Weather Api</h1>

        <h2>Cities</h2>
        
        <button type="button" onclick="ajax_call(this)" data-action="get_cities" data-params="no">get_cities</button>
        
        <h2>Actions</h2>

        <select id="action_cities">
            <?php foreach($cities as $k=>$v): ?>
            <option value="<?=$v['city_label'] ?>"><?=$v['city_label'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" onclick="ajax_call(this)" data-action="del_city" data-params="no">Del city</button>
        <button type="button" onclick="ajax_call(this)" data-action="del_weather" data-params="no">Del weather</button>
        
        <h2>Create</h2>
        
        <button type="button" onclick="ajax_call(this)" data-action="post_city" data-params="no">Create city</button>
        
        <h2>Weather</h2>

        <input list="weathers" name="weather">
        <datalist id="weathers">
            <?php foreach($cities as $k=>$v): ?>
            <option value="<?=$v['city_label'] ?>"><?=$v['city_label'] ?></option>
            <?php endforeach; ?>
        </datalist>

        <button id="ty" type="button" onclick="ajax_call(this)" data-action="get_weather" data-params="no">Get weather</button>

        <h2>Results</h2>
        
        <div id="callback" class="render">
            <?=$res ?>
        </div>

    </div>
</body>
</html>