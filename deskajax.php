<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include_once 'cfg.inc.php';
			 $mysqltime = date ("Y-m-d H:i:s", time()); 
		if(isset($_POST['finqueNum'])){//FINISHQ.PHP
			$finqueinfo = $_POST['finqueinfos'];
			$servhash = $_POST['servhash'];
			$finqueNum = $_POST['finqueNum'];
			$finbranch = $_POST['finbranch'];
			$fininsert = "UPDATE jqueue SET qserv_info='$finqueinfo', qserv_time= '$mysqltime', qserv_hash ='$servhash',qserv_status = 'Completed' where q_id = '$finqueNum' AND branch_id = '$finbranch'";
			$finstatus = "UPDATE jqueue set qserv_status = 'Completed' where q_id = '$finqueNum'";
			mysqli_query($conn, $fininsert);
			mysqli_query($conn, $finstatus);
			exit;
		}

		if(isset($_POST["finqueinfo"]))//svinfoinsertdb.php
        {
                $svcinfo = $_POST["finqueinfo"];
                $svchash = $_POST["servhash"];
                
                $action = 'FinishQueue';
                 
                $insertsql = "INSERT into serviceinfo( sv_action, sv_info, sv_hash, sv_time) VALUES ('$action', '$svcinfo', '$svchash', '$mysqltime');";    
                mysqli_query($conn, $insertsql);
                 exit;
        }

         if(isset($_POST["nxqueue"]))
        {
                $action = 'NextQueue';
                $nxhash = $_POST["nxqueue"];

                $insertsql = "INSERT into serviceinfo (sv_action, sv_hash, sv_time) VALUES('$action', '$nxhash', '$mysqltime');";    
                mysqli_query($conn, $insertsql);
                 exit;
        }
        if(isset($_POST["newdayid"]))
        {
                $action = 'NewDay';
                $ndhash = $_POST["newdayid"];
                $insertsql = "INSERT into serviceinfo (sv_action, sv_hash, sv_time) VALUES('$action', '$nxhash', '$mysqltime');";    
                mysqli_query($conn, $insertsql);
                 exit;
        }

		if(isset($_POST['branchid'])){//deskchoosenode.php
			$branchid = $_POST['branchid'];
				include_once 'cfg.inc.php';
				$getAddsyn = "Select contract_address from department where dept_id = '$branchid';";
				$getAdd = mysqli_query($conn, $getAddsyn);
				$returnAdd = mysqli_fetch_assoc($getAdd);
				echo $returnAdd['contract_address'];
		}

		if(isset($_POST['u_role'])){//getroleadd.php
			$role = $_POST['u_role'];
			
			$role_add = mysqli_query($conn, "SELECT role_address from role WHERE user_role = '$role'");
			$row = mysqli_fetch_assoc($role_add);
			echo $row['role_address'];
		}

		