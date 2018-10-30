<?

class Biblioteca extends Configuracao {

function arredondar_dois_decimal($valor) {
   $float_arredondar=round($valor * 100) / 100;
     return $float_arredondar;
   
} 


function loginContratosEmnuvem (){
	
	    $headers = array('Accept' => 'application/json','Token' => base64_encode($this->usercn.":".$this->senhacn));
        Unirest\Request::verifyPeer(false); 
        $response = Unirest\Request::get($this->endpointcn.'free/auth', $headers, null);
		return $response->body;
}

function getEnvs( $s_var ){
		$rs = false;
		if( @getenv( $s_var ) ){
		$rs = strtolower( getenv( $s_var ) );
		}else{
			if( isset( $_SERVER[$s_var] ) ){
			$rs = strtolower( $_SERVER[$s_var] );
			}
		}
		return $rs;
	}


function ultimoDiaMes($data){
$tsData = strtotime($data);
$ultimoDiaMes = date("t",$tsData);
return  date("Y",$tsData)."-".date("m",$tsData)."-".$ultimoDiaMes;	
}

function mesExtenso($int){
switch($int){
case 1:
$str = "Janeiro";
break;
case 2:
$str = "Fevereiro";
break;
case 3:
$str = "Mar�o";
break;
case 4:
$str = "Abril";
break;
case 5:
$str = "Maio";
break;
case 6:
$str = "Junho";
break;
case 7:
$str = "Julho";
break;
case 8:
$str = "Agosto";
break;
case 9:
$str = "Setembro";
break;
case 10:
$str = "Outubro";
break;
case 11:
$str = "Novembro";
break;
case 12:
$str = "Dezembro";
break;
}

return  $str;	
}

function limpaDigitos($texto){
return str_replace(".","",str_replace("-","",str_replace("/","",str_replace("_","",$texto))));
}


function ValidaData($dat){
	$data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como refer�ncia
	$d = $data[0];
	$m = $data[1];
	$y = $data[2];

	// verifica se a data � v�lida!
	// 1 = true (v�lida)
	// 0 = false (inv�lida)
	return checkdate($m,$d,$y);
}


function paginar ($total,$pagina){

if($pagina == "")
$pagina = 1;	
$paginas = ceil($total / $this->PAGINACAO);
$inicio =  $this->PAGINACAO *($pagina-1);
if($pagina < $paginas)
$proximaPagina = $pagina+1;
else
$proximaPagina =  $paginas;

if($pagina > 1)
$paginaAnterior = $pagina-1;
else
$paginaAnterior = $pagina;
return array('totalPaginas'=>$paginas,'primeiroRegistro'=>$inicio,'proximaPagina'=>$proximaPagina,'paginaAnterior'=>$paginaAnterior,'quantidadePorPagina'=>$this->PAGINACAO);	
}

function showMensagem($tip,$idMsg){
	$omsg = new mensagem();
	$omsg->getById($idMsg);
$string = '<div id="message-'.$tip.'">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="'.$tip.'-left">'.$omsg->mensagem.'</td>
					<td class="'.$tip.'-right"><a class="close-'.$tip.'"><img src="images/table/icon_close_'.$tip.'.gif"   alt="" /></a></td>
				</tr>
				</table>
				</div>';
				return $string;
}





function resultadoAleatorio($array,$quantObjetos){

		

	$indiceArray = array_rand($array,$quantObjetos);

	

	return $indiceArray;

}





/*

================================================================

	RETIRA A UTIMA VIRGULA DA STRING DA CAMPO DA QUERY DO UPDATE

================================================================

*/



function substituiUtimaVirgula($palavra){



	 if($palavra = substr_replace($palavra,' ', strlen($palavra) - 1,  strlen($palavra)))

	 return  $palavra;

	 else false;

}





/*

================================================================

	DIFERENA ENTRE DATAS

	na pagina que chamar esta funao, colocar a data no seguinte formato

	$inicial = 00/00/0000

	$final = 00/00/0000

================================================================

*/

function diferenca_dias($inicial, $final) { 

  list($dia_inicial, $mes_inicial, $ano_inicial) = explode("/", $inicial); 

  list($dia_final, $mes_final, $ano_final) = explode("/", $final); 



  $inicial2 = mktime(0,0,0,$mes_inicial,$dia_inicial,$ano_inicial); 

  $final2 = mktime(0,0,0,$mes_final,$dia_final,$ano_final); 



  $dias = ($final2 - $inicial2)/86400; 



  return round($dias); 

} 



/*

================================================================

	CONVERSO DE VALORES

================================================================

*/



