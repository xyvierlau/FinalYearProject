<?php
	include_once 'cfg.inc.php';
  date_default_timezone_set("Asia/Singapore");
  $mysqltime = date ("Y-m-d H:i:s", time()); 
	if(isset($_POST['smsbranch'])){
		$branch = $_POST['smsbranch'];
		$totq = $_POST['totq'];
		$curq = $_POST['curq'];
		$remindq = $curq+5;
		$checkqexist = mysqli_query($conn, "select qserv_status from jqueue where q_id = '$remindq' AND branch_id = '$branch'");
		$checkqrow = mysqli_fetch_row($checkqexist);

		if(implode(null,$checkqrow) == null){
			echo "error";
		}

		else{
	  $hpsyn = "SELECT user_phone from users where user_id in (SELECT user_id from jqueue where q_id ='$remindq' AND branch_id = '$branch')";
      $hpnum = mysqli_query($conn, $hpsyn);
      $hpnumval = mysqli_fetch_assoc($hpnum);
      $hpnumF = $hpnumval['user_phone'];


      $unsyn = "SELECT user_name from users where user_phone= '$hpnumF'";
      $username = mysqli_query($conn, $unsyn);
      $usernameval = mysqli_fetch_assoc($username);
      $usernameF = $usernameval['user_name'];

      $brsyn = "SELECT dept_name, dept_state FROM department where dept_id = '$branch'";
      $brdetails = mysqli_query($conn, $brsyn);
      $brval = mysqli_fetch_assoc($brdetails);
      $brnF = $brval['dept_name'];
      $brstF = $brval['dept_state'];
      $recordsvc = "INSERT INTO serviceinfo(sv_action, sv_info, sv_time) values ('SendSMS','receipient: $usernameF hpnum: $hpnumF','$mysqltime')";
      mysqli_query($conn, $recordsvc);
      sendSMS($hpnumF, $usernameF, $brnF, $brstF);

		}
		}

     function ismscURL($link){

      $http = curl_init($link);

      curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
      $http_result = curl_exec($http);
      $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
      curl_close($http);

      return $http_result;
     }

     function sendSMS($dest, $_usernameF, $_brnF, $_brstF){

      $destination = $dest;
      $usernameF = $_usernameF;
      $brnF = $_brnF;
      $brstF = $_brstF;
      $message = "Dear '$usernameF', you are 5 queues away for your service at '$brnF', '$brstF'. Please be ready! ";
      $message = html_entity_decode($message, ENT_QUOTES, 'utf-8'); 
      $message = urlencode($message);
      
      $username = "xyvier";
      $password = "950916160995";
      $sender_id = ("66300");
      $type = (int)'1';

      $fp = "https://www.isms.com.my/isms_send.php"; //UNLOCK TO REALLY SEND SMS
      $fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id&agreedterm=YES";
      
      $result = ismscURL($fp);
      echo $result;
     }

     ?>
