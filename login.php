<?php 
include("tupi.inicializar.php");

$tupi->trataRequestAntiInjection();
unset($_SESSION['ag_nomeUsuario']);
unset($_SESSION['ag_idUsuario']);
unset($_SESSION['ag_perfilUsuario']);
unset($_SESSION['ag_idPerfilUsuario']);
unset($_SESSION['ag_emailUsuario']);
unset($_SESSION['ag_itensMenu']);

$user = new usuario();
$msg = new Mensagem();

	$user->getByEmail($_REQUEST['email']);
	
	if($user->id == NULL){
		 $_SESSION['tupi.mensagem'] = 3;
		$user->jsReturn("-1");
		exit();
	}else{
		if($user->senha != md5($_REQUEST['senha'])){
			 $_SESSION['tupi.mensagem'] = 4;
			$user->jsReturn("-1");
			exit();
		}else{
			
			//executa agendamentos
			$ag = new Agendamento();
			//$ag->enviarEmailsAniversariantes();
			//$ag->enviarEmailsCartoesPrePagos();
			//$ag->enviarEmailsContasAPagar();
			//$ag->enviarEmailsPassaportes();
			//$ag->enviarEmailsChegadaGrupo();
			//gera menu de acesso
			$oAcesso = new acesso();
			$acessos = $oAcesso->getRows(0,999,array(),array("perfil" => " = ".$user->perfil->id));
			$listMenu = "999";
			foreach ($acessos as $acesso){
				$listMenu .=  ",".$acesso->menu->id;	
			}
			
			//gravar log de acesso
			$olog = new LogAcesso();
			$olog->dataHora = date("Y-m-d H:i:s");
			$olog->usuario = $user->id;
			$olog->menu = 'NULL';
			$olog->save();
			//setar as variaveis de sessao
			$_SESSION['ag_idUsuario'] = $user->id ;
			$_SESSION['ag_nomeUsuario'] = $user->nome;
			$_SESSION['ag_idPerfilUsuario'] = $user->perfil->id;
			$_SESSION['ag_perfilUsuario'] = $user->perfil->descricao;
			$_SESSION['ag_emailUsuario'] = $user->email;
			$_SESSION['ag_itensMenu'] = $listMenu;
			//gravar usuario no cookie
			setcookie("loginODM", $_REQUEST['email']);
			$user->location("home.php","");
			exit();
		}
	}

?>
