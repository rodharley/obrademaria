<?php
class Persistencia extends Biblioteca{

	function abrirConexao()
	{

		$varcon = @mysql_connect($this->mysql_host, $this->mysql_usuario, $this->mysql_password) or die("Erro de <b>Conexo</b><br><font color='red'>".mysql_error()."</font>");

		if (mysql_select_db($this->mysql_database,$varcon) or die("Erro de conexo <b>Database</b><br><font color='red'>".mysql_error()."</font>"))

			return(true);

		else

			return(false);

	}



	function fecharConexao($varcon='')

	{

		if(mysql_close($varcon))

			return(true);

		else

			return(false);

	}

	function DAO_ExecutarQuery($sql){

	try {
	$result = mysql_query($sql);
		if (!$result){
			throw new Exception('Erro de SQL');
		}

	}catch (Exception $e) {
		//$insert = "insert into tblogErros(dataErro,QuerySQL,erro,arquivo,idUsuario) values('".date("Y-m-d")."','".mysql_escape_string($sql)."','".mysql_escape_string($e->getTraceAsString().mysql_error())."','".$_SERVER['REQUEST_URI']."',".$_SESSION['fat_idUsuario'].")";
		//$result = mysql_query($insert);
		echo $e->getTraceAsString()."<br/>".mysql_error()."<br/>".$sql;
		exit();
	}

	return $result;

	}



	function DAO_NumeroLinhas($rs){

	return mysql_num_rows($rs);

	}

	function DAO_GerarArray($rs){

	return mysql_fetch_array($rs);

	}

	function DAO_Result($rs,$campo,$linha){

	return mysql_result($rs,$linha,$campo);

	}

	function DAO_recuperaUltimoIdInserido(){

	return mysql_insert_id();

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
				$this->$atributo[0] = $arrayrs;
			}
			}

		}else{
			if($atributo["type"] == "fk" && $atributo['lazy'] == "true" && strlen($arrayValues["$field"]) > 0 ){
				$strClass = substr($atributo['clrelation'],0,strlen($atributo['clrelation']));
				$obj = new $strClass;
				$obj->getById($arrayValues["$field"]);
				$this->$atributo[0] = $obj;
				unset($obj);
			}else{
				$this->$atributo[0] = $arrayValues["$field"];
			}

		}

		}
	return true;
	}



	/*seta o bean sem lazy */
	function setBeanUnlazy($arrayValues,$class){
		foreach ($class->children() as $atributo){
		if($atributo["type"] != "set"){
		$field = $atributo["tbname"];
		$tbIdent = $class["tbid"];
		$this->$atributo[0] = $arrayValues["$field"];
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
		$xml = simplexml_load_file($this->URI."xml/cfg.xml");
		foreach ($xml->children() as $elemento){
			if($elemento['name'] == get_class($this)){
				$sql = "select ".$this->getFieldsList($elemento)." from ".$elemento['tbname']." where ".$elemento['tbid']." = $id";

				$rs =  $this->DAO_ExecutarQuery($sql);
				if($this->DAO_NumeroLinhas($rs) > 0){
					$arrayItem = $this->DAO_GerarArray($rs);
					$this->setBean($arrayItem,$elemento);
				}
			}
		}
		return true;
	}


	/*m�todo que recupera a lista de obejtos da classe */
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
						if($atributo['type'] != "id" && $atributo['type'] != "set"){
							$sql .= $atributo['tbname']." = ";							
							if($atributo['type'] == "fk" && $atributo['lazy'] == "true"){
								$elementFk = $this->getClassElementXML($atributo['clrelation']);
								$idfk = $this->getIdElementXML($elementFk);
								if($this->$atributo[0] != NULL)
								$sql .= $this->$atributo[0]->$idfk;
								else
								$sql .= 'NULL';
								$sql .= ", ";
							}else{
								if($atributo['type'] == "txt" || $atributo['type'] == "dat"){
									$sql .= "'";
								$sql .= is_object($this->$atributo[0]) ? $this->$atributo[0]->id : $this->$atributo[0];
								$sql .= "'";
								}else{
										$sql .= is_object($this->$atributo[0]) ? $this->$atributo[0]->id : strlen($this->$atribut[0])>0 ? $this->$atributo[0]:'NULL';
										
									
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
						if($atributo['type'] != "id" && $atributo['type'] != "set"){
							$sql .= $atributo['tbname'].", ";
							if($atributo['type'] == "fk" && $atributo['lazy'] == "true"){
								$elementFk = $this->getClassElementXML($atributo['clrelation']);
								$idfk = $this->getIdElementXML($elementFk);
								if($this->$atributo[0] != NULL)
								$campos .= $this->$atributo[0]->$idfk;
								else
								$campos .= 'NULL';
								$campos .= ", ";
							}else{
								if($atributo['type'] == "txt" || $atributo['type'] == "dat"){
									$campos .= "'";
									$campos .= is_object($this->$atributo[0]) ? $this->$atributo[0]->id : $this->$atributo[0];
									$campos .= "'";
								}else{
									
								$campos .= is_object($this->$atributo[0]) ? $this->$atributo[0]->id : strlen($this->$atribut[0])>0 ? $this->$atributo[0]:'NULL';
										
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
}
?>