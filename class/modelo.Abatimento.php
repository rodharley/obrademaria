<?php
class Abatimento extends Persistencia{
	var $id = NULL;
	var $pagamento = NULL;
	var $valor;
	var $participante = NULL;
	
	function incluirAbatimento(){
		$idPagamento = $this->md5_decrypt($_REQUEST['idPagamento']);
		$oPag = new Pagamento();
		$oTipoP = new TipoPagamento();
		$om = new Moeda;
		$oPag->getById($idPagamento);
		$valor = $this->money($_REQUEST['valor'],"bta");
		$oPartic = new Participante();
		$oPartic->getById($_REQUEST['participante']);
		$idMoedaGrupo = $oPag->participante->grupo->moeda->id;
		if($idMoedaGrupo == $om->DOLLAR()){
			$this->valor = $oPag->CALCULA_DOLLAR($valor);
		}else{
			$this->valor = $oPag->CALCULA_REAL($valor);
		}	
		//validacao
		if($idMoedaGrupo == $om->DOLLAR()){
			$valorMaximo = $oPag->CALCULA_DOLLAR();
		}else{
			$valorMaximo = $oPag->CALCULA_REAL();
		}	
		
		$total = $this->totalAbatimentos($idPagamento);
	
		if(($oTipoP->CARTAO() == $oPag->tipo->id && $oPag->codAutorizacao == "")){
			$_SESSION['tupi.mensagem'] = 61;
		header("Location:participante.abatimentos.php?idPagamento=".$_REQUEST['idPagamento']);
		exit();
		}
	
		if(number_format($total+$this->valor,2,".","") > number_format($valorMaximo,2,".","")){
		$_SESSION['tupi.mensagem'] = 40;
		header("Location:participante.abatimentos.php?idPagamento=".$_REQUEST['idPagamento']);
		exit();
		}
				
			
		//configurando o objeto
		$this->pagamento = $oPag;
		$this->participante = $oPartic;
		$idAbatimento = $this->save();
		
		//FAZ A CONFERENCIA PARA MUDAR O STATUS DO PARTICIPANTE
		$oPartic->atualiza_status();
		$_SESSION['tupi.mensagem'] = 39;
	}
	
	function cancelarAbatimento(){
		$idPagamento = $this->md5_decrypt($_REQUEST['idPagamento']);
		$idAbatimento = $this->md5_decrypt($_REQUEST['idAbatimento']);
		$oPag = new Pagamento();
		$om = new Moeda;
		$oPartic = new Participante();
		$oPag->getById($idPagamento);
		//recupera o abatimento
		$this->getById($idAbatimento);
		$oPartic->getById($this->participante->id);
		$this->delete($idAbatimento);
		//FAZ A CONFERENCIA PARA MUDAR O STATUS DO PARTICIPANTE
		$oPartic->atualiza_status();
		$_SESSION['tupi.mensagem'] = 41;
	
	}
	
	
	function alterarAbatimento(){
		$idPagamento = $this->md5_decrypt($_REQUEST['idPagamento']);
		$oPag = new Pagamento();
		$om = new Moeda;
		$oTipoP = new TipoPagamento();
		$oPag->getById($idPagamento);
		$this->getById($_REQUEST['id']);		
		$valor = $this->money($_REQUEST['valor'],"bta");
		$oPartic = new Participante();
		$oPartic->getById($_REQUEST['participante']);
		$idMoedaGrupo = $oPag->participante->grupo->moeda->id;
		
		if($idMoedaGrupo == $om->DOLLAR()){
			$valorNovo = $oPag->CALCULA_DOLLAR($valor);
		}else{
			$valorNovo = $oPag->CALCULA_REAL($valor);
		}	
		
		//validacao
		if($idMoedaGrupo == $om->DOLLAR()){
			$valorMaximo = $oPag->CALCULA_DOLLAR();
		}else{
			$valorMaximo = $oPag->CALCULA_REAL();
		}		
		$total = $this->totalAbatimentos($idPagamento);
		$total = $total - $this->valor;
		
		if(($oTipoP->CARTAO() == $oPag->tipo->id && $oPag->codAutorizacao != "")){
			$_SESSION['tupi.mensagem'] = 61;
		header("Location:participante.abatimentos.php?idPagamento=".$_REQUEST['idPagamento']);
		exit();
		}
		
		
		if(number_format($total+$valorNovo,2,".","") > number_format($valorMaximo,2,".","")){
		$_SESSION['tupi.mensagem'] = 40;
		header("Location:participante.abatimentos.php?idPagamento=".$_REQUEST['idPagamento']);
		exit();
		}
		
		
				
				
		//configurando o objeto
		$this->valor = $valorNovo;
		$this->pagamento = $oPag;
		$this->participante = $oPartic;
		$idAbatimento = $this->save();
		
		//FAZ A CONFERENCIA PARA MUDAR O STATUS DO PARTICIPANTE
		$oPartic->atualiza_status();
		$_SESSION['tupi.mensagem'] = 42;
	}
	
	
	function totalAbatimentos($idPagamento){
	$sql = "select sum(valor) as total from ag_abatimento where idPagamento = ".$idPagamento;
	$rs = $this->DAO_ExecutarQuery($sql);
		$totalAbatimento = $this->DAO_Result($rs,"total",0);
		return $totalAbatimento;	
	}
	
	function getValorDollar(){
		$moeda = new Moeda();
		$cotReal = $this->pagamento->cotacaoReal == 0 ? 1 : $this->pagamento->cotacaoReal;
	if($this->participante->grupo->moeda->id == $moeda->DOLLAR())
		return $this->valor;
		else
		return $this->valor / $cotReal;
			
	}
	
	function getValorReal(){
		$moeda = new Moeda();
	if($this->participante->grupo->moeda->id == $moeda->REAL())
		return $this->valor;
	else
		return  $this->valor * $this->pagamento->cotacaoReal;
	
	}
}
?>