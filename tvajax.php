<?php
include_once 'cfg.inc.php';
if(isset($_POST['branchid'])){//deskchoosenode.php
	$branchid = $_POST['branchid'];
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