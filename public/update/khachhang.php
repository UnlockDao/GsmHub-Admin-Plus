<?php

include 'DatabaseConfig.php' ;
 
 $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);
 
 $phone = $_POST['phone'];
 
 if($phone != ''){
	 $date =  date("Y-m-d H:i:s");
   $Sql_Query = "insert into khachhang (phone,created_at) values ('$phone','$date')";
	 
	 
 }
 
	
   
 
 if(mysqli_query($con,$Sql_Query)){
 echo 'Data Submit Successfully';
 }
 else{
 
 echo 'Try Again';
 
 }
 
 mysqli_close($con);
?>