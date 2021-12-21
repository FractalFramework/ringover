<?php

function load($d){$dr='../';
    $r=['app','datas','views'];
	if($r)foreach($r as $k=>$v){$f=$dr.''.$v.'/'.$d.'.php'; if(file_exists($f)){require_once $f; return;}}
	$f=$dr.''.$d.'.php'; if(file_exists($f)){require_once $f; return;}}
spl_autoload_register('load');

function vd($p){var_dump($p);}
function pr($p){print_r($p);}

function get($k){return filter_input(INPUT_GET,$k);}

function img($d,$p=''){return '<img src="'.$d.'" width="'.$p.'" />';}
function span($d,$p=''){return '<span class="'.$p.'">'.$d.'</span>';}

function json($f){$d=file_get_contents($f); return json_decode($d,true);}