<?php
class Cheque extends Persistencia{
	var $id = NULL;
	var $pagamento  = NULL;
	var $valor;
	var $numeroCheque;
	var $dataCompensacao;	
	var $status = NULL;
	var $emissor = NULL;
	var $parcela;

function pesquisa($dataI,$dataF,$ncheque,$status,$grupo = 0){
$sql = "select * from ag_cheque ch
INNER JOIN ag_pagamento pag ON pag.id = ch.idPagamento
INNER JOIN ag_participante p ON p.id = pag.participante where 1 = 1 ";


if($dataI != "")
$sql .= " and ch.data >= '".$dataI."'";
if($dataF != "")
$sql .= " and ch.data <= '".$dataF."'";

if($ncheque != "")
$sql .= " and ch.numero = ".$ncheque;

if($status != 0)
$sql .= " and ch.status = ".$status;

if($grupo != 0)
$sql .= " and p.grupo = ".$grupo;	

$sql .= " order by ch.data asc";
return $this->getSQL($sql);
}
}
?>