		function money($valor,$tipo){

			if($tipo == "bta"){

				$number = str_replace('.','',$valor);

				$final1 = str_replace(',','.',$number);

				$final = $final1;

			}elseif($tipo == "atb"){

				$final = number_format($valor, 2, ',','.');

			}else{

				$final = "2 parmetro deve ser bta ou atb";

			}

			return $final;

		}

		

		

		

		

		

		

		

		

		

		

		/*

================================================================

 Verifica e-mail

================================================================

*/

		

		

		

		

		function verificar_email($email){ 

   $mail_correcto = 0; 

   //verifico umas coisas 

   if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 

      if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 

         //vejo se tem caracter . 

         if (substr_count($email,".")>= 1){ 

            //obtenho a terminao do dominio 

            $term_dom = substr(strrchr ($email, '.'),1); 

            //verifico que a terminao do dominio seja correcta 

         if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 

            //verifico que o de antes do dominio seja correcto 

            $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 

            $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 

            if ($caracter_ult != "@" && $caracter_ult != "."){ 

               $mail_correcto = 1; 

            } 

         } 

      } 

   } 

} 



if ($mail_correcto) 

   return 1; 

else 

   return 0; 

}

		

		

		

		

		

		



/*

================================================================

	CONVERSO DE DATA

================================================================

*/





		function convdata($dataentra,$tipo){ 

		  if ($tipo == "mtn") { 

			$datasentra = explode("-",$dataentra); 

			$indice=2; 

			while($indice != -1){ 

			  $datass[$indice] = $datasentra[$indice]; 

			  $indice--; 

			} 

			$datasaida=implode("/",$datass); 

		  } elseif ($tipo == "ntm") { 

			$datasentra = explode("/",$dataentra); 

			$indice=2; 

			while($indice != -1){ 

			  $datass[$indice] = $datasentra[$indice]; 

			  $indice--; 

			} 

			$datasaida = implode("-",$datass); 

		  } elseif ($tipo == "mtnh") { 
			
			$datasentra = explode("-",substr($dataentra,0,10)); 

			$indice=2; 

			while($indice != -1){ 

			  $datass[$indice] = $datasentra[$indice]; 

			  $indice--; 

			} 

			$datasaida= implode("/",$datass); 
			$datasaida .= substr($dataentra,10);

		  } else { 

			$datasaida = "erro"; 

		  } 

		  return $datasaida; 

		}

		

		function valida_datas($d1,$d2){

			$data1 = explode('/',$d1);

			$primeira = $data1[2].$data1[1].$data1[0];

			

			$data2 = explode('/',$d2);

			$segunda = $data2[2].$data2[1].$data2[0];

		

			if ($segunda > $primeira) {

				$maior = false;

				}else{$maior = true;

			}

		

			if(!checkdate(substr($d1,3,2),substr($d1,0,2),substr($d1,6,4))|| !checkdate(substr($d2,3,2),substr($d2,0,2),substr($d2,6,4)) || $maior){

				$final = true;}else{

				$final = false;}

				return $final;

			}

		

		

		function valida_data($d1){

			if(!checkdate(substr($d1,3,2),substr($d1,0,2),substr($d1,6,4))){

			$final = true;}else{

			$final = false;}

			return $final;

		}		



