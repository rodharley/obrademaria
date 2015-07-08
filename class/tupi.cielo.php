<?
class cielo extends persistencia{


	const ENCODING = "ISO-8859-1";

	    public $dadosEcNumero;
		public $dadosEcChave;

		public $dadosPedidoMoeda = "986";

	    public $dadosPedidoData;
		public $dadosPedidoIdioma = "PT";

			 public $formaPagamentoBandeira;
		 public $formaPagamentoProduto;
		  public $formaPagamentoParcelas;
		  public $stDesenvolvimento;

      public $urlRetorno;
	  public $autorizar=2;
	  public $capturar;



	  public $tid;
	  public $pan;
	  public $status;





	  //log
	  private $log_file = "logs/xml.log";
	  private $fp = null;





private function XMLHeader()
		{
			return '<?xml version="1.0" encoding="' . self::ENCODING . '" ?>';
		}

private function XMLDadosEc()
		{
			$msg = '<dados-ec>' . "\n      " .
						'<numero>'
							. $this->dadosEcNumero .
						'</numero>' . "\n      " .
						'<chave>'
							. $this->dadosEcChave .
						'</chave>' . "\n   " .
					'</dados-ec>';

			return $msg;
		}

private function XMLDadosPedido($oFatura)
		{


			$this->dadosPedidoData = date("Y-m-d") . "T" . date("H:i:s");
			$msg = '<dados-pedido>' . "\n      " .
						'<numero>'
							. $oFatura->id .
						'</numero>' . "\n      " .
						'<valor>'
							.str_replace('.','',str_replace(',','',$this->money($oFatura->valor,"atb"))).
						'</valor>' . "\n      " .
						'<moeda>'
							. $this->dadosPedidoMoeda .
						'</moeda>' . "\n      " .
						'<data-hora>'. $this->dadosPedidoData .'</data-hora>' . "\n      ";
			if(! is_null($oFatura->detalhe) && $oFatura->detalhe != "")
			{
				$msg .= '<descricao>'
					. str_replace("<BR/>","",$oFatura->detalhe).
					'</descricao>' . "\n      ";
			}


			$msg .= '<idioma>'
						. $this->dadosPedidoIdioma .
					'</idioma>' . "\n   " .
					'</dados-pedido>';

			return $msg;
		}



private function XMLFormaPagamento()
		{
			$msg = '<forma-pagamento>' . "\n      " .
						'<bandeira>'
							. $this->formaPagamentoBandeira .
						'</bandeira>' . "\n      " .
						'<produto>'
							. $this->formaPagamentoProduto .
						'</produto>' . "\n      " .
						'<parcelas>'
							. $this->formaPagamentoParcelas .
						'</parcelas>' . "\n   " .
					'</forma-pagamento>';

			return $msg;
		}

private function XMLUrlRetorno()
		{
			$msg = '<url-retorno>' . $this->urlRetorno . '</url-retorno>';

			return $msg;
		}


	private function XMLAutorizar()
		{
			$msg = '<autorizar>' . $this->autorizar . '</autorizar>';

			return $msg;
		}


