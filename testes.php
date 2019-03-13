<?php
include("tupi.inicializar.php");
 $headers = array('Accept' => 'application/json','Token' => base64_encode("sistema@obrademaria.com.br:$%VSDGS#g%hrdvB"));
 Unirest\Request::verifyPeer(false); 
 $response = Unirest\Request::get('https://contratosemnuvem.com.br/servicos/api/index.php/free/auth', $headers, null);
 echo $response->body->jwt;
