<?php
class Review extends Persistencia {
    var $roteiro = null;
    var $photo;
    var $review;
    var $date;
    var $name;

    function getFolder(){
        return str_replace("admin/","",$this->URI)."img/reviews/";
    }

    function getRandomicos($qtd){
        $sql = "select * from ag_review order by rand() limit 0,$qtd";
        return $this->getSQL($sql);
    }
    function excluir(){
        if($this->photo!= null && $this->photo != '')
            $this->apagaImagem($this->photo,$this->getFolder());
        $this->delete($this->id);
    }

    function salvaReview($file,$name,$data,$coment,$roteiro){

        if($file['name'] != ''){            
            $names = explode(".",$file['name']);
            $nomefoto = $this->retornaNomeUnico($roteiro->id."_cliente.".$names[count($names)-1],$this->getFolder());
            $this->uploadArquivo($file,$nomefoto,$this->getFolder());
            $this->name = $name;
            $this->photo = $nomefoto;
            $this->date = $this->convdata($data,"ntm");
            $this->review = $coment;
            $this->roteiro = $roteiro;

            $picture = WideImage::load($this->getFolder().$nomefoto);
            $resize = $picture->resize(450, null, 'fill');
            $resize->saveToFile($this->getFolder().$nomefoto);
            $this->save();
        }
    }
}