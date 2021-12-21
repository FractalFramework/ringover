<?php

class Controller extends mysql{
    protected $db;

    function __construct(){
        $db=new mysql();
        $this->db=$db;
    }

    public function get_cities(){
        $stmt=$this->db->getPDO()->query('select city_label,country from city');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function post_city(string $town, string $country){
        $sql = "INSERT INTO `city` (`country`, `city_label`) VALUES (:country, :city_label)";
        $stmt=$this->db->getPDO()->prepare($sql);
        $stmt->bindValue(':country', $country);
        $stmt->bindValue(':city_label', $city_label);
        $inserted=$stmt->execute([$id]);
        return $inserted?'ok':'ko';
    }

    public function del_city(int $id){
        $sql='DELETE FROM `city` WHERE `city`.`city_id` = ? ';
        $stmt=$this->db->getPDO()->prepare($sql);
        $deleted=$stmt->execute([$id]);
        return $deleted?'ok':'ko';
    }

    public function get_weather(int $id){
        $stmt=$this->db->getPDO()->prepare('select * from weather where weather_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function post_weather(string $city_id, string $temperature, string $weather, string $precipitation, string $humidity, string $wind){
        $sql = "INSERT INTO `weather` (`city_id`, `temperature`, `weather`, `precipitation`, `humidity`, `wind`) VALUES (:city_id, :temperature, :weather, :humidity, :weather, :wind)";
        $stmt=$this->db->getPDO()->prepare($sql);
        $stmt->bindValue(':city_id', $city_id);
        $stmt->bindValue(':temperature', $temperature);
        $stmt->bindValue(':weather', $weather);
        $stmt->bindValue(':precipitation', $precipitation);
        $stmt->bindValue(':humidity', $humidity);
        $stmt->bindValue(':wind', $wind);
        $inserted=$stmt->execute([$id]);
        return $inserted?'ok':'ko';
    }

    public function del_weather(int $id){
        $sql='DELETE FROM `city` WHERE `weather`.`weather_id` = ? ';
        $stmt=$this->db->getPDO()->prepare($sql);
        $deleted=$stmt->execute([$id]);
        return $deleted?'ok':'ko';
    }

}