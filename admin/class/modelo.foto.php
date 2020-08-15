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
            $nome = $this->retornaNomeUnico($roteiro->id."_foto.".date('YmdHis').'.'.$names[count($names)-1],$this->getFolder());
            $this->uploadArquivo($file,$nome,$this->getFolder());
            $this->name = $nome;
            $this->roteiro = $roteiro;

            $qtd = count($roteiro->photos)+1;

                $x = floor($qtd/8);
                if($qtd == 1+intval($x*8)){
                    $width = 700;
                    $heigth = 466;

                }else if($qtd == 4+intval($x*8)){
                    $width = 281;
                    $heigth = 386;

                }else{

                    $width = 281;
                    $heigth = 190;
                } 

                $this->resizeImage($this->getFolder(),$nome,$width,$heigth);


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