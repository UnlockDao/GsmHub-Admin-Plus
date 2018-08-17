<?php

require_once 'DBOperations.php';

class Functions{

private $db;

public function __construct() {

      $this -> db = new DBOperations();

}


public function registerUser($sp_name, $sp_email, $sp_password) {

	$db = $this -> db;

	if (!empty($sp_name) && !empty($sp_email) && !empty($sp_password)) {

  		if ($db -> checkUserExist($sp_email)) {

  			$response["result"] = "failure";
  			$response["message"] = "User Already Registered !";
  			return json_encode($response);

  		} else {

  			$result = $db -> insertData($sp_name, $sp_email, $sp_password);

  			if ($result) {

				  $response["result"] = "success";
  				$response["message"] = "User Registered Successfully !";
  				return json_encode($response);
  						
  			} else {

  				$response["result"] = "failure";
  				$response["message"] = "Registration Failure";
  				return json_encode($response);

  			}
  		}					
  	} else {

  		return $this -> getMsgParamNotEmpty();

  	}
}

public function loginUser($sp_email, $sp_password) {

  $db = $this -> db;

  if (!empty($sp_email) && !empty($sp_password)) {

    if ($db -> checkUserExist($sp_email)) {

       $result =  $db -> checkLogin($sp_email, $sp_password);


       if(!$result) {

        $response["result"] = "failure";
        $response["message"] = "Invaissssld Login Credentials";
        return json_encode($response);

       } else {

        $response["result"] = "success";
        $response["message"] = "Login Successful";
        $response["user"] = $result;
        return json_encode($response);

       }

    } else {

      $response["result"] = "failure";
      $response["message"] = "111Invaild Login Credentials";
      return json_encode($response);

    }
  } else {

      return $this -> getMsgParamNotEmpty();
    }

}


public function changePassword($sp_email, $old_password, $new_password) {

  $db = $this -> db;

  if (!empty($sp_email) && !empty($old_password) && !empty($new_password)) {

    if(!$db -> checkLogin($sp_email, $old_password)){

      $response["result"] = "failure";
      $response["message"] = 'Invalid Old Password';
      return json_encode($response);

    } else {


    $result = $db -> changePassword($sp_email, $new_password);

      if($result) {

        $response["result"] = "success";
        $response["message"] = "Password Changed Successfully";
        return json_encode($response);

      } else {

        $response["result"] = "failure";
        $response["message"] = 'Error Updating Password';
        return json_encode($response);

      }

    } 
  } else {

      return $this -> getMsgParamNotEmpty();
  }

}

public function isEmailValid($sp_email){

  return filter_var($sp_email, FILTER_VALIDATE_EMAIL);
}

public function getMsgParamNotEmpty(){


  $response["result"] = "failure";
  $response["message"] = "Parameters should not be empty !";
  return json_encode($response);

}

public function getMsgInvalidParam(){

  $response["result"] = "failure";
  $response["message"] = "Invalid Parameters";
  return json_encode($response);

}

public function getMsgInvalidEmail(){

  $response["result"] = "failure";
  $response["message"] = "Invalid Email";
  return json_encode($response);

}

}
