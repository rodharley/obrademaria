<?php
class Review extends Persistencia {
    var $roteiro = null;
    var $photo;
    var $review;
    var $date;
    var $name;
    var $local;

    function getFolder(){
        return str_replace("admin/","",$this->URI)."img/reviews/";
    }

    function getRandomicos($qtd){
        $sql = "select * from ag_review order by rand() limit 0,$qtd";
        return $this->getSQL($sql);
    }
    function getCommentsWithOutRoteiro(){
        $sql = "select * from ag_review where roteiro is null";
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
            $this->date = $this->convdata($data,"ntm")." ".date("H:m:s");
            $this->review = $coment;
            $this->roteiro = $roteiro;
            $this->local = $roteiro->cardTitle;

            $picture = WideImage::load($this->getFolder().$nomefoto);
            if($picture->getHeight() > $picture->getWidth()){
            $resize = $picture->resize(300,null, 'fill');
            $crop = $resize->crop(0,'25%',300,300);
            }else{
                $resize = $picture->resize(null,300, 'fill');
            $crop = $resize->crop('25%',0,300,300);
            }
            $crop->saveToFile($this->getFolder().$nomefoto);

            $this->save();
        }
    }

    function salvaReviewWithOutRoteiro($file,$name,$data,$coment,$local){

            $this->name = $name;            
            $this->date = $this->convdata($data,"ntm")." ".date("H:m:s");
            $this->review = $coment;
            $this->roteiro = null;
            $this->local = $local;


        if($file['name'] != ''){      
            
            if($this->photo!= null && $this->photo != '')
                $this->apagaImagem($this->photo,$this->getFolder());

            $names = explode(".",$file['name']);
            $nomefoto = $this->retornaNomeUnico("cliente.".$names[count($names)-1],$this->getFolder());
            $this->uploadArquivo($file,$nomefoto,$this->getFolder());
            $this->photo = $nomefoto;
            $picture = WideImage::load($this->getFolder().$nomefoto);
            if($picture->getHeight() > $picture->getWidth()){
            $resize = $picture->resize(300,null, 'fill');
            $crop = $resize->crop(0,'25%',300,300);
            }else{
                $resize = $picture->resize(null,300, 'fill');
            $crop = $resize->crop('25%',0,300,300);
            }
            $crop->saveToFile($this->getFolder().$nomefoto);

            
        }
        $this->save();
    }
}