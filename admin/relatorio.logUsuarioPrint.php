<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 27;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório de Log de Usuários";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
//declara as classes
$ol = new LogUsuario;
$dataRelatorio = $ol->convdata($_REQUEST['dataInicio'],"ntm");
$dataFimRelatorio = $ol->convdata($_REQUEST['dataFim'],"ntm");
$rsLogs = $ol->logsPeriodo($dataRelatorio." 00:00:00",$dataFimRelatorio." 23:59:59",$_REQUEST['usuario']);
foreach($rsLogs as $key => $log){	
$tpl->USUARIO = $log->usuario->nome;
$tpl->DATA = $log->convdata(substr($log->data,0,10),"mtn")." ".substr($log->data,9);
$tpl->MOVIMENTO = $log->movimento;
$tpl->block("BLOCK_ITEM_LISTA");
}//fim do loop de grupos
$tpl->DATA_INICIO = $ol->convdata($dataRelatorio,"mtn");
$tpl->DATA_FIM = $ol->convdata($dataFimRelatorio,"mtn");
include("tupi.template.finalizar.php"); 
?>