<?php
class GaleriaFoto extends Persistencia {
    var $galeria = null;
    var $name;

    function getByGaleria($idGaleria){
        return $this->getRows(0,999,array(),array("galeria"=>"=".$idGaleria));
    }

    
}