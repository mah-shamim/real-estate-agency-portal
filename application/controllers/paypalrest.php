<?php

class Paypalrest extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('settings_m');
        $this->load->model('user_m');
        $this->load->model('packages_m');
        
    }
    
    public function index()
    {
        echo 'test here';
    }
    
    private function _check_login()
    {        
        $this->load->library('session');
        $this->load->model('user_m');
        
        // Login check
        if($this->user_m->loggedin() == FALSE)
        {
            redirect('frontend/login/'.$this->data['lang_code']);
        }
    }

    public function cancel_subscription($subscription_id)
    {

        $this->_check_login();

        $this->data['settings'] = $this->settings_m->get_fields();

        $paypal_api_url = config_db_item('recurring_api_url');
        $PAYPAL_CLIENT_ID = config_db_item('recurring_client_id');
        $PAYPAL_SECRET = config_db_item('recurring_secret');

        $user_id = $this->session->userdata('id');

        $user = $this->user_m->get($user_id);

        if($user->recurring_subscription_id != $subscription_id)exit('Wrong subscription ID');

        $headers = array(   "Accept: application/json", 
            "Accept-Language: en_US",
            "Content-Type: multipart/form-data"
        ); 

        $data = "grant_type=client_credentials";

        $results = paypal_api_call('POST', $paypal_api_url.'v1/oauth2/token', $data, $headers, $PAYPAL_CLIENT_ID.':'.$PAYPAL_SECRET);
        $json_token = json_decode($results);

        if(empty($json_token->access_token))exit('Failing get access token');

        $access_token = $json_token->access_token;


/*

curl -v -X POST https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/cancel \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <Access-Token>"

*/

        $headers = array(   "Accept: application/json", 
                            "Accept-Language: en_US",
                            "Content-Type: application/json",
                            "Authorization: Bearer $access_token",
                            ); 

        $results = paypal_api_call('POST', $paypal_api_url.'v1/billing/subscriptions/'.$user->recurring_subscription_id.'/cancel', NULL, $headers);

        // if($results == FALSE)...

        // redirect to myproperties with message

        if($user->type == 'AGENT')
        {
            redirect(site_url('admin/estate').'?subcription_canceled=1');
        }
        else
        {
            redirect(site_url('frontend/myproperties').'?subcription_canceled=1');
        }
    }
   
    public function payment_recurring($lang_code, $price, $currency_code, $reference_id, $reference_code)
    {
        $this->_check_login();

        $this->data['settings'] = $this->settings_m->get_fields();

        $paypal_api_url = config_db_item('recurring_api_url');
        $PAYPAL_CLIENT_ID = config_db_item('recurring_client_id');
        $PAYPAL_SECRET = config_db_item('recurring_secret');

        if(empty($PAYPAL_CLIENT_ID)) {
            exit("Recurring payments, missing configuration data in settings");
        }
        
        $user_id = $this->session->userdata('id');
        $user_type = $this->session->userdata('type');
        $user = $this->user_m->get($user_id);
        $price = number_format($price, 2, '.', '');


        // get plan details

        $package_details = $this->packages_m->get($reference_id);

        if(!isset($package_details->package_days))
        {
            exit("Package not detected");
        }
        
        $invoice_num = $reference_id.'_'.$reference_code.'_'.$user_id.'_'.$price.'_'.date('w');
        $description = $reference_code.' '.$reference_id;
        
        if($reference_code == 'PAC')
        {
            $description = 'Package '.$reference_id;
        }
        /*
        else if($reference_code == 'RES')
        {
            $description = 'Reservation '.$reference_id;
        }
        else if($reference_code == 'ACT')
        {
            $description = 'Activate listing '.$reference_id;
        }
        else if($reference_code == 'FEA')
        {
            $description = 'Featured listing '.$reference_id;
        }
        */

        // Get access token

        $headers = array(   "Accept: application/json", 
            "Accept-Language: en_US",
            "Content-Type: multipart/form-data"
        ); 

        $data = "grant_type=client_credentials";

        $results = paypal_api_call('POST', $paypal_api_url.'v1/oauth2/token', $data, $headers, $PAYPAL_CLIENT_ID.':'.$PAYPAL_SECRET);
        $json_token = json_decode($results);

        if(empty($json_token->access_token))exit('Failing get access token');

        $access_token = $json_token->access_token;

        $allow_create_subscription = TRUE;

        // check if subscription is active I-WNNFSPR45LCX
        if(!empty($user->recurring_subscription_id))
        {
            $this->data['subscription_id'] = $user->recurring_subscription_id;

/*

curl -v -X POST https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/activate \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <Access-Token>"

*/

/*
$headers = array(   "Accept: application/json", 
                    "Accept-Language: en_US",
                    "Content-Type: application/json",
                    "Authorization: Bearer $access_token",
                    ); 

$results = paypal_api_call('POST', $paypal_api_url."v1/billing/subscriptions/{$user->recurring_subscription_id}/activate", NULL, $headers);

dump($results);
*/

/*
curl -v -X GET https://api-m.sandbox.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <Access-Token>"
*/
$headers = array(   "Accept: application/json", 
                    "Accept-Language: en_US",
                    "Content-Type: application/json",
                    "Authorization: Bearer $access_token",
                    ); 

$results = paypal_api_call('GET', $paypal_api_url.'v1/billing/subscriptions/'.$user->recurring_subscription_id, NULL, $headers);
$subscription = json_decode($results);

//dump($subscription);
//dump($subscription->status);
//exit();

            if($subscription->status == 'ACTIVE')
            {
                $allow_create_subscription = FALSE;
            }

        }

        if($allow_create_subscription)
        {
            // create product

            $headers = array(   "Accept: application/json", 
                                "Accept-Language: en_US",
                                "Content-Type: application/json",
                                "Authorization: Bearer $access_token",
                                "PayPal-Request-Id: PRODUCT-$invoice_num"
            ); 

            $invoice_num = $reference_id.'_'.$reference_code.'_'.$user_id.'_'.$price.'_'.date('w');
            $description = $reference_code.' '.$reference_id;

            $data = array(
                "name" => $description,
                "description"=> $description,
                "type"=> "SERVICE",
                "category"=> "SOFTWARE",
                //"image_url"=> "https://example.com/streaming1.jpg",
                //"home_url"=> site_url()
            );

            $results = paypal_api_call('POST', $paypal_api_url.'v1/catalogs/products', $data, $headers);
            $product = json_decode($results);


            if(empty($product->id))
            {
                dump($product);
                exit('Failing get product ID');
            }

            $product_id = $product->id;

            // create plan

            $headers = array(   "Accept: application/json", 
                                "Accept-Language: en_US",
                                "Content-Type: application/json",
                                "Authorization: Bearer $access_token",
                                "Prefer: return=representation",
                                //"PayPal-Request-Id: $invoice_num"
            ); 

/*

curl -v –X POST https://api-m.sandbox.paypal.com/v1/billing/plans /
-H "Content-Type: application/json" /
-H "Authorization: Bearer <Access-Token>" /
-d '{
    "name": "Premium Video Plus",
    "description": "Premium plan with video download feature",
    "product_id": "PROD-5RN21878H3527870P",
    "billing_cycles": [
        {
            "frequency": {
                "interval_unit": "MONTH",
                "interval_count": 1
            },
            "tenure_type": "REGULAR",
            "sequence": 1,
            "total_cycles": 0,   
            "pricing_scheme": {
                "fixed_price": {
                    "value": "20",
                    "currency_code": "USD"
                }
            }
        }
    ],
    "payment_preferences": {
        "auto_bill_outstanding": true,
        "payment_failure_threshold": 1
    }
 }'

*/

            $data = array(
                    "product_id" => $product_id,
                    "name" => $description,
                    "description" => $description,
                    "status" => "ACTIVE",
                    "billing_cycles"=> array(
                            array(
                                "frequency" => array(
                                    "interval_unit" => "DAY",
                                    "interval_count" => $package_details->package_days
                                ),
                                "tenure_type" => "REGULAR",
                                "sequence" => 1,
                                "total_cycles" => 0,
                                "pricing_scheme" => array(
                                    "fixed_price" => array(
                                        "value" => $price,
                                        "currency_code" => $currency_code
                                    )
                                )
                            ),
                    ),
                    "payment_preferences" => array(
                        "auto_bill_outstanding" => true,
                        /*
                        "setup_fee"=> array(
                            "value"=> "0",
                            "currency_code"=> $currency_code
                        ),*/

                        //"setup_fee_failure_action" => "CONTINUE",
                        "payment_failure_threshold" => 1
                    ),/*
                    "taxes" => array(
                    "percentage" => "10",
                    "inclusive" => true
                    )*/
            );

            $results = paypal_api_call('POST', $paypal_api_url.'v1/billing/plans', $data, $headers);

            $plan = json_decode($results);

            if(empty($plan->id))exit('Failing get plan ID');

            $plan_id = $plan->id;

            $this->user_m->save(array('recurring_plan_id' => $plan_id, 
                                      'recurring_package_id' => $reference_id), 
                                $user_id);

            $this->data['plan_id'] = $plan_id;
        }

        $this->data['allow_create_subscription'] = $allow_create_subscription;
        $this->data['user'] = $user;

        $output = $this->load->view($this->data['settings']['template'].'/recurring_form.php', $this->data, TRUE);
        echo str_replace('assets/', base_url('templates/'.$this->data['settings']['template']).'/assets/', $output);
    }

    //https://proptino.co.uk/index.php/paypalrest/webhooks

    public function webhooks()
    {
        $this->data['settings'] = $this->settings_m->get_fields();

        $paypal_api_url = config_db_item('recurring_api_url');
        $PAYPAL_CLIENT_ID = config_db_item('recurring_client_id');
        $PAYPAL_SECRET = config_db_item('recurring_secret');
        
        $request_body = file_get_contents('php://input');
        file_put_contents(FCPATH.'paypal/hooks_data/data_received_'.date("Y-m-d-H-i-s").'.txt', $request_body); 
        
        $received_data = json_decode($request_body);

        if($received_data->resource_type == 'subscription')
        if($received_data->event_type == 'BILLING.SUBSCRIPTION.CREATED')
        {
            $plan_id = $received_data->resource->plan_id;
            $subscription_id = $received_data->resource->id;

            file_put_contents(FCPATH.'paypal/hooks_data/subscription_id_'.date("Y-m-d-H-i-s").'.txt', $subscription_id.'-'.$plan_id); 

            $user = $this->user_m->get_by(array(
                'recurring_plan_id' => $plan_id
            ), TRUE);

            file_put_contents(FCPATH.'paypal/hooks_data/duserID_'.date("Y-m-d-H-i-s").'.txt', $user->id); 
            if(!isset($user->id))return;

            // get plan details

            $package_details = $this->packages_m->get($user->recurring_package_id);

            if(!isset($package_details->package_days))
            {
                exit("Package not detected");
            }

            $this->db->set(array('recurring_subscription_id' => $subscription_id));
            $this->db->where('id', $user->id);
            $this->db->update('user');
        }

        if($received_data->resource_type == 'subscription')
        if($received_data->event_type == 'BILLING.SUBSCRIPTION.ACTIVATED')
        {
        }

        if($received_data->resource_type == 'sale')
        if($received_data->event_type == 'PAYMENT.SALE.COMPLETED')
        {
            $subscription_id = $received_data->resource->billing_agreement_id;

            // extend package related
            $user = $this->user_m->get_by(array(
                'recurring_subscription_id' => $subscription_id
            ), TRUE);

            if(!isset($user->id))return;

            // get plan details

            $package_details = $this->packages_m->get($user->recurring_package_id);

            if(!isset($package_details->package_days))
            {
                exit("Package not detected");
            }

            $from_time = time();
            if(strtotime($user->package_last_payment) > $from_time)
                $from_time = strtotime($user->package_last_payment);

            $this->db->set(array(
                            'package_last_payment' => date('Y-m-d H:i:s', strtotime("+".$package_details->package_days." days", $from_time)),
                            'package_id'           => $package_details->id 
                        ));

            $this->db->where('id', $user->id);
            $this->db->update('user');

            // save sale transaction to transactions table
            $data = array();
            $data['invoice_num'] = $received_data->id.'_'.$user->id.'_PAC_'.$package_details->id ;
            $data['date_paid'] = date('Y-m-d H:i:s');
            $data['data_post'] = serialize($received_data);
            $data['payer_id'] = '';
            $data['txn_id'] = $received_data->resource->id;
            $data['paid'] = $received_data->resource->amount->total;
            $data['currency_code'] = $received_data->resource->amount->currency;
            $data['payer_email'] = $user->mail;
            $data['payment_gateway'] = 'PayPal Recurring Payment';
            $data['user_id'] = $user->id;
            $data['listing_id'] = '';
            $data['package_id'] = $package_details->id ;
            $data['reservation_id'] = '';
            
            $this->load->model('payments_m');
            $payments_id = $this->payments_m->save($data);
        }

    }
    
}

function paypal_api_call($method, $url, $data, $headers = false, $userpass = NULL){
    $curl = curl_init();

    //$data = 'email=test';

    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
          if ($data)
          {
            //$data = http_build_query($data);

            if(is_array($data))
                $data = json_encode($data);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            if($headers === FALSE)
                $headers = array('Content-Type: multipart/form-data');
          }
             
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break;
        case "DELETE":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break; 
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    if(!$headers){
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
        ));
    }else{
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if(isset($headers['User-Agent']))
            curl_setopt($curl, CURLOPT_USERAGENT, $headers['User-Agent']);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    if(!empty($userpass))
    {
        curl_setopt($curl, CURLOPT_USERPWD, $userpass);
    }

    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){return FALSE;}
    curl_close($curl);
    return $result;
}

?>