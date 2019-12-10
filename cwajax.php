<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include_once 'cfg.inc.php';
if(isset($_POST['cancelqueue'])){
	$cancelq = $_POST['cancelqueue'];
	$canceltxn =$_POST['canceltxn'];
	$mysqltime = date ("Y-m-d H:i:s", time()); 
	$cancelsyn = "UPDATE jqueue SET qserv_status = 'Cancelled', qcancel_time= '$mysqltime', qcancel_hash = '$canceltxn' WHERE queue='$cancelq'";
	mysqli_query($conn, $cancelsyn);
}

if(isset($_POST['qval'])){
	$qval = $_POST['qval'];
	$syn = "SELECT branch_id, qserv_type, dept_name from jqueue where queue = '$qval'";
	$brid = mysqli_query($conn, $syn);
	$brids = mysqli_fetch_assoc($brid);
	$bridstr = $brids['branch_id'];
	$deptname = $brids['dept_name'];
	$servtype = $brids['qserv_type'];

	$qidsyn = "SELECT q_id from jqueue where queue = '$qval'";
	$qidsy = mysqli_query($conn, $qidsyn);
	$qids = mysqli_fetch_assoc($qidsy);
	$qid = $qids['q_id'];

	$ctrsyn = "SELECT contract_address from department where dept_id = '$bridstr'";
	$ctra = mysqli_query($conn, $ctrsyn);
	$ctrad = mysqli_fetch_assoc($ctra);
	$ctradd = $ctrad['contract_address'];
	$result = array();
	$result['ctradd'] = $ctradd;
	$result['qid'] = $qid;
	$result['deptname'] = $deptname;
	$result['servtype'] = $servtype;
	echo json_encode($result);
}

if(isset($_POST['u_role'])){
	$role = $_POST['u_role'];
	
	$role_add = mysqli_query($conn, "SELECT role_address from role WHERE user_role = '$role'");
	$row = mysqli_fetch_assoc($role_add);
	echo $row['role_address'];
}