<?php 

include("tupi.inicializar.php");
$codTemplate = 'tpl_shopping';
include("tupi.template.inicializar.php"); 
//$tupi->trataRequestAntiInjection();
unset($_SESSION['ag_nomeUsuario']);
unset($_SESSION['ag_idUsuario']);
unset($_SESSION['ag_perfilUsuario']);
unset($_SESSION['ag_idPerfilUsuario']);
unset($_SESSION['ag_emailUsuario']);
unset($_SESSION['ag_itensMenu']);

//CARREGA DADOS DO GRUPO 
if(isset($_REQUEST['idGrupo'])){
    $oGrupo = new Grupo();
    $oAg = new Agendamento();
    $oAg->getById(6);
    $tpl->COTACAO = $oAg->destinatarios;
    $oGrupo->getById($_REQUEST['idGrupo']);	
    $tpl->URL_IMAGE_GRUPO = $_REQUEST['foto'];
    $tpl->GRUPO_NOME = $oGrupo->nomePacote;
    $tpl->QUANTIDADE = 1;
    $tpl->GRUPO_IDMOEDA = $oGrupo->moeda->id;
    $tpl->GRUPO_VALOR = $oGrupo->getValorTotal(0);
    $tpl->GRUPO_VALOR_CURRENCY = $oGrupo->money($oGrupo->getValorTotal(0),"atb");
    $tpl->ID_GRUPO = $oGrupo->id;
    if($oGrupo->possuiPacoteOpcional ==1){
        $tpl->GRUPO_OPCIONAL_NOME = $oGrupo->nomePacoteOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR = $oGrupo->getValorTotalOpcional();
        $tpl->GRUPO_OPCIONAL_VALOR_CURRENCY = $oGrupo->money($oGrupo->getValorTotalOpcional(),"atb");
        $tpl->block('BLOCK_OPCIONAL');

    }else{
        $tpl->GRUPO_OPCIONAL_VALOR = 0;
    }
    $tpl->GRUPO_MOEDA = $oGrupo->moeda->cifrao;
    $tpl->GRUPO_OPCIONAL = $oGrupo->possuiPacoteOpcional;



    //endereco
    $ouf = new Uf();

    $ufs = $ouf->getRows(0,99,array("uf"=>'asc'));
    foreach ($ufs as $key => $uf) {
        $tpl->UF = $uf->uf;
        $tpl->block("BLOCK_ESTADO");
        # code...
    }
    
}
include("tupi.template.finalizar.php"); 