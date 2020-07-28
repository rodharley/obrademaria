<?php

namespace Cielo\Serializer;

use Cielo\Authentication;
use Cielo\Authorization;
use Cielo\CieloException;
use Cielo\Token;
use Cielo\Transaction;
use DOMDocument;
use DOMXPath;

class TransactionResponseUnserializer
{
    const NS = 'http://ecommerce.cbmp.com.br';

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var DOMXPath
     */
    private $xpath;

    /**
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param  string $xml
     * @return Transaction
     * @throws CieloException se houver alguma mensagem de erro envolvida no XML
     */
    public function unserialize($xml)
    {
        $document = new DOMDocument('1.0', 'utf-8');

        $document->loadXML($xml);

        $this->xpath = new DOMXpath($document);
        $this->xpath->registerNamespace('c', TransactionResponseUnserializer::NS);

        if (($code = $this->xpath->query('/c:erro/c:codigo')->item(0)) !== null) {
            $message = $this->xpath->query('/c:erro/c:mensagem')->item(0)->nodeValue;

            throw new CieloException($message, $code->nodeValue);
        }

        $this->readTransacao($this->transaction);
        $this->readDadosPedido($this->transaction);
        $this->readFormaPagamento($this->transaction);
        $this->readAutenticacao($this->transaction);
        $this->readAutorizacao($this->transaction);
        $this->readToken($this->transaction);

        return $this->transaction;
    }

    /**
     * @param  string $query
     * @return string
     */
    private function getValue($query)
    {
        $node = $this->xpath->query($query)->item(0);

        if ($node !== null) {
            return $node->nodeValue;
        }
    }

    /**
     * @param Transaction $transaction
     */
    private function readTransacao(Transaction $transaction)
    {
        $transaction->setTid($this->getValue('//c:transacao/c:tid'));
        $transaction->setPan($this->getValue('//c:transacao/c:pan'));
        $transaction->setStatus($this->getValue('//c:transacao/c:status'));
        $transaction->setAuthenticationURL($this->getValue('//c:transacao/c:url-autenticacao'));
    }

    /**
     * @param Transaction $transaction
     */
    private function readDadosPedido(Transaction $transaction)
    {
        $order = $transaction->getOrder();

        $order->setNumber($this->getValue('//c:transacao/c:dados-pedido/c:numero'));
        $order->setTotal((int) $this->getValue('//c:transacao/c:dados-pedido/c:valor'));
        $order->setCurrency((int) $this->getValue('//c:transacao/c:dados-pedido/c:moeda'));
        $order->setDateTime($this->getValue('//c:transacao/c:dados-pedido/c:data-hora'));
        $order->setDescription($this->getValue('//c:transacao/c:dados-pedido/c:descricao'));
        $order->setLanguage($this->getValue('//c:transacao/c:dados-pedido/c:idioma'));
        $order->setShipping((int) $this->getValue('//c:transacao/c:dados-pedido/c:taxa-embarque'));
    }

    /**
     * @param Transaction $transaction
     */
    private function readFormaPagamento(Transaction $transaction)
    {
        $paymentMethod = $transaction->getPaymentMethod();

        $paymentMethod->setIssuer($this->getValue('//c:transacao/c:forma-pagamento/c:bandeira'));
        $paymentMethod->setProduct((int) $this->getValue('//c:transacao/c:forma-pagamento/c:produto'));
        $paymentMethod->setInstallments((int) $this->getValue('//c:transacao/c:forma-pagamento/c:parcelas'));
    }

    /**
     * @param Transaction $transaction
     */
    private function readAutenticacao(Transaction $transaction)
    {
        $authentication = new Authentication();

        $authentication->setCode($this->getValue('//c:transacao/c:autenticacao/c:codigo'));
        $authentication->setMessage($this->getValue('//c:transacao/c:autenticacao/c:mensagem'));
        $authentication->setDateTime($this->getValue('//c:transacao/c:autenticacao/c:data-hora'));
        $authentication->setTotal($this->getValue('//c:transacao/c:autenticacao/c:valor'));
        $authentication->setEci($this->getValue('//c:transacao/c:autenticacao/c:eci'));

        $transaction->setAuthentication($authentication);
    }

    /**
     * @param Transaction $transaction
     */
    private function readAutorizacao(Transaction $transaction)
    {
        $authorization = new Authorization();

        $authorization->setCode($this->getValue('//c:transacao/c:autorizacao/c:codigo'));
        $authorization->setMessage($this->getValue('//c:transacao/c:autorizacao/c:mensagem'));
        $authorization->setDateTime($this->getValue('//c:transacao/c:autorizacao/c:data-hora'));
        $authorization->setTotal($this->getValue('//c:transacao/c:autorizacao/c:valor'));
        $authorization->setLr($this->getValue('//c:transacao/c:autorizacao/c:lr'));
        $authorization->setArp($this->getValue('//c:transacao/c:autorizacao/c:arp'));
        $authorization->setNsu($this->getValue('//c:transacao/c:autorizacao/c:nsu'));

        $transaction->setAuthorization($authorization);
    }

    /**
     * @param Transaction $transaction
     */
    private function readToken(Transaction $transaction)
    {
        $token = new Token();

        $token->setCode($this->getValue('//c:transacao/c:token/c:dados-token/c:codigo-token'));
        $token->setStatus($this->getValue('//c:transacao/c:token/c:dados-token/c:status'));
        $token->setNumero($this->getValue('//c:transacao/c:token/c:dados-token/c:numero-cartao-truncado'));

        $transaction->setToken($token);
    }
}
