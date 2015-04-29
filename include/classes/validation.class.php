<?php

class Validation {

	public static function validateIfEmailIsUnique($email = ''){
		global $db;
		
		$existsEmail = $db->row("SELECT * FROM " . TABLE_USERS . " WHERE user_email = :user_email", array("user_email" => $email), PDO::FETCH_NUM);
		
		$return = array("status" => "", "msg" => "");
		
		if ($existsEmail) {
			$return['status'] = 'error';
			$return['msg'] = 'Ein Benutzer mit dieser E-Mail-Adresse existiert bereits.';
		}
		
		return json_encode($return);
	}
}

?>