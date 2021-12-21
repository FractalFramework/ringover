<?php

//use PDO;

class mysql{

    private $host='localhost';
    private $user='root';
    private $base='ringover';
    private $pass='';
    private $pdo;

    function __construct(){}//used to inject connection

    public function getPDO():PDO{
        return $this->pdo??$this->pdo=new PDO("mysql:dbname={$this->base};host={$this->host}",$this->user,$this->pass,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        //PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
        PDO::MYSQL_ATTR_INIT_COMMAND=>'SET CHARACTER SET UTF8']);
    }
}