/*

================================================================

	CONEXAO

================================================================

*/

		function makeSQL($sql){

		$result = mysql_query($sql)or die('

		<table width="300" height="200" border="0" align="center" cellpadding="0" cellspacing="0">

          <thead>

		  <tr>

            <td width="9" height="37"><img src="img/pc5.gif" width="9" height="37" /></td>

            <td background="img/pc11.gif">Erro</td>

            <td width="9"><img src="img/pc6.gif" width="9" height="37" /></td>

          </tr>

		  </thead>

		  <tbody>

          <tr>

            <td background="img/pc9.gif">&nbsp;</td>

            <td><table width="100%" border="0" cellspacing="0" cellpadding="8" class="tbForm">

              <tr>

                <td>'.mysql_error().'</td>

              </tr>

			  <tr>

                <td>'.$sql.'</td>

              </tr>

            </table>

              </td>

            <td background="img/pc10.gif">&nbsp;</td>

          </tr>

		  </tbody>

		  <tfoot>

          <tr>

            <td height="9"><img src="img/pc7.gif" width="9" height="9" /></td>

            <td background="img/pc12.gif"></td>

            <td><img src="img/pc8.gif" width="9" height="9" /></td>

          </tr>

		  </tfoot>

        </table>');

		return $result;

		}

		



//----------------------------------------------------------------------------------------------------------------------

function mail_html($destinatario,$origem, $titulo, $mensagem) 

{ 

    
    try{
    $headers = "MIME-Version: 1.1\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";
$headers .= "From: Obra de Maria <$origem>"."\n"; // remetente
$headers .= "Return-Path: Obra de Maria <$origem>"."\n"; // return-path
$email = @mail("$destinatario", "$titulo", "$mensagem", $headers, "-r".$origem);    
            
    return $email;
    }catch(exception $e){
        return false;
    }
    
    /*
    $headers ="Content-Type: text/html; charset=iso-8859-1\n"; 

    $headers.="From: $origem\n"; 

    $email = mail("$destinatario", "$titulo", "$mensagem", "$headers"); 

	return $email;
*/
} 

function makePassword($digitos){

$alpha = array("a","b","c","d","e","f","g","0","1","2","3","4","5","6","7","8","9");

	$senha = "";

	for ($i=0;$i<$digitos;$i++){

	$key = array_rand($alpha);

	$senha .= $alpha[$key];

	}

	return $senha;

}



function alert($mensagem){

echo '<script>window.alert("'.$mensagem.'");</script>';

return true;

}

function jsReturn($pagina){

echo '<script>history.go('.$pagina.');</script>';

return true;

}

function alertMensagens($codigo){

$query = "select * from et_mensagens where idMensagem = ".$codigo;

$rs = $this->makeSQL($query);

echo '<script>window.alert("'.mysql_result($rs,0,'mensagem').'");</script>';

return true;

}

function location($url,$mensagem){

$str = '<script>';

if($mensagem != ""){

$str .= 'window.alert("'.$mensagem.'");';

}

if($url != ""){

$str .= 'window.location.href="'.$url.'";';

}



$str .= '</script>';

echo $str;

exit();

}

function locationNewPage($url,$parametros){

$str = '<script>';

$str .= 'window.open("'.$url.'","'.$paramentros.'")';

$str .= '</script>';

echo $str;

}



function locationOpener($url,$mensagem){

$str = '<script>';

if($mensagem != ""){

$str .= 'window.alert("'.$mensagem.'");';

}

if($url != ""){

$str .= 'window.opener.location.href="'.$url.'";';

$str .= 'window.close();';

}



$str .= '</script>';

echo $str;

return  true;

}



function javascript($script){

echo '<script>'.$script.'</script>';

return true;

}



function listObject($recset){

$arrayObj = array();

	while($row = mysql_fetch_array($recset)){

	array_push($arrayObj,$row);

	}

return $arrayObj;

}



function removeSQL($param){

$string = str_replace(";","",$param);

$string = str_replace("'","",$string);

$string = str_replace("\"","",$string);

return $string;

}

//CONVERTE MINUTOS EM DIA HORA MINUTOS

function convmin($m){

if($m > 1440){

$dias = floor($m/1440);

$resto = $m -($dias*1440);

	if($resto > 60){

	$horas = floor($resto/60);

	$resto = $resto -($horas*60);

	}else{

	$horas = 0;

	$resto = $resto;	

	}

}else{

$dias = 0;

$resto = $m;

	if($resto > 60){

	$horas = floor($resto/60);

	$resto = $resto -($horas*60);

	}else{

	$horas = 0;

	$resto = $resto;	

	}

}

$string = "";

if ($dias > 0) 

$string .= $dias." dia(s) ";

if($horas > 0)

$string .= $horas." hora(s) ";

if($resto > 0)

$string .=  $resto." minutos ";

return $string;

}



//-------------------------------------



//CONVERTE GRAMAS EM KILOS----------------



function convKilo($valor,$tipo){

if($tipo == "gtk"){

return number_format($valor/1000,3,",",".");

}else{

return number_format($valor*1000);

}



}



function convKiloAmericano($valor,$tipo){

if($tipo == "gtk"){

return number_format($valor/1000,1,".",",");

}else{

return number_format($valor*1000);

}



}

//----------------------------------------

//mtodos get e set genrico

function setCampo($valor,$campo){

$this->$campo = $valor;

return true;

}



function getCampo($campo){

return $this->$campo;

}

function setAllFieldsTheClass($Array){

	foreach ($Array as $field => $value) {

		$this->setCampo($value,$field);

	}

	return true;

}



/*
================================================================
	REMOVE CARACTERE
================================================================
*/
function removerAcento($palavra){

		$ret = array(
	'/[�`^~#$%�&* ]/'=>'_',
	'/[-+�������]/'=>'_',
		"/[']/"=>'_',
		'/[�`^~#$%�&*@]+-*]/'=>'_',
	    
		'/[ ]/'=>'_',
		
		'/[�����]/'=>'A',
		
		'/[�����]/'=>'a',
		
		'/[����]/'=>'E',
		
		'/[����]/'=>'e',
		
		'/[����]/'=>'I',
		
		'/[����]/'=>'i',
		
		'/[�����]/'=>'O',
		
		'/[�����]/'=>'o',
		
		'/[����]/'=>'U',
		
		'/[����]/'=>'u',
		
		'/�/'=>'c',
		
		'/�/'=> 'C'
		
		);
	
    $ret = preg_replace(array_keys($ret), array_values($ret), $palavra);
	return($ret);
	} 

	

function apagaImagem($nomeImagem,$diretorio)	{
	
	if(file_exists($diretorio.$nomeImagem)){
	
	
		if(unlink($diretorio . $nomeImagem)) {
			return(true);
		} else {
			return(false);
		}
		
		}else{
		return(false);		
		}
		
		
	}	

function retornaNomeUnico($nomeImagem,$diretorio,$i=0)	{
	
		if(file_exists($diretorio.$nomeImagem)){
		$i++;
		$pos = strpos($nomeImagem,".");
		$nome = substr($nomeImagem,0,$pos).$i.substr($nomeImagem,$pos);
		return $this->retornaNomeUnico($nome,$diretorio,$i);	
		}else{
		return $nomeImagem;		
		}
		
		
	}	

/*
================================================================
	UPLOAD DE IMAGEM: basta passar como parametros  a diretiva $_FILES, nome da imagem tratado e o caminho do diretorio.
================================================================
*/	
function uploadImagem($file,$nomeImagem,$diretorio){	
	
if($file['name'] != ""){
		if($file["type"] == "image/gif" || $file["type"] == "image/pjpeg" || $file["type"] == "image/jpeg" || $file["type"] == "image/png"  || $file["type"] == "image/bmp"  || $file["type"] == "image/x-png" ||  $file["type"] == "application/x-shockwave-flash"  ||  $file["type"] == "aplication/x-shockwave-flash" ){
				                                       
	
		if($file['size'] > 500000){
		$this->alert("Imagem Maior que 500 kbytes!");
		exit();
		}
		 
		  copy($file['tmp_name'],$diretorio."".$nomeImagem);
	
	    }// fim if 2 type file
		else {
			$this->alert("Tipo de arquivo inv�lido.");
			exit();
		 }
	
	}// fim if 1 file name
}
	

function uploadArquivo($file,$nomeImagem,$diretorio){	
	if($file['name'] != ""){	
		copy($file['tmp_name'],$diretorio."".$nomeImagem);
	}// fim if 1 file name
	
	
	
	
	
}

	





function alertDiv($codigo,$tipo,$urlDestino,$target,$urlImg){

$query = "select * from et_mensagens where idMensagem = ".$codigo;

$rs = $this->makeSQL($query);

switch($tipo){

case 'error':

$string = '<div id="alert"><table border="0" height="100%"><tr><td align="center" valign="middle">'.mysql_result($rs,0,'mensagem').'</td></tr>

<tr><td height="20">

<a href="javascript:pesquisar_dados_cliente(\''.$urlDestino.'\',\''.$target.'\');"><img src="'.$urlImg.'bt/ok.gif" border="0"></a>

</td></tr></table>

</div>'; 

break;

default:

$string = '<div id="alert"><table border="0" height="100%"><tr><td align="center" valign="middle">'.mysql_result($rs,0,'mensagem').'</td></tr>

<tr><td height="20">

<a href="javascript:pesquisar_dados_cliente(\''.$urlDestino.'\',\''.$target.'\');"><img src="'.$urlImg.'bt/ok.gif" border="0"></a>

</td></tr></table>

</div>'; 

}

return $string;

}



function formataCep($cep){

$p1 = substr($cep,0,5);

$p2 = substr($cep,5,3);

return $p1.'-'.$p2;

}

function formataCPFCNPJ($cpf){

	if (strlen($cpf) == 11){

		$p1 = substr($cpf,0,3);

		$p2 = substr($cpf,3,3);

		$p3 = substr($cpf,6,3);

		$p4 = substr($cpf,9,2);

		return $p1.'.'.$p2.'.'.$p3.'-'.$p4;

	}

	else{

		if (strlen($cpf) == 14){

			$p1 = substr($cpf,0,2);

			$p2 = substr($cpf,2,3);

			$p3 = substr($cpf,5,3);

			$p4 = substr($cpf,8,4);

			$p5 = substr($cpf,13,2);

			return $p1.'.'.$p2.'.'.$p3.'/'.$p4.'-'.$p5;

		}

		else{

			$cpf;

		}    

	}

}







//*********************************************************************************

/*

================================================================

	retorna valor por extenso em reais passar os parametros: $valor como string e $maiusculas como 

	true ou false

================================================================

*/	



function extenso($valor,$maiusculas) 

{ 

    // verifica se tem virgula decimal 

    if (strpos($valor,",") > 0) 

    { 

      // retira o ponto de milhar, se tiver 

      $valor = str_replace(".","",$valor); 



      // troca a virgula decimal por ponto decimal 

      $valor = str_replace(",",".",$valor); 

    } 



        $singular = array("centavo", "real", "mil", "milho", "bilho", "trilho", "quatrilho"); 

        $plural = array("centavos", "reais", "mil", "milhes", "bilhes", "trilhes", 

"quatrilhes"); 



        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", 

"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"); 

        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", 

"sessenta", "setenta", "oitenta", "noventa"); 

        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", 

