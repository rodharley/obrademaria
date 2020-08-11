<?php
class Galeria extends Persistencia {
    var $grupo = null;
    var $name;
    var $photos=null;

    function getByGrupo($idGrupo){
        return $this->getRows(0,999,array(),array("grupo"=>"=".$idGrupo));
    }

    function excluir(){
        foreach ($this->photos as $key => $value) {
            @$this->apagaImagem($value->name,$this->getFolder());
        }
        $this->delete($this->id);
    }

    function salvaFoto($file){

        if($file['name'] != ''){            
            $names = explode(".",$file['name']);
            $nome = $this->retornaNomeUnico($this->grupo->id."_foto.".$names[count($names)-1],$this->getFolder());
            $this->uploadArquivo($file,$nome,$this->getFolder());
            $foto = new GaleriaFoto();
            $foto->name = $nome;
            $foto->galeria = $this;            
            $picture = WideImage::load($this->getFolder().$nome);
            $resize = $picture->resize(500, null, 'fill');
            $resize->saveToFile($this->getFolder().$nome);
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
        $foto->delete($idFoto);
        }
    }
}