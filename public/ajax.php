<?php
#called by ajax

require '../lib/core.php';
require '../app/operations.php';

$act=get('action');
$prm=get('params');

$op=new operations;
$res=$op->$act($prm);

echo $res;