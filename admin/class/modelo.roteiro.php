<?php 
class Roteiro extends Persistencia {
    var $cardImage;
    var $cardTitle;
    var $unlikes;
    var $cardDescription;
    var $continent;
    var $likes;
    var $grupo =null;
    var $description;
    var $title;
    var $image;
    var $countDown;
    var $itineraryes = null;
    var $videos =null;
    var $reviews = null;
    var $photos =null;


    function pesquisa(){
        return $this->getRows();
    }

    function getCountDown (){        
        return $this->getRow(array("countDown"=>"=1"));
    }

    function getContinentesDispoiveis (){
        $sql = "select * from ag_roteiro group by continent";
        return $this->getSQL($sql);
    }

    function getRoteirosRandomicos($qtd){
        $sql = "select * from ag_roteiro order by rand() limit 0,$qtd";
        return $this->getSQL($sql);
    }
    function getByContinent($continent,$count){
        return $this->getRows(0,$count,array("likes"=>"desc"),array("continent"=>"='".$continent."'"));
    }

    function pesquisar($termo='',$ano=null,$contador=false,$init=0,$end=99999){
        if($contador){
            $sql = "select cound(r.id) as total from ag_roteiro r inner join ag_grupo g on g.id = r.grupo ";
        }else{
            $sql = "select r.* from ag_roteiro r inner join ag_grupo g on g.id = r.grupo ";
        }
        $sql .= "where 1 = 1 ";

        if($termo != ''){
            $sql .= " and r.title like '%$termo%' ";
        }
        if($ano != null){
            $sql .= " and g.ano = $ano";
        }
        $sql .= " order by g.ano asc";

        $sql .= " limit $init, $end";

        if($contador){
            $rs = $this->DAO_ExecutarQuery($sql);
            return $this->DAO_Result($rs,"total",0);
        }else{
            return $this->getSQL($sql);
        }

        
    
    }

    function getNumberStars(){
        $result = $this->likes - $this->unlikes;
        
        if($result < -9){
            return 1;
        }
        if($result < -4){
            return 2;
        }
        if($result < 1){
            return 3;
        }
        if($result < 6){
            return 4;
        }
        return 5;
        
    }

    function getStarsHtml(){
        $n = $this->getNumberStars();
        $html = '<i class="fa ';
        $html .= $n < 1 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 2 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 3 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 4 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 5 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        return $html;
    }
}