	private function XMLCapturar()
		{
			$msg = '<capturar>' . $this->capturar . '</capturar>';

			return $msg;
		}

public function ToString($oFatura)
		{
			$msg = $this->XMLHeader() .
				   '<objeto-pedido>'
				    . '<tid>' . $this->tid . '</tid>'
				    . '<status>' . $this->status . '</status>'
				   	. $this->XMLDadosEc()
				   	. $this->XMLDadosPedido($oFatura)
				   	. $this->XMLFormaPagamento() .
				   '</objeto-pedido>';

			return $msg;
		}



function RequisicaoConsulta($tid,$idFatura)
		{

		if($this->setaDadosDoCartao()){


	define('VERSAO', "1.1.0");


	if($this->stDesenvolvimento==1)
	define("ENDERECO_BASE", "https://qasecommerce.cielo.com.br");
	else
    define("ENDERECO_BASE", "https://ecommerce.cbmp.com.br");

	define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");



		$this->urlRetorno = $this->ReturnURL();


			$test = $this->DAO_ExecutarQuery("select * from tbPagamento WHERE idFatura = ".$idFatura." and status=0 and tid = '".$tid."' order by id desc");



			$gu = mysql_fetch_array($test);

			$msg = $this->XMLHeader() . "\n" .
				   '<requisicao-consulta id="' . md5(date("YmdHisu")) . '" versao="' . VERSAO . '">' . "\n   "
				    . '<tid>' . $gu['tid'] . '</tid>' . "\n   "
				    . $this->XMLDadosEc() . "\n" .
				   '</requisicao-consulta>';
			$objResposta = $this->Enviar($msg, "Consulta");
			return	$objResposta;


		}
		}
















function FromString($Str)
		{
			$DadosEc = "dados-ec";
			$DadosPedido = "dados-pedido";
			$DataHora = "data-hora";
			$FormaPagamento = "forma-pagamento";

			$XML = simplexml_load_string($Str);
			print_r($XML);

		/*	$this->tid = $XML->tid;
			$this->status = $XML->status;
			$this->dadosEcChave = $XML->$DadosEc->chave;
			$this->dadosEcNumero = $XML->$DadosEc->numero;
			$this->dadosPedidoNumero = $XML->$DadosPedido->numero;
			$this->dadosPedidoData = $XML->$DadosPedido->$DataHora;
			$this->dadosPedidoValor = $XML->$DadosPedido->valor;
			$this->formaPagamentoProduto = $XML->$FormaPagamento->produto;
			$this->formaPagamentoParcelas = $XML->$FormaPagamento->parcelas;

			*/
		}

function salvarStatusPan($status,$pan,$idFatura,$tid){
	$ins = $this->makeSQL("UPDATE tbPagamento
			set

		   		status 	= '".$status ."',
				pan 	= '".$pan ."'

			WHERE
				idFatura = ".$idFatura." and tid='".$tid."'");





	}










		function getStatus($status)
		{


			switch($status)
			{
				case "0": $status = "Criada";
						break;
				case "1": $status = "Em andamento";
						break;
				case "2": $status = "Autenticada";
						break;
				case "3": $status = "N&atilde;o autenticada";
						break;
				case "4": $status = "Autorizada";
						break;
				case "5": $status = "N&atilde;o autorizada";
						break;
				case "6": $status = "Capturada";
						break;
				case "8": $status = "N&atilde;o capturada";
						break;
				case "9": $status = "Cancelada";
						break;
				case "10": $status = "Em autentica&ccedil;&atilde;o";
						break;
				default: $status = "n/a";
						break;
			}

			return $status;
		}




function efetuarPgt($oFatura,$oContrato,$r){




	if($this->setaDadosDoCartao()){







	define('VERSAO', "1.1.0");


	if($this->stDesenvolvimento==1)
	define("ENDERECO_BASE", "https://qasecommerce.cielo.com.br");
	else
    define("ENDERECO_BASE", "https://ecommerce.cbmp.com.br");

	define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");


		$this->urlRetorno = $this->ReturnURL();


		if($r['tipoPagamento']==2)
		$this->formaPagamentoBandeira = 'visa';

		if($r['tipoPagamento']==3)
		$this->formaPagamentoBandeira = 'mastercard';

		if($r['tipoPagamento']==4)
		$this->formaPagamentoBandeira = 'diners';

		$this->formaPagamentoProduto = $r['modelo'];
	    $this->formaPagamentoParcelas = $r['parcela'];
		return $this->RequisicaoTransacao(false,$oFatura,$oContrato);

		}else{

		return false;
		}






	}


	function setaDadosDoCartao(){
		$oConf = new configuracoes();
		$oConf->getById(1);
	  	$this->dadosEcNumero = $oConf->dadosEcNumero;//"1001734898";
 		$this->dadosEcChave = $oConf->dadosEcChave;//"e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832";
		$this->stDesenvolvimento = $oConf->stDesenvolvimento;//1;
		$this->capturar = $oConf->capturar;//'true';
		return true;
}



	public function Enviar($vmPost, $transacao)
		{

			$this->logWrite("ENVIO: " . $vmPost, $transacao);

			// ENVIA REQUISIÇÃO SITE CIELO
			$vmResposta = $this->httprequest(ENDERECO, "mensagem=" . $vmPost);
			$this->logWrite("RESPOSTA: " . $vmResposta, $transacao);

			VerificaErro($vmPost, $vmResposta);
			return simplexml_load_string($vmResposta);
		}





	function RequisicaoTransacao($incluirPortador,$oFatura,$oContrato)
		{
			$msg = $this->XMLHeader() . "\n" .
				   '<requisicao-transacao id="' . md5(date("YmdHisu")) . '" versao="' . VERSAO . '">' . "\n   "
				   		. $this->XMLDadosEc() . "\n   ";

			//compra na maquineta // não usar
			if($incluirPortador == true)
			{
					$msg .=	$this->XMLDadosPortador() . "\n   ";
			}


			$msg .=		  $this->XMLDadosPedido($oFatura) . "\n   "
				   		. $this->XMLFormaPagamento() . "\n   "
				   		. $this->XMLUrlRetorno() . "\n   "
				   		. $this->XMLAutorizar() . "\n   "
				   		. $this->XMLCapturar() . "\n" ;

			$msg .= '</requisicao-transacao>';

			$objResposta = $this->Enviar($msg, "Transacao");

			$this->tid = $objResposta->tid;
			$this->pan = $objResposta->pan;
			$this->status = $objResposta->status;
			$urlAutenticacao = "url-autenticacao";
			$this->urlAutenticacao = $objResposta->$urlAutenticacao;
			$StrPedido = $this->ToString($oFatura);
			$valorTotalParaEnviar = $oFatura->valor;
	  		$ins = $this->DAO_ExecutarQuery("INSERT INTO tbPagamento
				(tid,status,pan,bandeira,parcelas,formaPagamentoProduto,valor,idFatura,dataInicio)
            	VALUES
				('".$this->tid."','".$this->status."','".$this->pan."','".$this->formaPagamentoBandeira."','".$this->formaPagamentoParcelas ."','".$this->formaPagamentoProduto."','".$valorTotalParaEnviar."',".$oFatura->id.",'".$this->dadosPedidoData."' )");






	echo '<script type="text/javascript">
			window.location.href = "' . $this->urlAutenticacao . '"
		 </script>';

		return	$this->tid;

		}




	function ReturnURL()
{
	/*/$pageURL = 'http';

	//if ($_SERVER["SERVER_PORT"] == 443) // protocolo https
	{
		$pageURL .= 's';
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"]. substr($_SERVER["REQUEST_URI"], 0);
	}
	// ALTERNATIVA PARA SERVER_NAME -> HOST_HTTP

	$file = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);



	$ReturnURL = str_replace($file, "index.php?pag=includes/finalizar/retornocielo.php", $pageURL);
	$ReturnURL =  str_replace('?pag=includes/finalizar/concluirPedido.php','',$ReturnURL);

	return $ReturnURL;*/
	return "http://intra.msiserver.com.br/retornoPagamento.php";
}






//log



public function logOpen()
		{
			$this->fp = fopen($this->log_file, 'a');
		}

		public function logWrite($strMessage, $transacao)
		{
			if(!$this->fp)
				$this->logOpen();

			$path = $_SERVER["REQUEST_URI"];
			$data = date("Y-m-d H:i:s:u (T)");

			$log = "***********************************************" . "\n";
			$log .= $data . "\n";
			$log .= "DO ARQUIVO: " . $path . "\n";
			$log .= "OPERAÇÃO: " . $transacao . "\n";
			$log .= $strMessage . "\n\n";

			fwrite($this->fp, $log);
		}
















		//testando

		function httprequest($paEndereco, $paPost){

	$sessao_curl = curl_init();
	curl_setopt($sessao_curl, CURLOPT_URL, $paEndereco);

	curl_setopt($sessao_curl, CURLOPT_FAILONERROR, true);

	//  CURLOPT_SSL_VERIFYPEER
	//  verifica a validade do certificado
	curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYPEER, false);
	//  CURLOPPT_SSL_VERIFYHOST
	//  verifica se a identidade do servidor bate com aquela informada no certificado
	curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYHOST, 2);

	//  CURLOPT_SSL_CAINFO
	//  informa a localização do certificado para verificação com o peer



	curl_setopt($sessao_curl, CURLOPT_CAINFO, getcwd() .
			"/ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.crt");
	curl_setopt($sessao_curl, CURLOPT_SSLVERSION, 3);

	//  CURLOPT_CONNECTTIMEOUT
	//  o tempo em segundos de espera para obter uma conexão
	curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, 10);

