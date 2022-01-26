<?php
/**
 *
 * PayPal PHP
 * 
 * @author: "Arthur 'ArTDsL' Dias dos Santos Lasso";
 * @version: "1.0.0.0";
 * 
 * Dev At: "2022-01-25";
 * Last Update: "2022-01-26";
 * 
 * file: 'paypalphp.class.php';
 *
 * Copyright (c) 2022. Arthur 'ArTDsL' Dias dos Santos Lasso.
 * This  program  is  free  software:  you can  redistribute it  and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the  License, or (at your 
 * option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * Github Repo: "https://github.com/ArTDsL/paypal-php/"
 *
 */
//
include_once('paypalphp.conf.php');
//
Class PAYPALPHP{
	protected string $paypal_clientID;
	protected string $paypal_secretToken;
	private string $paypal_url;

	private string $paypal_api_url;
	private string $paypal_sandbox_api_url;
	//
	public function __construct(){
		//Api URL
		$this->paypal_api_url = 'https://api-m.paypal.com/';
		$this->paypal_sandbox_api_url = 'https://api-m.sandbox.paypal.com/';
		//
		if(SANDBOX_MODE == true && (empty(__CLIENT_ID_TOKEN_SANDBOX__) || empty(__SECRET_TOKEN_SANDBOX__))){
			throw new Exception(_PLUGIN_ERROR_ID_."Your SANDBOX credentials are empty, sandbox mode is TRUE, so SANDBOX credentials are required.");
		}else if(SANDBOX_MODE == false && (empty(__CLIENT_ID_TOKEN_LIVE__) || empty(__SECRET_TOKEN_LIVE__))){
			throw new Exception(_PLUGIN_ERROR_ID_."Your LIVE credentials are empty, sandbox mode is FALSE, so LIVE credentials are required.");
		}
		if(SANDBOX_MODE == true){
			$this->paypal_clientID = __CLIENT_ID_TOKEN_SANDBOX__;
			$this->paypal_secretToken = __SECRET_TOKEN_SANDBOX__;
			$this->paypal_url = $this->paypal_sandbox_api_url;
		}else{
			$this->paypal_clientID = __CLIENT_ID_TOKEN_LIVE__;
			$this->paypal_secretToken = __SECRET_TOKEN_LIVE__;
			$this->paypal_url = $this->paypal_api_url;
		}
	}
	// ------------------------------------------ Authentication ------------------------------------------
	/*
	 *
     * Authenticate at Paypal
     * return: Json Client Request.
	 *
	 */
	private function PPPHP_ClientCredentials(){
		$ch = curl_init();
		$url = $this->paypal_url.'v1/oauth2/token';
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json', 'Accept-Language: en_US'));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->paypal_clientID.':'.$this->paypal_secretToken);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		if(DEBUG_MODE == true){
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}else{
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);
		}
		
    	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$ret = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$json_recebido = json_decode($ret);
		//
		header('Content-Type: application/json');
		return $ret;
	}
	/*
	*
 	* Get Paypal Access Token With Credentials Detail at Paypal
	* return: Json with order Details.
	*
 	*/
	private function PPPHP_GetClientAccessToken(){
		$bearer = json_decode($this->PPPHP_ClientCredentials())->access_token;
		return $bearer;
	}
	// ---------------------------------------------- Orders ----------------------------------------------
	/**
 	* Get Order Detail at Paypal
	* return: Json with order Details.
	*
	* @param 	string 	Order id number.
	*
 	*/
	private function PPPHP_GetOrder($orderid){
		if($orderid == null){
			return $this->PPPHP_TrowError('Order Id cannot be null.', 400, true);
		}
		$order_pattern = "/[A-Z-a-z-0-9]/";
		if(!preg_match($order_pattern, $orderid)){
			return $this->PPPHP_TrowError('Order Id have bad characters.', 400, true);
		}

		$bearer = $this->PPPHP_GetClientAccessToken();
		$ch = curl_init();
		$url = $this->paypal_url.'v2/checkout/orders/'.$orderid;
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json', 'Accept-Language: en_US', 'Authorization: Bearer '.$bearer));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->paypal_clientID.':'.$this->paypal_secretToken);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		if(DEBUG_MODE == true){
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}else{
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);
		}
		
    	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$ret = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$json_recebido = json_decode($ret);
		return $ret;
	}
	public static function GetOrder($orderid){
		return (new self)->PPPHP_GetOrder($orderid);
	}
	/**
 	* Create Order at Paypal
	* return: Json with order Details/links.
	*
	* @param 	string/int 		intent should be specified as CAPTURE (0) OR AUTHORIZE (1).
	* @param 	string 			Reference ID for Order.
	* @param 	string 			Order description.
	* @param 	string/decimal 	Order value (price).
	* 
 	*/
	private function PPPHP_CreateOrder($intent, $order_referece, $order_description, $order_value){
		if($intent != 0 && $intent != 1 && $intent != 'CAPTURE' && $intent != 'AUTHORIZE'){
			return $this->PPPHP_TrowError('Bad intent, check your code and try again.', 400, true);
		}
		if(is_numeric($intent)){
			switch ($intent) {
				case 0:
					$intent = 'CAPTURE';
					break;
				
				case 1:
					$intent = 'AUTHORIZE';
					break;
			}
		}
		$order = [
            "intent" => $intent,
            "purchase_units" => [
                [
                	"reference_id" => $order_referece,
                    "amount" => [
                        "currency_code" => _PAYMENT_CURRENCY_CODE_,
                        "value" => $order_value
                    ],
                    "description" => $order_description
                ]
            ]
        ];
		$bearer = $this->PPPHP_GetClientAccessToken();
		$ch = curl_init();
		$url = $this->paypal_url.'v2/checkout/orders';
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json', 'Accept-Language: en_US', 'Authorization: Bearer '.$bearer, 'Prefer: return=representation'));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERPWD, $this->paypal_clientID.':'.$this->paypal_secretToken);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order));
		if(DEBUG_MODE == true){
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}else{
			curl_setopt($ch, CURLOPT_VERBOSE, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);
		}
		
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$ret = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$json_recebido = json_decode($ret);
		return $ret;
	}
	public static function CreateOrder($intent, $order_referece, $order_description, $order_value){
		return (new self)->PPPHP_CreateOrder($intent, $order_referece, $order_description, $order_value);
	}
	// ---------------------------------------------- Untils ----------------------------------------------
	private function PPPHP_TrowError($msg, $http_code, $isError){
		header('Content-Type: application/json');
		$ret['isError'] = $isError;
		$ret['message'] = _PLUGIN_ERROR_ID_.' '.$msg;
		echo json_encode($ret);
		http_response_code(400);
		exit;
	}
}
