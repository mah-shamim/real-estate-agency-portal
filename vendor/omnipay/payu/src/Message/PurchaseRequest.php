<?php

namespace Omnipay\PayU\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PayU Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://secure.payu.com/api/v2_1/orders';
    protected $testEndpoint = 'https://secure.payu.com/api/v2_1/orders';

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getData()
    {
/*
<form method="post" id="payu-payment-form" action="https://secure.payu.com/api/v2_1/orders">
    <input type="hidden" name="customerIp" value="127.0.0.1" />
    <input type="hidden" name="merchantPosId" value="145227" />
    <input type="hidden" name="description" value="Order description" />
    <input type="hidden" name="currencyCode" value="PLN" />
    <input type="hidden" name="totalAmount" value="1000" />
    <input type="hidden" name="products[0].name" value="Product 1" />
    <input type="hidden" name="products[0].unitPrice" value="1000" />
    <input type="hidden" name="products[0].quantity" value="1" />
    <input type="hidden" name="continueUrl" value="http://localhost/continue" />
    <input type="hidden" name="OpenPayu-Signature" value="sender=145227;algorithm=MD5;signature=34267d8d3844d90af7a4aac24f8ee5e4" />
    <button type="submit" formtarget="_blank" />
</form>   
*/  
        
        $amount = number_format($this->getAmount()*100,0,",","");
        
        
        $data = array();
        $data['customerIp'] = $this->getUserIP();
        $data['merchantPosId'] = $this->getMerchantId();
        $data['description'] = $this->getDescription();
        $data['currencyCode'] = 'CZK'; //$this->getCurrency();
        $data['totalAmount'] = $amount;
        
        
        $data['products[0].name'] = $this->getDescription();
        $data['products[0].unitPrice'] = $amount;
        $data['products[0].quantity'] = "1";
        
        $data['extOrderId'] = $this->getTransactionId().'_'.rand(100, 999);
        $data['notifyUrl'] = $this->getNotifyUrl();
        $data['continueUrl'] = $this->getReturnUrl();
        
        $data['OpenPayu-Signature'] = $this->generateHash($data);
        
//        echo '<pre>';
//        var_dump($data);
//        echo '</pre>';
//        exit();
        
        return $data;
    }
    
    private function getUserIP()
    {
//        $client  = @$_SERVER['HTTP_CLIENT_IP'];
//        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
//        $remote  = $_SERVER['REMOTE_ADDR'];
//    
//        if(filter_var($client, FILTER_VALIDATE_IP))
//        {
//            $ip = $client;
//        }
//        elseif(filter_var($forward, FILTER_VALIDATE_IP))
//        {
//            $ip = $forward;
//        }
//        else
//        {
//            $ip = $remote;
//        }
        
        $ip = $_SERVER['REMOTE_ADDR'];
    
        return $ip;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data, $this->getEndpoint());
    }

    private function generateHash($data)
    {
        if ($this->getSecretKey()) {
            //begin HASH calculation
            ksort($data);

            $hashString = "";
            foreach ($data as $key => $val) {
                $hashString .= $val;
            }
            
            $hashString.=$this->getSecretKey();
            
            $signature = "sender=".$data['merchantPosId'].";algorithm=MD5;signature=".hash("md5", $hashString);
            
//            echo '<pre>';
//            var_dump($data);
//            echo $signature;
//            echo '</pre>';
//            exit();
            
            return $signature;
        }

    }

}