<?php
$path = str_replace("batch/cron.php","",$_SERVER['SCRIPT_FILENAME']);
//incluindo todas as classes
include($path."class/ready.php");
include($path."vendor/autoload.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Commons.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Address.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Cart.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Customer.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Discount.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Item.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Options.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Order.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Payment.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Services.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Shipping.php");
include($path."vendor/drupalista-br/checkoutcielo-library/src/Checkout/Transaction.php");
$menusite = 0;
$tupi = new Tupi();
try{
    //executa agendamentos
	$ag = new Agendamento();
	if(!$ag->debug){
			$ag->enviarEmailsAniversariantes();
			$ag->enviarEmailsCartoesPrePagos();
			$ag->enviarEmailsContasAPagar();
			$ag->enviarEmailsPassaportes();
			$ag->enviarEmailsChegadaGrupo();
    }		
    $ag->atualizaDollar();
}catch (Exception $e){
    echo "erro";
}