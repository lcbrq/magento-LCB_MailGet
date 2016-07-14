<?php

/**
* @see www.formget.com
*/

global $mailget_user;
$mailget_select = '';

class mailget_curl{
	function __construct($mailget_api){
		global $mailget_user;
		$url="http://www.formget.com/mailget/mailget_api/user_validation";
		$data = array(
				'api_key'  => $mailget_api
			);
		$result = $this->curl_call($url, $data);
		$result = json_decode($result);
		if($result->status == 'success'){
			$mailget_user = $result->api_key;
		}else{
			$mailget_user = 'Invalid API Key';
		}
	}

	function mailget_select_list($mailget_api){
		global $mailget_user;
		$url="http://www.formget.com/mailget/mailget_api/get_select_list";
		$data = array(
				'api_key'  => $mailget_api
			);

		$result = $this->curl_call($url, $data);
		$result = json_decode($result);
		if($result->status == 'success'){
			$mailget_select = $result->select;
		}else{
			$mailget_select = 'Invalid API Key.';
		}
		return $mailget_select;
	}

	function get_list_in_json($mailget_api){
	global $mailget_user;
	$url="http://www.formget.com/mailget/mailget_api/get_list_in_json";
	$data = array(
	'api_key'  => $mailget_api
	);

	$result = $this->curl_call($url, $data);
	$result = json_decode($result);
	if($result->status == 'success'){
	$mailget_select = $result->contact_list;
	}else{
	$mailget_select = 'Invalid API Key.';
	}
	return $mailget_select;
	}

	/* fUNCTION CURL Data add emails to list*/

	public function curl_data($arr, $list_id,$send_val='multiple'){
		global $mailget_user;
		$main_contact_arr = array();
		if($mailget_user != 'Invalid API Key'){
			if(!empty($arr)){
				$url="http://www.formget.com/mailget/mailget_api/save_data";
				foreach($arr as $arr_row){
					if( isset($arr_row['name']) && isset($arr_row['email']) && isset($arr_row['get_date']) && isset($arr_row['ip']) && filter_var(trim($arr_row['email']),FILTER_VALIDATE_EMAIL)){
						$contact_arr['name']  = $arr_row['name'];
						$contact_arr['email'] = $arr_row['email'];
						if(isset($arr_row['get_date']) && $arr_row['get_date'] != ''){
						$contact_arr['date']  = $arr_row['get_date'];
						}else{
						$contact_arr['date']  = date('Y-m-d H:i:s');
						}
						$contact_arr['ip']    = $arr_row['ip'];
						$main_contact_arr[$arr_row['email']] = $contact_arr;
					}
				}
				if(!empty($main_contact_arr)){
					$main_data = json_encode($main_contact_arr);
					$data = array(
						'json_arr'  =>$main_data,
						'list_id_enc' =>$list_id,
						'send_val'=>$send_val
					);

					$result = $this->curl_call($url, $data);
					return $result;
				}
			}
		}else{
			return 'Invalid API Key';
		}
	}

	/* fUNCTION CURL Data delete emails from list*/

	public function delete_from_list($email,$list_id){
		global $mailget_user;
		$main_contact_arr = array();
		if($mailget_user != 'Invalid API Key'){
				$url="http://www.formget.com/mailget/mailget_api/delete_from_list";
				if(isset($email) && filter_var(trim($email),FILTER_VALIDATE_EMAIL)){
					$data = array(
						'email'  => $email,
						'list_id_enc' => $list_id
					);
					$result = $this->curl_call($url, $data);
					return $result;
				}else{
				return 'Invalid Email Id';
				}
			}else{
			return 'Invalid API Key';
		}
	}

	/* fUNCTION TO CALL cURL*/
	public function curl_call($url, $data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_URL,$url);
		$data = curl_exec($ch);
		return $data;
	}
}
?>