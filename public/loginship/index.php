<?php

require_once 'Functions.php';

$fun = new Functions();


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $data = json_decode(file_get_contents("php://input"));

  if(isset($data -> operation)){

  	$operation = $data -> operation;

  	if(!empty($operation)){

  		if($operation == 'register'){

  			if(isset($data -> user ) && !empty($data -> user) && isset($data -> user -> sp_name) 
  				&& isset($data -> user -> sp_email) && isset($data -> user -> sp_password)){

  				$user = $data -> user;
  				$sp_name = $user -> sp_name;
  				$sp_email = $user -> sp_email;
  				$sp_password = $user -> sp_password;

          if ($fun -> isEmailValid($sp_email)) {
            
            echo $fun -> registerUser($sp_name, $sp_email, $sp_password);

          } else {

            echo $fun -> getMsgInvalidEmail();
          }

  			} else {

  				echo $fun -> getMsgInvalidParam();

  			}

  		}else if ($operation == 'login') {

        if(isset($data -> user ) && !empty($data -> user) && isset($data -> user -> sp_email) && isset($data -> user -> sp_password)){

          $user = $data -> user;
          $sp_email = $user -> sp_email;
          $sp_password = $user -> sp_password;

          echo $fun -> loginUser($sp_email, $sp_password);

        } else {

          echo $fun -> getMsgInvalidParam();

        }
      } else if ($operation == 'chgPass') {

        if(isset($data -> user ) && !empty($data -> user) && isset($data -> user -> sp_email) && isset($data -> user -> old_password) 
          && isset($data -> user -> new_password)){

          $user = $data -> user;
          $sp_email = $user -> sp_email;
          $old_password = $user -> old_password;
          $new_password = $user -> new_password;

          echo $fun -> changePassword($sp_email, $old_password, $new_password);

        } else {

          echo $fun -> getMsgInvalidParam();

        }
      }

  	}else{

  		
  		echo $fun -> getMsgParamNotEmpty();

  	}
  } else {

  		echo $fun -> getMsgInvalidParam();

  }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET'){


  echo "Learn2Crack Login API";

}

