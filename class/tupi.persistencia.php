<?php

class Persistencia extends Biblioteca {
    public $id = NULL;
	public $mensagem;
	public $conn;   

    public function __construct($id = NULL){
        $this->id = $id;
        $this->conn = Configuracao::init();              
    }

    function DAO_ExecutarQuery($sql){

        try {
        $result = $this->conn->query($sql);
            if (!$result){
                throw new Exception('Erro de SQL:<BR>SQL:'.$sql."<BR>ERRO:".$this->conn->connection->error);
            }
    
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    
        return $result;
    
        }


        function DAO_ExecutarDelete($sql){

            try {
             
            $result = $this->conn->query($sql);
                if (!$result){
                   return false;
                }
        
            }catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        
            return true;
        
            }
        
    
                        
    
        function DAO_NumeroLinhas($rs){
    
        return mysqli_num_rows($rs);
    
        }
    
        function DAO_GerarArray($rs){
    
        return mysqli_fetch_array($rs,MYSQLI_ASSOC);
    
        }
    
        function DAO_Result($rs,$campo,$linha){
            $rs->data_seek($linha);
            $datarow = $rs->fetch_array(MYSQLI_ASSOC);
            return $datarow[$campo]; 
        //return mysql_result($rs,$linha,$campo);
    
        }
    
        function DAO_recuperaUltimoIdInserido(){
    
            return $this->conn->connection->insert_id;
        
            }
        
    
    
    
            function testando(){

                $sql = "select idUsuario from usuario where idPerfil = 1";
            
                $rs = $this->DAO_ExecutarQuery($sql);
            
                return $this->DAO_NumeroLinhas($rs);
            
                }
            
    
    
    

        /* recupera uma lista de todos os nomes dos campos na tabela ligada a classe*/
	function getFieldsList($class){
		$string = "";
		foreach ($class->children() as $atributo){
				if($atributo['tbname'] != "")
				$string .= $atributo['tbname'].",";
			}

	return substr($string,0,strlen($string)-1);
	}


    
        /* recupera o nome do campo no banco de dados atraves no nome do atributo */
        function getField($class,$propriedade){
            foreach ($class->children() as $atributo){
                    if($atributo[0] == $propriedade)
                    return $atributo['tbname'];
                }
            }
  


    /* seta o bean com lazy */
        function setBean($arrayValues,$class){
            foreach ($class->children() as $atributo){
    
            $field = $atributo["tbname"];
            $tbIdent = $class["tbid"];
            $atrb = $atributo[0];
    
    
            if($atributo["type"] == "set" && $atributo['lazy'] == "true"){
                $clfk = substr($atributo['clfk'],0,strlen($atributo['clfk']));
                $strClass = substr($atributo['clrelation'],0,strlen($atributo['clrelation']));
                $clorder = substr($atributo['clorder'],0,strlen($atributo['clorder']));
                $valueIdFk = $arrayValues["$tbIdent"];
    
                $obj = new $strClass;
    
                $arrayOrder = array(0 => $clorder);
                $arraywhere = array( $clfk => "=".$valueIdFk);
                $xml2 = simplexml_load_file($this->URI."xml/cfg.xml");
                foreach ($xml2->children() as $elemento2){
                if($elemento2['name'] == get_class($obj)){
                    $arrayrs =$obj->getClassRows($elemento2,$arrayOrder,$arraywhere);
                    $this->$atrb = $arrayrs;
                }
                }
    
            }else{
                if($atributo["type"] == "fk" && $atributo['lazy'] == "true" && strlen($arrayValues["$field"]) > 0 ){
                    $strClass = substr($atributo['clrelation'],0,strlen($atributo['clrelation']));
                    $obj = new $strClass;
                    $obj->getById($arrayValues["$field"]);
                    $this->$atrb = $obj;
                    unset($obj);
                }else{
                    $this->$atrb = $arrayValues["$field"];
                }
    
            }
    
            }
        return true;
        }
    
    
    
        /*seta o bean sem lazy */
        function setBeanUnlazy($arrayValues,$class){
            foreach ($class->children() as $atributo){
                $atrb =	$atributo[0];
            if($atributo["type"] != "set"){
            $field = $atributo["tbname"];
            $tbIdent = $class["tbid"];
            $this->$atrb = $arrayValues["$field"];
            }
            }
        return true;
        }
    
    function xmlContruct($i,$objeto){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($objeto)){
                    return $this->xmlObject($elemento,$i,$objeto);
                }
            }
    }
    
    /*xml do objeto completo*/
    function xmlObject($elemento,$i,$objeto){
                $str = "";
                if($i < 2){
                $str = "<".$elemento['name'].">".chr(13);
                foreach ($elemento->children() as $item){				
                        if($item["type"] == "fk"){
                            $str .= $this->xmlContruct($i+1,$objeto->$item[0]);
                        }else if($item["type"] == "set"){
                            $str .= "<".$item[0].">".chr(13);
                            foreach ($objeto->$item[0] as $objClasse){
                                $str .= $this->xmlContruct($i+1,$objClasse);
                            }	
                            $str .= "</".$item[0].">".chr(13);				
                        }else{
                        $str .= str_pad("<".$item[0].">".$objeto->$item[0]."</".$item[0].">",strlen("<".$item[0].">".$objeto->$item[0]."</".$item[0].">")+($i*4)," ",STR_PAD_LEFT).chr(13);
                        }
                
                }
                $str .= "</".$elemento['name'].">".chr(13);
                }
        return $str;
    }
    
        /*recupera um objeto da classe pelo id com lazy at� segundo nivel*/
        function getById($id){
            if(strlen($id) > 0){	
            $xml = simplexml_load_file($this->URI."/xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($this)){
                    $sql = "select ".$this->getFieldsList($elemento)." from ".$elemento['tbname']." where ".$elemento['tbid']." = $id";
                    $rs =  $this->DAO_ExecutarQuery($sql);
                    if($this->DAO_NumeroLinhas($rs) > 0){
                        $arrayItem = $this->DAO_GerarArray($rs);
                        $this->setBean($arrayItem,$elemento);
                        return true;
                    }else{
                        return false;
                    }
                }
            }
            
            }else{
                return false;
            }
        }
    
    



        function getRows($init=0,$limit=999, $order = array(), $filtro = array()){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($this)){
                    //configura a ordenacao
                    $ordenacao = "";
                    if(count($order) > 0){
                    $ordenacao = " order by ";
                    foreach($order as $key => $value){
                        $campo = $this->getField($elemento,$key);
                        $ordenacao .= $campo." ".$value.",";
                        }
    
                    }
                    $ordenacao = substr($ordenacao,0,strlen($ordenacao)-1);
                    //configura as clausulas where
                    $where = "";
                    if(count($filtro) > 0){
                        $where = "where 1 = 1 ";
                        foreach($filtro as $key => $value){						
                        $campo = $this->getField($elemento,$key);					
                        $where .= " and $campo $value	";
                        }
                    }
                    
                    $sql = "select ".$this->getFieldsList($elemento)." from ".$elemento['tbname']." $where $ordenacao limit $init, $limit";

                    $rs =  $this->DAO_ExecutarQuery($sql);
                    $arrayItens = array();
                    if($this->DAO_NumeroLinhas($rs) > 0){
                        while($arrayItem = $this->DAO_GerarArray($rs)){
                        $object = new $this;
                        $object->setBean($arrayItem,$elemento);
                        array_push($arrayItens,$object);
                        }
                    }
                    return $arrayItens;
                }
    
            }
    
        }
    
    
    
    //metodo que recupera 1 registro por filtro e set o objeto
        function getRow($filtro = array()){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($this)){
                    
                    //configura as clausulas where
                    $where = "";
                    if(count($filtro) > 0){
                        $where = "where 1 = 1 ";
                        foreach($filtro as $key => $value){						
                        $campo = $this->getField($elemento,$key);					
                        $where .= " and $campo $value	";
                        }
                    }
    
                    $sql = "select ".$this->getFieldsList($elemento)." from ".$elemento['tbname']." $where limit 0, 1";
                    
                    $rs =  $this->DAO_ExecutarQuery($sql);
    
                    if($this->DAO_NumeroLinhas($rs) > 0){
                        $arrayItem = $this->DAO_GerarArray($rs);
                        $this->setBean($arrayItem,$elemento);	
                        return true;
                    }
                    return false;
                }
    
            }
    
        }
    
    
    
    /*m�todo que recupera a quantidade de objetos obejtos da classe */
        function getNumRows($filtro = array()){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($this)){
                        
                    //configura as clausulas where
                    $where = "";
                    if(count($filtro) > 0){
                        $where = "where 1 = 1 ";
                        foreach($filtro as $key => $value){						
                        $campo = $this->getField($elemento,$key);					
                        $where .= " and $campo $value	";
                        }
                    }
                    
                    $sql = "select count(*) as total from ".$elemento['tbname']." $where";
                    $rs =  $this->DAO_ExecutarQuery($sql);
                    $arrayItem = $this->DAO_GerarArray($rs);
                    return $arrayItem['total'];
                }
    
            }
    
        }
    
    
    
    
        /* m�todo que recupera uma array(lista) de objetos atraves de uma consulta sql*/
        function getSQL($sql){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($this)){
                    $rs =  $this->DAO_ExecutarQuery($sql);
                    $arrayItens = array();
                    if($this->DAO_NumeroLinhas($rs) > 0){
                        while($arrayItem = $this->DAO_GerarArray($rs)){						
                            $object = new $this;
                            $object->setBean($arrayItem,$elemento);
                            array_push($arrayItens,$object);
                        }
                    }
                }
            }
            return $arrayItens;
        }
    
    
    
        /*m�todo que recupera os elementos do banco sem pesquisar por filhos */
    
        function getClassRows($elemento, $order = array(), $filtro = array()){
                    //configura a ordenacao
                    if(count($order) > 0){
                    $ordenacao = " order by ";
                    foreach($order as $key => $value){
                        $campo = $this->getField($elemento,$value);
                        $ordenacao .= $campo.",";
                        }
    
                    }
                    $ordenacao = substr($ordenacao,0,strlen($ordenacao)-1);
                    //configura as clausulas where
                    if(count($filtro) > 0){
                        $where = "where 1 = 1 ";
                        foreach($filtro as $key => $value){
                            
                            $campo = $this->getField($elemento,$key);
                            $where .= " and $campo $value	";
                        }
                    }
    
                    $sql = "select ".$this->getFieldsList($elemento)." from ".$elemento['tbname']." $where $ordenacao ";
                
                    $rs =  $this->DAO_ExecutarQuery($sql);
                    $arrayItens = array();
                    if($this->DAO_NumeroLinhas($rs) > 0){
                        while($arrayItem = $this->DAO_GerarArray($rs)){
                        $object = new $this;
                        $object->setBeanUnlazy($arrayItem,$elemento);
                        array_push($arrayItens,$object);
                        }
                    }
                    return $arrayItens;
    
    
        }
    
    
        function save(){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($this)){
                    //recupera o nome do atributo id da class
                    $id = $this->getIdElementXML($elemento);
                        if($this->$id != NULL){
                        //update
                        $sql = "update ".$elemento['tbname']." set ";
                        foreach($elemento->children() as $atributo){
                            $atrb = $atributo[0];
                            if($atributo['type'] != "id" && $atributo['type'] != "set"){
                                $sql .= $atributo['tbname']." = ";							
                                if($atributo['type'] == "fk" && $atributo['lazy'] == "true"){
                                    $elementFk = $this->getClassElementXML($atributo['clrelation']);
                                    $idfk = $this->getIdElementXML($elementFk);
                                    if($this->$atrb != NULL)
                                    $sql .= $this->$atrb->$idfk;
                                    else
                                    $sql .= 'NULL';
                                    $sql .= ", ";
                                }else{
                                    if($atributo['type'] == "txt"){
                                        $sql .= "'".$this->conn->real_escape_string($this->$atrb)."'";
                                    }elseif ($atributo['type'] == "dat") {
                                        $sql .= strlen($this->$atrb) > 0 ? $this->$atrb != '0000-00-00' ? "'".$this->conn->real_escape_string($this->$atrb)."'" : "NULL" : "NULL";
                                    }else{	
                                        $sql .= strlen($this->$atrb) > 0 ? $this->$atrb : "NULL";
                                    }
                                    $sql .= ", ";
    
                                }
                            }
                        }
                        $sql = substr($sql,0,strlen($sql)-2);
                        $sql .=  " where ".$elemento['tbid']." = ".$this->$id;
                        
                        $this->DAO_ExecutarQuery($sql);
                    return $this->id;
                    }else{
                        //insert
                        $sql = "insert into ".$elemento['tbname']." (";
                        $campos = "";
                        foreach($elemento->children() as $atributo){
                            $atrb = $atributo[0];
                            if($atributo['type'] != "id" && $atributo['type'] != "set" && $atributo['type'] != "def"){
                                $sql .= $atributo['tbname'].", ";
                                if($atributo['type'] == "fk" && $elemento['lazy'] == "true"){
                                    $elementFk = $this->getClassElementXML($atributo['clrelation']);
                                    $idfk = $this->getIdElementXML($elementFk);
                                    if($this->$atrb != NULL)
                                    $campos .= $this->$atrb->$idfk;
                                    else
                                    $campos .= 'NULL';
                                    $campos .= ", ";
                                }else{
                                    if($atributo['type'] == "txt"){
                                        $campos .= "'".$this->conn->real_escape_string($this->$atrb)."'";
                                    }elseif ($atributo['type'] == "dat") {	
                                        $campos .= strlen($this->$atrb) > 0 ? "'".$this->conn->real_escape_string($this->$atrb)."'" : "NULL";
                                    }else{
                                        $campos .= strlen($this->$atrb) > 0 ? $this->$atrb : "NULL";
                                    }
                                    $campos .= ", ";
                                }
                            }

                        }
                        $sql = substr($sql,0,strlen($sql)-2);
                        $campos = substr($campos,0,strlen($campos)-2);
                        $sql .= ") values(".$campos;
    
                        $sql .=  ")";
                    $this->DAO_ExecutarQuery($sql);
                    $this->id = $this->DAO_recuperaUltimoIdInserido();
                    return $this->id;
                    }
                }
            }
            
        }
    
    
    /*recupera um objeto da classe pelo id com lazy at� segundo nivel*/
        function delete($id){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                if($elemento['name'] == get_class($this)){
                    $sql = "delete from ".$elemento['tbname']." where ".$elemento['tbid']." = $id";
                    $rs =  $this->DAO_ExecutarQuery($sql);				
                }
            }
            return true;
        }
    
        function getIdElementXML($class){
            foreach($class->children() as $atributo){
                if($atributo['type'] == "id")
                    return $atributo[0];
                }
        }
    
        function getClassElementXML($nameClass){
            $xml = simplexml_load_file($this->URI."xml/cfg.xml");
            foreach ($xml->children() as $elemento){
                $name = substr($elemento['name'],0,strlen($elemento['name']));
                if($name == $nameClass){
                    return $elemento;
                }
            }
        }

        function objectToArray($obj){
            
            $jsonarray = array();
            foreach ($obj as $key => $value) {
               if (!is_object($value) && !is_array($value)){
                $jsonarray[$key] = utf8_encode($value);
               }else{
                   $jsonarray[$key] = $this->objectToArray($value);
               }                        
            } 
            
            unset($jsonarray['conn']);        
            unset($jsonarray['HASH_URL']);
            unset($jsonarray['PAGINACAO']);
                  
            return $jsonarray;
        }
    
}
?>