<?php

//ringover_test
//Davy Hoyau 12/2021
/*
The objective is to build the shortest Api system
*/

//lib
function n(){return "\n";}
function pr($p){print_r($p);}
function vd($p){var_dump($p);}
function get($k){return filter_input(INPUT_GET,$k);}
function post($k){return filter_input(INPUT_POST,$k);}
//function img($d,$p=''){return '<img src="'.$d.'" width="'.$p.'" />';}
//function get_json($f){$d=file_get_contents($f); return json_decode($d,true);}

//mysql
function sql(){
return new PDO("mysql:dbname=ringover;host=localhost",'root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::MYSQL_ATTR_INIT_COMMAND=>'SET CHARACTER SET UTF8']);}

function get_cities(){$pdo=sql();
$stmt=$pdo->query('select city_id,city_label,country from city');
return $stmt->fetchAll(\PDO::FETCH_ASSOC);}

function post_city($r){$pdo=sql();
['city_label'=>$city_label,'country'=>$country]=$r;
$sql="INSERT INTO city (country, city_label) VALUES (:country, :city_label)";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(':country',$country);
$stmt->bindValue(':city_label',$city_label);
$inserted=$stmt->execute();
return $inserted?'ok':'ko';}

function del_city($id){$pdo=sql();
$sql='DELETE FROM city WHERE city.city_id = ? ';
$stmt=$pdo->prepare($sql);
$deleted=$stmt->execute([$id]);
return $deleted?'ok':'ko';}

function get_weathers(){$pdo=sql();
$stmt=$pdo->prepare('select weather_id,weather.city_id,city_label from weather inner join city on weather.city_id=city.city_id');
$stmt->execute();
return $stmt->fetchAll(\PDO::FETCH_ASSOC);}

function get_weather($id){$pdo=sql();
$stmt=$pdo->prepare('select weather.city_id,temperature,weather,precipitation,humidity,wind,city_label from weather inner join city on weather.city_id=city.city_id where weather.city_id = ?');
$stmt->execute([$id]);
return $stmt->fetchAll(\PDO::FETCH_ASSOC);}

function post_weather($city_id, array $r){$pdo=sql();
['temperature'=>$temperature,'weather'=>$weather,'precipitation'=>$precipitation,'humidity'=>$humidity,'wind'=>$wind]=$r;
$sql = "INSERT INTO weather (city_id, temperature, weather, precipitation, humidity, wind) VALUES (:city_id, :temperature, :weather, :humidity, :weather, :wind)";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(':city_id',$city_id);
$stmt->bindValue(':temperature',$temperature);
$stmt->bindValue(':weather',$weather);
$stmt->bindValue(':precipitation',$precipitation);
$stmt->bindValue(':humidity',$humidity);
$stmt->bindValue(':wind',$wind);
$inserted=$stmt->execute();
return $inserted?'ok':'ko';}

function del_weather($id){$pdo=sql();
$sql='DELETE FROM weather WHERE weather_id = ? ';
$stmt=$pdo->prepare($sql);
$deleted=$stmt->execute([$id]);
return $deleted?'ok':'ko';}

//render
$action=get('action');
$city_id=get('city_id');
$weather_id=get('weather_id');
//pr($_GET);

//called by ajax
if($action){
	$posts=$_POST??[]; //pr($posts);
	switch($action){
		case('get_cities'):$res=get_cities(); break;
		case('post_city'):$res=post_city($posts); break;
		case('del_city'):$res=del_city($city_id);break;
		case('get_weather'):$res=get_weather($city_id);break;
		case('post_weather'):$res=post_weather($city_id,$posts);break;
		case('del_weather'):$res=del_weather($weather_id);break;
	}
	if(is_array($res))echo json_encode($res);
	elseif($res=='ok')echo '<div class="valid">'.$action.' : ok</div>';
	else echo '<div class="error">'.$action.' : not ok</div>';
	exit;}

//datas
$cities=get_cities(); //pr($cities);
$weathers=get_weathers(); //pr($weathers);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style type="text/css">
		body{border:0; background:#ddd;}
		form,select,button{display:inline-block;}
		.container{width:900px; margin:0 auto; background:white; padding:20px;}
		.render{border:1px solid gray; background:silver; margin:20px 0; padding:10px;}
		.valid{border:1px solid green; background-color:rgba(0,200,0,0.5); margin:20px 0; padding:10px;}
		.error{border:1px solid red; background-color:rgba(200,0,0,0.5); margin:20px 0; padding:10px;}
	</style>
    <script>
var httpRequest;
var _URL;

//output
function ajax_callback(target){
if(httpRequest.readyState===XMLHttpRequest.DONE){
	if(httpRequest.status===200){
		var res=httpRequest.responseText;
		document.getElementById(target).innerHTML=res;} 
	else alert('error httprequest');}}

function ajax_req(target,action,url,fd){
	httpRequest=new XMLHttpRequest();
	if(!httpRequest){alert('error httprequest'); return false;}
	httpRequest.onreadystatechange=function(){ajax_callback(target);}
	httpRequest.open('POST',url,true);
	httpRequest.send(fd);}

//enter point
function ajax_call(target,action,form){
	var ra=action.split('_');//get_city
	var fd=captures(form);
	//construct the url
	var url='/'+ra[0]+'/cities';
	if(_URL)url+=_URL; //console.log(_URL);
	ajax_req(target,action,url,fd);}

function captures(name){_URL='';
	var ob=document.forms[name];
	//we decide to store url in first select
	//console.log((ob[0].type).split('-')[0]);
	if(ob!=undefined && (ob[0].type).split('-')[0]=='select')_URL=ob[0].options[ob[0].selectedIndex].value;
	var fd=new FormData();
	if(ob)for(i=0;i<ob.length;i++){
		var ty=ob[i].type.split('-')[0];
		if(ty=='text' || ty=='number')fd.append(ob[i].name,ob[i].value);
		else if(ty=='select')fd.append(ob[i].name,ob[i].options[ob[i].selectedIndex].value);}
	return fd;}
    </script>
    <title>Weather Api</title>
</head>

<body>
    <div class="container">
        <h1>Weather Api</h1>
        
        <h2>Get cities</h2>
        <button onClick="ajax_call('cities_result','get_cities','')" data-action="get_cities">Get cities</button>
        <button onClick="getElementById('cities_result').innerHTML='';">reset</button>
        <div id="cities_result" class="render"></div>
        
        <h2>Get weather</h2>
        <form name="get_weather_form" action="javascript:return false;">
            <select id="url">
                <option value="" selected>Choose...</option>
        <?php
        foreach($cities as $k=>$v)
            echo '<option name="city_id" value="/:'.$v['city_id'].'/weather">'.$v['city_label'].'</option>'; ?>
            </select>
        </form>
        <button onClick="ajax_call('weather_results','get_weather','get_weather_form')">Get weather</button>
        <button onClick="getElementById('weather_results').innerHTML='', document.forms['get_weather_form'].reset()">reset</button>
        <div id="weather_results" class="render"></div>
        
        
        
        <h2>Create city</h2>
        
        <form name="create_city_form" action="javascript:return false;">
            <div><input type="text" id="city_label" name="city_label" value=""> <label for="city_label">City label</label></div>
            <div><input type="text" id="country" name="country" value=""> <label for="country">Country</label></div>
        </form>
        <button onClick="ajax_call('create_city_results','post_city','create_city_form')" data-action="post_city">Create city</button>
        <button onClick="getElementById('create_city_results').innerHTML='', document.forms['create_city_form'].reset()">reset</button>
		<div id="create_city_results" class="render"></div>
        
        <h2>Create weather</h2>
        
        <form name="create_weather_form" action="javascript:return false;">
            <select id="url">
                <option value="" selected>Choose...</option>
        <?php
        foreach($cities as $k=>$v)
            echo '<option name="city_id" value="/:'.$v['city_id'].'/weather">'.$v['city_label'].'</option>'; ?>
            </select>
        
            <div><input type="number" id="temperature" name="temperature" value=""> <label for="temperature">temperature</label></div>
            <div><select id="weather" name="weather">
                <option value="SUNNY">SUNNY</option>
                <option value="RAINY">RAINY</option>
                <option value="WINDY">WINDY</option>
                <option value="FOGGY">FOGGY</option>
                <option value="SNOW">SNOW</option>
                <option value="HAIL">HAIL</option>
                <option value="SHOWER">SHOWER</option>
                <option value="LIGHTNING">LIGHTNING</option>
                <option value="RAINDBOW">RAINDBOW</option>
                <option value="HURRICANE">HURRICANE</option>
            </select>
            <label for="weather">weather</label></div>
            <div><input type="number" id="precipitation" name="precipitation" value=""> <label for="precipitation">precipitation</label></div>
            <div><input type="number" id="humidity" name="humidity" value=""> <label for="humidity">humidity</label></div>
            <div><input type="number" id="wind" name="wind" value=""> <label for="wind">wind</label></div>
        </form>
        <button onClick="ajax_call('create_weather_results','post_weather','create_weather_form')" data-action="post_city">Create weather</button>
        <button onClick="getElementById('create_weather_results').innerHTML='', document.forms['create_weather_form'].reset()">reset</button>
    <div id="create_weather_results" class="render"></div>
    
    

        <h2>Del city</h2>
        <form name="del_cities_form" action="javascript:return false;">
            <select id="url">
                <option value="" selected>Choose...</option>
		<?php
        foreach($cities as $k=>$v)
			echo '<option name="city_id" value="/:'.$v['city_id'].'">'.$v['city_label'].'</option>'; ?>
            </select>
        </form>
        <button onClick="ajax_call('del_city_result','del_city','del_cities_form')">Del cities</button>
        <button onClick="getElementById('del_city_result').innerHTML='', document.forms['del_cities_form'].reset()">reset</button>
        <div id="del_city_result" class="render"></div>

        <h2>Del weather</h2>
        <form name="del_weather_form" action="javascript:return false;">
            <select id="url">
                <option value="" selected>Choose...</option>
		<?php
        foreach($weathers as $k=>$v)
			echo '<option name="weather_id" value="/:'.$v['city_id'].'/weather/:'.$v['weather_id'].'">'.$v['weather_id'].' ('.$v['city_label'].')'.'</option>'; ?>
            </select>
        </form>
        <button onClick="ajax_call('del_weather_result','del_weather','del_weather_form')">Del weather</button>
        <button onClick="getElementById('del_weather_result').innerHTML='', document.forms['del_weather_form'].reset()">reset</button>
        <div id="del_weather_result" class="render"></div>
            
	</div>
</body>
</html>