"dezesseis", "dezesete", "dezoito", "dezenove"); 

        $u = array("", "um", "dois", "trs", "quatro", "cinco", "seis", 

"sete", "oito", "nove"); 



        $z=0; 



        $valor = number_format($valor, 2, ".", "."); 

        $inteiro = explode(".", $valor); 

        for($i=0;$i<count($inteiro);$i++) 

                for($ii=strlen($inteiro[$i]);$ii<3;$ii++) 

                        $inteiro[$i] = "0".$inteiro[$i]; 



        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2); 

        for ($i=0;$i<count($inteiro);$i++) { 

                $valor = $inteiro[$i]; 

                $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]]; 

                $rd = ($valor[1] < 2) ? "" : $d[$valor[1]]; 

                $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : ""; 



                $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && 

$ru) ? " e " : "").$ru; 

                $t = count($inteiro)-1-$i; 

                $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : ""; 

                if ($valor == "000")$z++; elseif ($z > 0) $z--; 

                if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 

                if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && 

($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r; 

        } 



         if(!$maiusculas){ 

                          return($rt ? $rt : "zero"); 

         } elseif($maiusculas == "2") { 

                          return (strtoupper($rt) ? strtoupper($rt) : "Zero"); 

         } else { 

                          return (ucwords($rt) ? ucwords($rt) : "Zero"); 

         } 



} 



