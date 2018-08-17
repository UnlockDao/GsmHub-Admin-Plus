<?php

class DBOperations{

	 private $host = 'localhost';
	 private $user = 'sdevs';
	 private $db = 'sdevs_fsm';
	 private $pass = '2nC6Ggdh333zhP';
	 private $conn;

public function __construct() {

	$this -> conn = new PDO("mysql:host=".$this -> host.";dbname=".$this -> db, $this -> user, $this -> pass);
	mysqli_set_charset($conn, 'UTF8');
}


 public function insertData($sp_name,$sp_email,$sp_password){
 	$unique_id = uniqid('', true);
    $hash = $this->getHash($sp_password);
    $sp_password = $hash["encrypted"];
	$salt = $hash["salt"];

 	$sql = 'INSERT INTO shipper SET unique_id =:unique_id,sp_name =:sp_name,
    sp_email =:sp_email,sp_password =:sp_password,salt =:salt';

 	$query = $this ->conn ->prepare($sql);
 	$query->execute(array('unique_id' => $unique_id, ':sp_name' => $sp_name, ':sp_email' => $sp_email,
     ':sp_password' => $sp_password, ':salt' => $salt));

    if ($query) {
        
        return true;

    } else {

        return false;

    }
 }


 public function checkLogin($sp_email, $sp_password) {

    $sql = 'SELECT * FROM shipper WHERE sp_email = :sp_email';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array(':sp_email' => $sp_email));
    $data = $query -> fetchObject();
    $salt = $data -> salt;
    $db_encrypted_password = $data -> sp_password;

    if ($this -> verifyHash($sp_password.$salt,$db_encrypted_password) ) {

		$user["id_sp"] = $data -> id_sp;
		$user["sp_name"] = $data -> sp_name;
        $user["sp_email"] = $data -> sp_email;
        $user["unique_id"] = $data -> unique_id;
        return $user;

    } else {

        return false;
    }

 }


 public function changePassword($sp_email, $sp_password){


    $hash = $this -> getHash($sp_password);
    $sp_password = $hash["encrypted"];
    $salt = $hash["salt"];

    $sql = 'UPDATE shipper SET sp_password = :sp_password, salt = :salt WHERE sp_email = :sp_email';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array(':sp_email' => $sp_email, ':sp_password' => $sp_password, ':salt' => $salt));

    if ($query) {
        
        return true;

    } else {

        return false;

    }

 }

 public function checkUserExist($sp_email){

    $sql = 'SELECT COUNT(*) from shipper WHERE sp_email =:sp_email';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array('sp_email' => $sp_email));

    if($query){

        $row_count = $query -> fetchColumn();

        if ($row_count == 0){

            return false;

        } else {

            return true;

        }
    } else {

        return false;
    }
 }

 public function getHash($sp_password) {

     $salt = sha1(rand());
     $salt = substr($salt, 0, 10);
     $encrypted = password_hash($sp_password.$salt, PASSWORD_DEFAULT);
     $hash = array("salt" => $salt, "encrypted" => $encrypted);

     return $hash;

}



public function verifyHash($sp_password, $hash) {

    return password_verify ($sp_password, $hash);
}
}




