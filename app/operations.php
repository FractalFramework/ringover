<?php

class operations{

    public function sql(){
        $db=new PDO("mysql:dbname={$this->base};host={$this->host}",$this->user,$this->pass,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        //PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
        PDO::MYSQL_ATTR_INIT_COMMAND=>'SET CHARACTER SET UTF8']);

    }

    public function icosvg($n){
        $r=json('../json/weather-svg.json');
        foreach($r as $k=>$v)if($v[0]==(string)$n)return $v;
    }

    public function picto(int $n=1):string{
        [$n,$nm,$ic]=$this->icosvg($n);//$nm='Soleil'; $ic='day';
        $ret=img('../img/weather-svg/'.$ic.'.svg',36).span($nm,'bold');
        return $ret;
    }

    public function render_html(array $r):string{$ret='';
        foreach($r as $k=>$v)$ret.='<li>'.$v.'</li>'."\n";
        return '<ul>'.$ret.'</ul>';
    }

    public function render_json(array $r):string{$ret='';
        return json_encode($r);
    }

    public function get_cities():string{
        $sql=new controller;
        $cities=$sql->get_cities(); //pr($cities);
        $ret=$this->render_json($cities);
        return $ret;
    }

    public function post_city(string $town, string $country):string{
        $sql=new controller;
        $ok=$sql->post_city($n);
        return $ok;
    }

    public function del_city(int $id):string{
        $sql=new controller;
        $ok=$sql->del_city($id);
        return $ok;
    }

    public function get_weather(int $id):array{
        $sql=new controller;
        $r=$sql->get_weather($n); //pr($show);
        return $r;
    }

    public function post_weather(string $city_id, string $temperature, string $weather, string $precipitation, string $humidity, string $wind):string{
        $sql=new controller;
        $show=$sql->post_weather($n);
        $ok=$this->render($n);
        return $ok;
    }

    public function del_weather(int $id):string{
        $sql=new controller;
        $show=$sql->del_weather($n);
        $ok=$this->render($n);
        return $ok;
    }
    
}