<?php
//iniciando acess�o
session_start();
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.iso-8859-1', 'portuguese');
header('Content-Type: text/html; charset=iso-8859-1');

//incluindo todas as classes
include("class/ready.php");

include("vendor/autoload.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Commons.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Address.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Cart.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Customer.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Discount.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Item.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Options.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Order.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Payment.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Services.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Shipping.php");
include("vendor/drupalista-br/checkoutcielo-library/src/Checkout/Transaction.php");

$tupi = new Tupi();
?>