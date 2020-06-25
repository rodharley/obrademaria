<?php
include("../../tupi.inicializar.php");
$obCheckout = new GerenciaNetCheckOut();
$obCliente = new Cliente();
$obGrupo = new Grupo();
$obCidade = new Cidade();
$obParticipante = new Participante();
try{
//grupo

if(!$obGrupo->getById($_REQUEST['idGrupo'])){
    throw new Exception("Grupo não encontrado");
}
//incluir o cliente
$idCliente = $obCliente->saveBySite($_REQUEST);

//incluir como participante no grupo
$idParticipante  = $obParticipante->saveBySite($obGrupo,$obCliente,isset($_REQUEST['opcional'])?1:0);

//outros participantes
$idPartAcomp1 = 0;
$idPartAcomp2 = 0;
$idPartAcomp3 = 0;
$idPartAcomp4 = 0;
if($_REQUEST['quantidade'] > 1){
    if(isset($_REQUEST['acompanhante1'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante1'],$_REQUEST['email1'],isset($_REQUEST['mailmarketing']) ? 1 : 0);
        $idPartAcomp2  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
    if(isset($_REQUEST['acompanhante2'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante2'],$_REQUEST['email2'],isset($_REQUEST['mailmarketing']) ? 1 : 0);
        $idPartAcomp3  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
    if(isset($_REQUEST['acompanhante3'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante3'],$_REQUEST['email3'],isset($_REQUEST['mailmarketing']) ? 1 : 0);
        $idPartAcomp4  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
    if(isset($_REQUEST['acompanhante4'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante4'],$_REQUEST['email4'],isset($_REQUEST['mailmarketing']) ? 1 : 0);
        $idPartAcomp4  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
}


$charge = $obCheckout->createCharge($obParticipante,$obGrupo,$_REQUEST['quantidade'],isset($_REQUEST['opcional'])?1:0,$idPartAcomp1,$idPartAcomp2,$idPartAcomp3,$idPartAcomp4);

echo json_encode($obCheckout->createLinkPagamento($charge['data']['charge_id'],'',$_REQUEST['pagamento']));
}catch(Exception $e){
    $mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"404","data"=>array("mensagem"=>"$mensagem")));
}