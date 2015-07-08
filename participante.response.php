<?php 
include("tupi.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");


//classes
$oParticipante = new Participante();
$oPagamento = new Pagamento();
$oAbatimento = new Abatimento();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oParticipante->incluir();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	if($_REQUEST['acao'] == "IncluirPorId"){
		$oParticipante->incluirPorId();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	
	if($_REQUEST['acao'] == "Cancelar"){
		$oParticipante->cancelar();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oParticipante->excluir();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	
	if($_REQUEST['acao'] == "Editar"){
		$oParticipante->editar();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	
	if($_REQUEST['acao'] == "EditarSeguro"){
		$oParticipante->editarSeguro();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	
	if($_REQUEST['acao'] == "Reativar"){
		$oParticipante->reativar();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	
	if($_REQUEST['acao'] == "Distribuir"){
		$oParticipante->distribuir();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	if($_REQUEST['acao'] == "Remover"){
		$oParticipante->removerQuarto();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	if($_REQUEST['acao'] == "SalvaContrato"){
		$oParticipante->salvaContrato();
		header("Location:participante.lista.php?idGrupo=".$_REQUEST['idGrupo']);
	}
	
	
	
	if($_REQUEST['acao'] == "IncluirPagamento"){
		$oPagamento->incluirPagamento();
	header("Location:participante.pagamentos.php?idGrupo=".$_REQUEST['idGrupo']."&idParticipante=".$_REQUEST['idParticipante']);
	}
	if($_REQUEST['acao'] == "AlterarPagamento"){
		$oPagamento->alterarPagamento();
	header("Location:participante.pagamentos.php?idGrupo=".$_REQUEST['idGrupo']."&idParticipante=".$_REQUEST['idParticipante']);
	}
	if($_REQUEST['acao'] == "ExcluirPagamento"){
		$oPagamento->getById($oPagamento->md5_decrypt($_REQUEST['idPagamento']));
		$oPagamento->excluirPagamento();
	header("Location:participante.pagamentos.php?idGrupo=".$_REQUEST['idGrupo']."&idParticipante=".$_REQUEST['idParticipante']);
	}
	
	
	if($_REQUEST['acao'] == "IncluirAbatimento"){
		$oAbatimento->incluirAbatimento();
		header("Location:participante.abatimentos.php?idPagamento=".$_REQUEST['idPagamento']);
	}
	if($_REQUEST['acao'] == "AlterarAbatimento"){
		$oAbatimento->alterarAbatimento();
		header("Location:participante.abatimentos.php?idPagamento=".$_REQUEST['idPagamento']);
	}
	if($_REQUEST['acao'] == "CancelarAbatimento"){
		$oAbatimento->cancelarAbatimento();
		header("Location:participante.abatimentos.php?idPagamento=".$_REQUEST['idPagamento']);
	}

	
}else{
	$_SESSION['tupi.mensagem'] = 5;
	header("Location:home.php" );
}

?>