function removeCaracteres($str){
return strtr($str,"���������������������� ������������������������,;:?.","aaaaaeeeeiiiiooooouuuu_AAAAAEEEEIIIIOOOOOUUUUcC_____");
}


function modCaixaAlta($str){

return strtr(strtoupper($str),"","");

}

function msg($id){

$sql = "select texto from msg where idMsg = ".$id;

$rs = $this->makeSQL($sql);



if($this->DAO_NumeroLinhas($rs) == 0)

return "Erro no sistema desconhecido!";

else{

$r = $this->DAO_GerarArray($rs);

return $r['texto'];

}

}



function notInjection($str){

$strFim = str_replace(" or ","",str_replace(" = ","",$str));

return $strFim;

}



function bloqueiaComandoStatusEvento($listaPermitida,$status,$retorno){
	if(stripos($listaPermitida,$status)=== false){

	$this->location($retorno,"Comando no pode ser executado, estatus do evento no permite.");

	exit();

	}

}


      
function convertImgUrls($texto){
if($_SERVER['SERVER_PORT'] == 80)
$url = "http://".$_SERVER['HTTP_HOST'];
else
$url = "https://".$_SERVER['HTTP_HOST'];
return str_replace("/imagens/images/",$url."/imagens/images/",$texto);
}

function antiInjection2($str) { #Remove palavras suspeitas de injection.
$str = preg_replace(sql_regcase("/(\n|\r|%0a|%0d|Content-Type:|bcc:|to:|cc:|Autoreply:|from|select|insert|delete|where|drop table|show tables|show tables|\*|--|\\\\)/"), "", $str);
//$str = str_replace('../','',$str);
//$str = str_replace('/&#117;s&#101;rf&#105;&#108;&#101;s/','http://img.msisites.com.br/',$str);

//$str = str_replace('/&#117;s&#101;rf&#105;&#108;&#101;s/','http://img.msisites.com.br/',$str);
//$str = trim($str); # Remove espa�os vazios.
//$str = strip_tags($str); # Remove tags HTML e PHP.
//$str = addslashes($str); # Adiciona barras invertidas � uma string.
return $str;
}


