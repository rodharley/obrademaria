<?php
class Mensagem extends Persistencia {
	var $id = NULL;
	var $mensagem;
	var $tipo;
	
	function getMensagem($id){
	$xml = simplexml_load_file($this->URI."xml/mensagem.xml");
		foreach ($xml->children() as $elemento){			
			
			if($elemento['id'] == $id){			
				$this->id = $id;
				$this->tipo = $elemento['type'];
				$this->mensagem = $elemento[0];
			}
		}
	}
	
}
?>