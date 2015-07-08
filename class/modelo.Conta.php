<?php
class Conta extends Persistencia{
	var $id = NULL;
	var $descricao;
	var $dataPagamento;
	var $valorPagamento;
	var $tipo = NULL;
	var $parcelas;
	var $ativo;
public function recuperaTotal($descricao,$mes,$ano){
		$sql = "select count(id) as total from ag_conta where ativo = 1 ";
		
		if ($descricao != ""){

			$sql .= " and descricao like '%" . $descricao . "%' ";
			}
		if ($mes != ""){
			$sql .= " and  SUBSTR(dataPagamento,6,2) = '" . $mes . "' ";
			}
		if ($ano != ""){
			$sql .= " and  SUBSTR(dataPagamento,1,4) = '" . $ano . "' ";
			}
		$rs = $this->DAO_ExecutarQuery($sql);	
		return $this->DAO_Result($rs,"total",0);
}	

public function Pesquisa($inicio,$fim,$descricao,$mes,$ano){
		$sql = "select * from ag_conta where ativo = 1 ";
		
		if ($descricao != "")
			$sql .= " and descricao like '%" . $descricao . "%' ";
			
		if ($mes != "")
			$sql .= " and  SUBSTR(dataPagamento,6,2) = '" . $mes . "' ";
			
		if ($ano != "")
			$sql .= " and  SUBSTR(dataPagamento,1,4) = '" . $ano . "' ";
		
		$sql .= "order by dataPagamento desc limit $inicio, $fim";
		return $this->getSQL($sql);
}

public function incluir(){
		$this->descricao = $_POST['descricao'];
		$this->dataPagamento = $this->convdata($_POST['dataPagamento'],"ntm");
		$this->valorPagamento = $this->money($_POST['valorPagamento']  == '' ? 0 : $_POST['valorPagamento'],"bta");
		$otipoConta = new TipoConta();
		$otipoConta->id = $_POST['tipo'];
		$this->tipo = $otipoConta;
		$this->parcelas = $_POST['parcela'];
		$this->ativo = 1;
		$newid = $this->save();
		
		switch ($_POST['tipo']) {
		case $otipoConta->UNICA():
		$ocr = new ContaRealizado();
		$ocr->parcela = 1;
		$ocr->dataPagamento = $this->dataPagamento;
		$ocr->valorPagamento =  $this->valorPagamento;
		$ocr->conta = $this;
		$ocr->save();
		break;
		case $otipoConta->PERIODICA():
		for($i=0;$i < 360 ; $i++){
		$ocr = new ContaRealizado();
		$tsdata = strtotime($this->dataPagamento);
		$ocr->dataPagamento = date("Y-m-d", mktime(0,0,0,date("m",$tsdata)+$i,date("d",$tsdata),date("Y",$tsdata )));
		$ocr->valorPagamento =  $this->valorPagamento;
		$ocr->conta = $this;
		$ocr->parcela = $i+1;
		$ocr->save();
		}
		break;
		case $otipoConta->PERIODO():
		
		for($i=0;$i < $_POST['parcela'] ; $i++){
		$ocr = new ContaRealizado();
		$tsdata = strtotime($this->dataPagamento);
		$ocr->dataPagamento = date("Y-m-d", mktime(0,0,0,date("m",$tsdata)+$i,date("d",$tsdata),date("Y",$tsdata )));
		$ocr->valorPagamento =  $this->valorPagamento;
		$ocr->conta = $this;
		$ocr->parcela = $i+1;
		$ocr->save();
		}
		break;
		}		
		
		$_SESSION['tupi.mensagem'] = 43;	
		return $newid;
	}
	
	public function alterar(){
		$this->getById($_REQUEST['id']);
		$this->descricao = $_POST['descricao'];
		$this->dataPagamento = $this->convdata($_POST['dataPagamento'],"ntm");
		$this->valorPagamento = $this->money($_POST['valorPagamento']  == '' ? 0 : $_POST['valorPagamento'],"bta");
		$otipoConta = new TipoConta();
		$otipoConta->id = $_POST['tipo'];
		$this->tipo = $otipoConta;
		$this->parcelas = $_POST['parcela'];
		$this->ativo = 1;
		$newid = $this->save();
		$this->apagaRealizado();
		
		switch ($_POST['tipo']) {
		case $otipoConta->UNICA():
		$ocr = new ContaRealizado();		
		$ocr->dataPagamento = $this->dataPagamento;
		$ocr->valorPagamento =  $this->valorPagamento;
		$ocr->parcela = 1;
		$ocr->conta = $this;
		$ocr->save();
		break;
		case $otipoConta->PERIODICA():
		for($i=0;$i < 360 ; $i++){
		$ocr = new ContaRealizado();
		$tsdata = strtotime($this->dataPagamento);
		$ocr->dataPagamento = date("Y-m-d", mktime(0,0,0,date("m",$tsdata)+$i,date("d",$tsdata),date("Y",$tsdata )));
		$ocr->valorPagamento =  $this->valorPagamento;
		$ocr->conta = $this;
		$ocr->parcela = $i+1;
		$ocr->save();
		}
		break;
		case $otipoConta->PERIODO():
		
		for($i=0;$i < $_POST['parcela'] ; $i++){
		$ocr = new ContaRealizado();
		$tsdata = strtotime($this->dataPagamento);
		$ocr->dataPagamento = date("Y-m-d", mktime(0,0,0,date("m",$tsdata)+$i,date("d",$tsdata),date("Y",$tsdata )));
		$ocr->valorPagamento =  $this->valorPagamento;
		$ocr->conta = $this;
		$ocr->parcela = $i+1;
		$ocr->save();
		}
		break;
		}		
		
		$_SESSION['tupi.mensagem'] = 44;	
		return $newid;
	}
	
	public function excluir(){
		$this->getById($this->md5_Decrypt($_REQUEST['idConta']));
		$otipoConta = new TipoConta();
		if($otipoConta->UNICA() == $this->tipo->id){
		$this->apagaRealizado();
		$this->delete($this->id);
		} else{
		$this->apagaRealizadoaVencer();
		$this->ativo = 0;
		$this->save();
		}
				
		$_SESSION['tupi.mensagem'] = 45;	
	}
	
	
	public function apagaRealizadoaVencer(){
		$sql = "delete from ag_contarealizado where dataPagamento >= '".date("Y-m-d")."' and idConta = ".$this->id;
		$this->DAO_ExecutarQuery($sql);
	}
	public function apagaRealizado(){
		$sql = "delete from ag_contarealizado where idConta = ".$this->id;
		$this->DAO_ExecutarQuery($sql);
	}
}
?>