function localizaType($tipo){
$retorno = "iconUKN.gif";
foreach ($this->mimeTypes2 as $key => $value) {
    $pos = strpos($value,$tipo);
	if($pos !== false){
	$retorno = $key;
	}
}
return $retorno;
}

function md5_encrypt($plain_text, $iv_len = 16)

{

   $plain_text .= "x13";

   $n = strlen($plain_text);

   if ($n % 16) $plain_text .= str_repeat("{TEXTO}", 16 - ($n % 16));

   $i = 0;

   $enc_text = $this->get_rnd_iv($iv_len);

   $iv = substr($this->HASH_URL ^ $enc_text, 0, 512);

   while ($i < $n) {

      $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));

      $enc_text .= $block;

      $iv = substr($block . $iv, 0, 512) ^ $this->HASH_URL;

      $i += 16;

   }
	return base64_encode($enc_text);
   ///return str_replace(" ","nbsp*",base64_encode($enc_text));

}



function get_rnd_iv($iv_len)

{

   $iv = '';

   while ($iv_len-- > 0) {

      $iv .= chr(mt_rand() & 0xff);

   }

   return $iv;

}



function md5_decrypt($enc_text, $iv_len = 16)

{

    //$enc_text = str_replace("nbsp*","+",$enc_text);

	$enc_text = str_replace(" ","+",$enc_text);

   $enc_text = base64_decode($enc_text);

   $n = strlen($enc_text);

   $i = $iv_len;

   $plain_text = '';

   $iv = substr($this->HASH_URL ^ substr($enc_text, 0, $iv_len), 0, 512);



   while ($i < $n) {

      $block = substr($enc_text, $i, 16);

      $plain_text .= $block ^ pack('H*', md5($iv));

      $iv = substr($block . $iv, 0, 512) ^ $this->HASH_URL;

      $i += 16;

   }



   $posF = strpos($plain_text,"x13{");

   if( strlen($posF) == 0)

   $posF = strpos($plain_text,"x13");

   //return preg_replace('/\x13\x00*$/', '', $plain_text);

   return substr($plain_text,0,$posF);

}

function trataRequestAntiInjection(){
	$Array = $_REQUEST;
	$arrayName = array_keys($Array);
	$re ='';
	for($b=0;$b<count($arrayName);$b++){
		if(!is_array($_REQUEST[$arrayName[$b]])){
			@$_REQUEST[$arrayName[$b]] = $this->antiInjection2($_REQUEST[$arrayName[$b]]);
		}
	}
}


}//FIM DA CLASSE

?>