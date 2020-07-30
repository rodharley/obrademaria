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
    var $itineraryes = null;
    var $videos =null;
    var $reviews = null;
    var $photos =null;

    function getContinentesDispoiveis (){
        $sql = "select * from ag_roteiro group by continent";
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
        if($this->likes == 0 && $this->unlikes == 0){
            return 0;
        }else{
            if($this->likes > $this->unlikes){
                //mais likes
                return 5;
            }else{
                //menos likes
                return 1;

            }
        }
    }
}