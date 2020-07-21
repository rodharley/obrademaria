<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 50;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>

    <li class="active">Editar Venda Web</li>
    </ul>';
}

$oV = new VendaSite();
$oP = new Participante();
$oPagamento = new Pagamento();
$oGn = new GerenciaNetCheckOut();
$oV->getById($oV->md5_decrypt($_REQUEST['idVenda']));
$pagamentos = $oPagamento->getPagamentosParticipanteWeb($oV->participante->id);

foreach($pagamentos as $key => $pag){
	$tpl->TRANSACAO = '-';
    $tpl->TIPO = 'Manual';
    $tpl->METODO = $pag->tipo->descricao;
    $tpl->ID = $pag->id;
    $tpl->STATUS = $pag->pago == 1 ? '<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-success">Pago</span>' :'<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-warning">Em aberto</span>';
    $tpl->VALOR = 'R$ '.$oV->money($pag->valorPagamento,"atb");
    $tpl->block("BLOCK_ITEM_LISTA");
}
if($oV->acompanhante1 != null){
$pagamentos = $oPagamento->getPagamentosParticipanteWeb($oV->acompanhante1);
foreach($pagamentos as $key => $pag){
	$tpl->TRANSACAO = '-';
    $tpl->TIPO = 'Manual';
    $tpl->METODO = $pag->tipo->descricao;
    $tpl->ID = $pag->id;
    $tpl->STATUS = $pag->pago == 1 ? '<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-success">Pago</span>' :'<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-warning">Em aberto</span>';
    $tpl->VALOR = 'R$ '.$oV->money($pag->valorPagamento,"atb");
    $tpl->block("BLOCK_ITEM_LISTA");
}
}
if($oV->acompanhante2 != null){
    $pagamentos = $oPagamento->getPagamentosParticipanteWeb($oV->acompanhante2);
    foreach($pagamentos as $key => $pag){
        $tpl->TRANSACAO = '-';
        $tpl->TIPO = 'Manual';
        $tpl->METODO = $pag->tipo->descricao;
        $tpl->ID = $pag->id;
        $tpl->STATUS = $pag->pago == 1 ? '<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-success">Pago</span>' :'<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-warning">Em aberto</span>';
        $tpl->VALOR = 'R$ '.$oV->money($pag->valorPagamento,"atb");
        $tpl->block("BLOCK_ITEM_LISTA");
    }
    }
    if($oV->acompanhante3 != null){
        $pagamentos = $oPagamento->getPagamentosParticipanteWeb($oV->acompanhante3);
        foreach($pagamentos as $key => $pag){
            $tpl->TRANSACAO = '-';
            $tpl->TIPO = 'Manual';
            $tpl->METODO = $pag->tipo->descricao;
            $tpl->ID = $pag->id;
            $tpl->STATUS = $pag->pago == 1 ? '<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-success">Pago</span>' :'<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-warning">Em aberto</span>';
            $tpl->VALOR = 'R$ '.$oV->money($pag->valorPagamento,"atb");
            $tpl->block("BLOCK_ITEM_LISTA");
        }
        }
        if($oV->acompanhante4 != null){
            $pagamentos = $oPagamento->getPagamentosParticipanteWeb($oV->acompanhante4);
            foreach($pagamentos as $key => $pag){
                $tpl->TRANSACAO = '-';
                $tpl->TIPO = 'Manual';
                $tpl->METODO = $pag->tipo->descricao;
                $tpl->ID = $pag->id;
                $tpl->STATUS = $pag->pago == 1 ? '<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-success">Pago</span>' :'<span val="'.$pag->id.'" title="Mudar status" class="changestatus label label-warning">Em aberto</span>';
                $tpl->VALOR = 'R$ '.$oV->money($pag->valorPagamento,"atb");
                $tpl->block("BLOCK_ITEM_LISTA");
            }
            }
include("tupi.template.finalizar.php");
?>