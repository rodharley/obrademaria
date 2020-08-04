<?php
class Foto extends Persistencia {
    var $roteiro = null;
    var $name;

    function getByRoteiro($idRoteiro){
        return $this->getRows(0,999,array(),array("roteiro"=>"=".$idRoteiro));
    }

    function salvaFoto($file,$roteiro){

        if($file['name'] != ''){            
            $names = explode(".",$file['name']);
            $nome = $this->roteiro->grupo->id."_foto.".$names[count($names)-1];
            $this->uploadArquivo($file,$nome,$this->getFolder());
            $this->name = $nome;
            $this->roteiro = $roteiro;
            $this->save();
        }
    }

    function getFolder(){
        return str_replace("admin/","",$this->URI)."img/fotos/";
    }

    function excluir(){
        if($this->name!= null && $this->name != '')
            $this->apagaImagem($this->name,$this->getFolder());
        $this->delete($this->id);
    }
}