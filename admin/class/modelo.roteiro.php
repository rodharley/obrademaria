<?php 
class Roteiro extends Persistencia {
    var $cardImage;
    var $cardTitle;
    var $unlikes;
    var $cardDescription;
    var $continent;
    var $likes;
    var $grupo =null;
    var $description;
    var $title;
    var $image;
    var $countDown;
    var $itineraryes = null;
    var $videos =null;
    var $reviews = null;
    var $photos =null;
    var $publish;


    function setCountDown($value){
        if($value == 1){
            $sql = "update ag_roteiro set countDown = 0";
            $this->DAO_ExecutarQuery($sql);
        }
        $this->countDown = $value;        
    }

    function getFolder(){
        return str_replace("admin/","",$this->URI)."img/packages/";
    }
    function excluir(){
        if($this->cardImage!= null && $this->cardImage != '')
            $this->apagaImagem($this->cardImage,$this->getFolder());
        if($this->image!= null && $this->image != '')    
            $this->apagaImagem($this->image,$this->getFolder());
        $obFoto = new Foto();
        $rsfotos = $obFoto->getByRoteiro($this->id);
        foreach ($rsfotos as $key => $value) {
            $this->apagaImagem($value->name,$obFoto->getFolder());
        }
        $this->delete($this->id);
    }
    function salvaCardImage($file){
        //redimencionar
             


        if($file['name'] != ''){            

            if($this->cardImage!= null && $this->cardImage != '')
                $this->apagaImagem($this->cardImage,$this->getFolder());

            $names = explode(".",$file['name']);
            $nome = $this->grupo->id."_cardimage.".$names[count($names)-1];
            $this->uploadArquivo($file,$nome,$this->getFolder());
            $this->cardImage = $nome;

            $picture = WideImage::load($this->getFolder().$nome);
            $resize = $picture->resize(360,310, 'fill');
            $resize->saveToFile($this->getFolder().$nome);
            
        }
    }

    function salvaImage($file){
        if($file['name'] != ''){            
            if($this->image!= null && $this->image != '')
                $this->apagaImagem($this->image,$this->getFolder());
            $names = explode(".",$file['name']);
            $nome = $this->grupo->id."_image.".$names[count($names)-1];
            $this->uploadArquivo($file,$nome,$this->getFolder());
            $this->image = $nome;
            $picture = WideImage::load($this->getFolder().$nome);
            $resize = $picture->resize(1680,550, 'fill');
            $resize->saveToFile($this->getFolder().$nome);
        }
    }

    function getCountDown (){        
        return $this->getRow(array("countDown"=>"=1"));
    }

    function getContinentesDispoiveis (){
        $sql = "select * from ag_roteiro where publish = 1 group by continent";
        return $this->getSQL($sql);
    }

    function getRoteirosRandomicos($qtd){
        $sql = "select * from ag_roteiro where publish = 1 order by rand() limit 0,$qtd";
        return $this->getSQL($sql); 
    }
    function getByContinent($continent,$count){
        return $this->getRows(0,$count,array("likes"=>"desc"),array("publish"=>"=1","continent"=>"='".$continent."'"));
    }

    function pesquisar($termo='',$ano='', $local='',$contador=false,$init=0,$end=99999){
        if($contador){
            $sql = "select count(r.id) as total from ag_roteiro r inner join ag_grupo g on g.id = r.grupo ";
        }else{
            $sql = "select r.* from ag_roteiro r inner join ag_grupo g on g.id = r.grupo ";
        }
        $sql .= "where r.publish = 1 ";

        if($termo != ''){
            $sql .= " and (r.title like '%$termo%' or r.description like '%$termo%' or r.card_title like '%$termo%' or r.card_description like '%$termo%') ";
        }
        if($ano != ''){
            $sql .= " and g.ano = $ano";
        }
        if($local != ''){
            $sql .= " and g.local = '$local'";
        }
        $sql .= " order by g.ano asc";

        $sql .= " limit $init, $end";

        if($contador){
            $rs = $this->DAO_ExecutarQuery($sql);
            return $this->DAO_Result($rs,"total",0);
        }else{
            return $this->getSQL($sql);
        }

        
    
    }

    function getNumberStars(){
        $result = $this->likes - $this->unlikes;
        
        if($result < -9){
            return 1;
        }
        if($result < -4){
            return 2;
        }
        if($result < 1){
            return 3;
        }
        if($result < 6){
            return 4;
        }
        return 5;
        
    }

    function getStarsHtml(){
        $n = $this->getNumberStars();
        $html = '<i class="fa ';
        $html .= $n < 1 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 2 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 3 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 4 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        $html .= '<i class="fa ';
        $html .= $n < 5 ? 'fa-star-o' : 'fa-star';
        $html .= '"></i>';
        return $html;
    }

    public function getRoteirosSemSlider(){
		$sql = "select distinct g.* from ag_roteiro g left outer join ag_slide p on g.id = p.roteiro where p.id is null";
			return $this->getSQL($sql);
		}
}