<?php
class Slide extends Persistencia {
    var $image;
    var $title;
    var $subTitle;
    var $description;
    var $roteiro =null;
    var $buttomText;
    var $publish;
    function getFolder(){
        return str_replace("admin/","",$this->URI)."img/slider/";
    }

    function excluir(){
        if($this->image!= null && $this->image != '')
            $this->apagaImagem($this->image,$this->getFolder());
        $this->delete($this->id);
    }
    function salvaImage($file){
        
        if($file['name'] != ''){            
            $names = explode(".",$file['name']);
            if($this->image!= null && $this->image != '')
                $this->apagaImagem($this->image,$this->getFolder());
            $nome = $this->roteiro->id."_slide.".$names[count($names)-1];
            $this->uploadArquivo($file,$nome,$this->getFolder());
            $this->image = $nome;
            $picture = WideImage::load($this->getFolder().$nome);
            $resize = $picture->resize(1680,990, 'fill');
            $resize->saveToFile($this->getFolder().$nome);
        }
    }
    
}