<?php
session_start();
if(isset($_SESSION['u_id'])){
include_once 'cfg.inc.php';
$uid = $_SESSION['u_id'];

$histsyn = "SELECT q_id, qserv_type, qserv_status, qcre_time, branch_id from jqueue where user_id = '$uid'";
$histynt = mysqli_query($conn,$histsyn);
$histassoc = mysqli_fetch_all($histynt, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: RoyalBlue;
  color: white;
}
.btn {
  background-color:DodgerBlue;
  border: none;
  color: white;
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
}
.btn:hover {
  background-color: #ddd;
  border-style: solid;
  border-color: black;
}
</style>
</head>

<button class="btn" onclick="location.href='clientwallet.php';"><i class="fa fa-close"></i>Back</button>
<body>
<div style="height:960px;width:540px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
<table id="customers">
  <tr>
    <th>Created Date</th>
    <th>Branch</th>
    <th>Service</th>
    <th>Status</th>
    <th>Queue</th>
  </tr>
  <?php 
  for($i=0;$i<sizeof($histassoc);$i++){
  	echo "<tr><td>".$histassoc[$i]['qcre_time']."</td><td>".$histassoc[$i]['branch_id']."</td><td>".$histassoc[$i]['qserv_type']."</td><td>".$histassoc[$i]['qserv_status']."</td><td>".$histassoc[$i]['q_id']."</td></tr>";
  }
  ?> 
</table>
</div>
</body>
</html>
