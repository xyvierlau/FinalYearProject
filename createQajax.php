<?php        
session_start();
date_default_timezone_set("Asia/Singapore");
include_once ('cfg.inc.php');   

if(isset($_POST['u_role'])){
    $role = $_POST['u_role'];
    
    $role_add = mysqli_query($conn, "SELECT role_address from role WHERE user_role = '$role'");
    $row = mysqli_fetch_assoc($role_add);
    echo $row['role_address'];
}

if(isset($_POST['state'])){
    $state = $_POST['state'];
    $deptname = $_POST['deptname'];
    $fctradd = "SELECT contract_address from department where dept_state='$state' AND dept_name = '$deptname'";
    $ContractAddress = mysqli_query($conn,$fctradd);
    $row = mysqli_fetch_assoc($ContractAddress);
    echo $row['contract_address'];
}

if(isset($_POST['deptst']) && (isset($_POST['deptname']))){
    $deptst = $_POST['deptst'];
    $deptname = $_POST['deptname'];
    $queryservices = "SELECT serv_desc FROM service where serv_branch = (SELECT dept_id from department WHERE dept_name = '$deptname' AND dept_state = '$deptst')";
    $quservices = mysqli_query($conn, $queryservices);
    $result = array();
    echo '<select name="services" id="services" class="form-control" style="font-size: 3vw">';
    echo '<option disabled selected value> -- select an option -- </option>';
while ($row = $quservices->fetch_assoc()){
    echo '<option value="' . $row['serv_desc'] . '">' . $row['serv_desc'] . '</option>';
}
}

if (isset($_SESSION["u_id"])){
        $uid = $_SESSION['u_id'];
        $uname = $_SESSION['u_name'];
        $u_eth_add = $_SESSION['u_ethadd'];
        $u_role = $_SESSION['u_role'];
        if(isset($_POST["brdept"]))
        {
                $brdept = $_POST["brdept"];
                $brst = $_POST["brst"];
                $branchids = mysqli_query($conn, "SELECT dept_id from department WHERE dept_name = '$brdept' AND dept_state = '$brst';");
                $branchid = json_encode($branchids);
                $serv_type = $_POST["servtype"];
                $crehash = $_POST["crehash"];
                $queueNum = $_POST["queueNum"];
                $mysqltime = date ("Y-m-d H:i:s", time()); 
                $insertsql = "INSERT into jqueue(q_id, branch_id, q_creator, user_id, user_name, qserv_type, qserv_status, qcre_hash, qcre_time, q_creator_address, dept_name) VALUES ('$queueNum', (SELECT dept_id from department WHERE dept_name = '$brdept' AND dept_state = '$brst'),'$u_role','$uid', '$uname', '$serv_type', 'Waiting', '$crehash', '$mysqltime', '$u_eth_add', '$brdept')";    
  
                mysqli_query($conn, $insertsql);
                 exit;
        }
       


}