	//  CURLOPT_TIMEOUT
	//  o tempo máximo em segundos de espera para a execução da requisição (curl_exec)
	curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 40);

	//  CURLOPT_RETURNTRANSFER
	//  TRUE para curl_exec retornar uma string de resultado em caso de sucesso, ao
	//  invés de imprimir o resultado na tela. Retorna FALSE se há problemas na requisição
	curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($sessao_curl, CURLOPT_POST, true);
	curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, $paPost );

	$resultado = curl_exec($sessao_curl);

	curl_close($sessao_curl);

	if ($resultado)
	{
		return $resultado;
	}
	else
	{
		return curl_error($sessao_curl);
	}
}


}






$logFile = $_SERVER['DOCUMENT_ROOT']."/logs/log.log";

	// Verifica em Resposta XML a ocorrência de erros
	// Parâmetros: XML de envio, XML de Resposta
	function VerificaErro($vmPost, $vmResposta)
	{
		$error_msg = null;

		try
		{
			if(stripos($vmResposta, "SSL certificate problem") !== false)
			{
				throw new Exception("CERTIFICADO INVÁLIDO - O certificado da transação não foi aprovado", "099");
			}

			$objResposta = simplexml_load_string($vmResposta, null, LIBXML_NOERROR);
			if($objResposta == null)
			{
				throw new Exception("HTTP READ TIMEOUT - o Limite de Tempo da transação foi estourado", "099");
			}
		}
		catch (Exception $ex)
		{
			$error_msg = "     Código do erro: " . $ex->getCode() . "\n";
			$error_msg .= "     Mensagem: " . $ex->getMessage() . "\n";

			// Gera página HTML
			echo '<html><head><title>Erro na transação</title></head><body>';
			echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transação!</span>' . '<br />';
			echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
			echo '<pre>' . $error_msg . '<br /><br />';
			//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
			echo '</pre><p><center>';
			echo '<input type="button" value="Retornar" onclick="javascript:if(window.opener!=null){window.opener.location.reload();' .
				 'window.close();}else{window.location.href=' . "'index.php';" . '}" />';
			echo '</center></p></body></html>';
			$error_msg .= "     XML de envio: " . "\n" . $vmPost;

			// Dispara o erro
			trigger_error($error_msg, E_USER_ERROR);

			return true;
		}

		if($objResposta->getName() == "erro")
		{
			$error_msg = "     Código do erro: " . $objResposta->codigo . "\n";
			$error_msg .= "     Mensagem: " . utf8_decode($objResposta->mensagem) . "\n";
			// Gera página HTML
			echo '<html><head><title>Erro na transação</title></head><body>';
			echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transação!</span>' . '<br />';
			echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
			echo '<pre>' . $error_msg . '<br /><br />';
			//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
			echo '</pre><p><center>';
			echo '<input type="button" value="Retornar" onclick="javascript:if(window.opener!=null){window.opener.location.reload();' .
				 'window.close();}else{window.location.href=' . "'index.php';" . '}" />';
			echo '</center></p></body></html>';
			$error_msg .= "     XML de envio: " . "\n" . $vmPost;

			// Dispara o erro
			trigger_error($error_msg, E_USER_ERROR);
		}
	}


	// Grava erros no arquivo de log
	function Handler($eNum, $eMsg, $file, $line, $eVars)
	{
		$logFile = $_SERVER['DOCUMENT_ROOT']."/logs/log.log";;
		$e = "";
		$Data = date("Y-m-d H:i:s (T)");

		$errortype = array(
				E_ERROR 			=> 'ERROR',
				E_WARNING			=> 'WARNING',
				E_PARSE				=> 'PARSING ERROR',
				E_NOTICE			=> 'RUNTIME NOTICE',
				E_CORE_ERROR		=> 'CORE ERROR',
				E_CORE_WARNING      => 'CORE WARNING',
                E_COMPILE_ERROR     => 'COMPILE ERROR',
                E_COMPILE_WARNING   => 'COMPILE WARNING',
                E_USER_ERROR        => 'ERRO NA TRANSACAO',
                E_USER_WARNING      => 'USER WARNING',
                E_USER_NOTICE       => 'USER NOTICE',
                E_STRICT            => 'RUNTIME NOTICE',
                E_RECOVERABLE_ERROR	=> 'CATCHABLE FATAL ERROR'
				);


if($errortype[$eNum]<>'RUNTIME NOTICE'){
		$e .= "**********************************************************\n";
		$e .= $eNum . " (" . $errortype[$eNum] . ") - ";
		$e .= $Data . "\n";
		$e .= "     ARQUIVO: " . $file . "(Linha " . $line .")\n";
		$e .= "     MENSAGEM: " . "\n" . $eMsg . "\n\n";

		error_log($e, 3, $logFile);

		exit();
}
	}

	$olderror = set_error_handler("Handler");
	ini_set('error_log', $logFile);
	ini_set('log_errors', 'On');
	ini_set('display_errors', 'On');
	ini_set("date.timezone", "America/Sao_Paulo");


?>
