<?php
class Video extends Persistencia {
    var $roteiro = null;
    var $name;

    function getByRoteiro($idRoteiro){
        return $this->getRows(0,999,array(),array("roteiro"=>"=".$idRoteiro));
    }

    
}