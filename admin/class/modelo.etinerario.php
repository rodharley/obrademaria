<?php 
class Etinerario extends Persistencia {
var $title;
var $description;
var $order;
var $roteiro = null;

function getByRoteiro($idRoteiro){
    return $this->getRows(0,999,array(),array("roteiro"=>"=".$idRoteiro));
}

function salva($order,$title,$description,$roteiro){
    $this->id = null;
    $this->order = $order;
    $this->description = $description;
    $this->title = $title;
    $this->roteiro = $roteiro;
    $this->save();
  
}



function excluir($id){
    $this->getById($id);
    $this->delete($this->id);
}

}