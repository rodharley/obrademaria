<?php
class Galeria extends Persistencia {
    var $grupo = null;
    var $name;
    var $photos=null;

    function getByGrupo($idGrupo){
        return $this->getRows(0,999,array(),array("grupo"=>"=".$idGrupo));
    }

    function getGaleriasRandomicas($qtd){
        $sql = "select * from ag_galeria where publish = 1 order by rand() limit 0,$qtd";
        return $this->getSQL($sql); 
    }

    function excluir(){
        foreach ($this->photos as $key => $value) {
            @$this->apagaImagem($value->name,$this->getFolder());
        }
        $this->delete($this->id);
    }
    function getSizePhoto(){

    }

    function salvaFoto($file,$description){

        if($file['name'] != ''){            
            $names = explode(".",$file['name']);
            $timer = date('YmdHis');
            $nome = $this->retornaNomeUnico($this->grupo->id."_foto.".$timer.'.'.$names[count($names)-1],$this->getFolder());
            $nomeThumb = $this->retornaNomeUnico($this->grupo->id."_fotoThumb.".$timer.'.'.$names[count($names)-1],$this->getFolder());
            $this->uploadArquivo($file,$nome,$this->getFolder());
            $this->uploadArquivo($file,$nomeThumb,$this->getFolder());
             
            $qtd = count($this->photos)+1;

                $x = floor($qtd/8);
                if($qtd == 1+intval($x*8)){
                    $width = 700;
                    $heigth = 466;
                    $type = 0;
                }else if($qtd == 4+intval($x*8)){
                    $width = 281;
                    $heigth = 386;
                    $type = 2;
                }else{
                    $type = 1;
                    $width = 281;
                    $heigth = 190;
                } 
                $foto = new GaleriaFoto();
            $foto->name = $nome;
            $foto->nameThumb = $nomeThumb;
            $foto->galeria = $this;
            $foto->type = $type;   
            $foto->description = $description;        
            
            $this->resizeImage($this->getFolder(),$nomeThumb,$width,$heigth);
            $foto->save();
        }
    }

    function getFolder(){
        return str_replace("admin/","",$this->URI)."img/galery/";
    }

    function excluirFoto($idFoto){
        $foto = new GaleriaFoto();
        if($foto->getById($idFoto)){
        if($foto->name!= null && $foto->name != '')
            $this->apagaImagem($foto->name,$this->getFolder());
        if($foto->nameThumb!= null && $foto->nameThumb != '')
            $this->apagaImagem($foto->nameThumb,$this->getFolder());
        $foto->delete($idFoto);
        }
    }
}