<?php 
session_start();
if(isset($_SESSION['u_role'])){
	if($_SESSION['u_role'] == 'client'){
header("Location: /loginandregister/login.php"); //kick non admin
exit();
}
}
else{
	header("Location: /loginandregister/login.php"); 
}
include ('cfg.inc.php');
if(isset($_SESSION['u_id']))
{ 
    $uid = $_SESSION['u_id'];
    $uname = $_SESSION['u_name'];
    $role = $_SESSION['u_role'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<button class ='btn'style="float:right;width:150px" id="newDay" name="newDay">New Day</button>

	<label for="Branch" class="col-lg-2 control-label" style="font-size: 35px">Service Desk</label>
      		<form id="branchche">
      		<select name="branchch" class="form-control" id="branchch" style="padding:5px;border:solid 1px #a6a6a6;font-family:CalibriRegular,sans-serif">
        		<?php
					$redeptstate = mysqli_query($conn, "Select * from department");
					echo "<option disabled selected value> -- select an option -- </option>";		 
		        	while ($rows = $redeptstate->fetch_assoc()){
		        		echo "<option style='font-size:20px;width:350px;' value='" . $rows['dept_id'] . "'>" . $rows['dept_name'] ."| ". $rows['dept_state'] . "</option>";
		        }
		        ?>
		       </select>
		       <input type="submit" value="submit" style="width:300px" class='btn'>
		    </form>

	<title> Service Desk</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script src="jquery-3.4.1.js"></script>
    <script src="coursetro-eth/node_modules/web3/dist/web3.min.js"></script>
    <script type="text/javascript" src="ethnodeconn.js"></script> <!--conn to ethnode function file-->
    <script>
$(document).ready(function(){
	var Role_Address;
    var u_role = '<?php echo $role; ?>';
    $.ajax({
        type: "post",
        url: 'deskajax.php',
        async:false,
        data: {u_role: u_role},
        success: function(roleadd){
            Role_Address = roleadd;
        
        }});

	var Cont_Address;

	$("#branchche").submit(function(e){
		e.preventDefault();
		var branchid = $("#branchch").val();
		var tes;
		$.ajax({
			url: 'deskajax.php',
			method: 'post',
			data:{ branchid : branchid},
			success:function(data){
				Cont_Address = data;
			}
		});		
		setWeb3(Role_Address, Cont_Address);
///Function to display
		function getTQCQ(){
			getTQ();
			getCQ();
			var tqtq = getTQ();
			var cqcq = getCQ();
			console.log(tqtq);
			console.log(cqcq);
			document.getElementById("TotalQ").innerHTML = tqtq;
			document.getElementById("CurrQ").innerHTML = cqcq;
		}
		setTimeout(function(){$("#branchche").submit();},8000);

		window.setInterval(getTQCQ(),5000);
	
 
    });

});
   
</script>
<style>
@media screen and (max-width: 800px) {
.left, .main, .right {
width: 100%; /* The width is 100%, when the viewport is 800px or smaller */
  }
  .btn {
  background-color: DodgerBlue;
  border: none;
  color: white;
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
  float:left;
}
.btn:hover {
  background-color: RoyalBlue;
  border-style: solid;
  border-color: black;
}
}
body {
  font-family: "Oxygen", sans-serif;
  font-weight: 300;
  font-size: 14px;
  line-height: 1.7;
  color: black;
  background: #e8e8e8;
}

#page {
  position: relative;
  overflow-x: hidden;
  width: 100%;
  height: 100%;
  -webkit-transition: 0.5s;
  -o-transition: 0.5s;
  transition: 0.5s;
}
.offcanvas #page {
  overflow: hidden;
  position: absolute;
}
.offcanvas #page:after {
  -webkit-transition: 2s;
  -o-transition: 2s;
  transition: 2s;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 101;
  background: rgba(0, 0, 0, 0.7);
  content: "";
}
h1, h2, h3, h4, h5, h6, figure {
  color: #000;
  font-family: "Source Sans Pro", sans-serif;
  font-weight: 400;
  margin: 0 0 20px 0;
}

.btn {
  background-color: DodgerBlue;
  border: none;
  color: white;
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
  width:30%;
}
.btn:hover {
  background-color: RoyalBlue;
  border-style: solid;
  border-color: black;
  width:30%;
}
</style>
</head>
<body>
	<p id="holder">&nbsp;</p>
	<h2 style="text-align:center">Current Queue</h2>
	<div  style="background-color:lightblue;width: 450px;margin:auto;border:2px solid #73AD21;max-height:80px;"> <p id="CurrQ" style="text-align:center;font-size:25px; font-color: black"></p></div>
	<h2 style="text-align:center">Total Queue</h2>
	<div style="background-color:orange;width: 450px;margin:auto;border:2px solid #73AD21;max-height:80px"> <p style="text-align:center;font-size:25px; font-color: black" id="TotalQ"></p>
	</div>

<div id="contt" style="background:lightblue;float:left;width: 230px;border: 1px solid #3366FF;">
	<h4 style="height:5px">Process Queue</h4>
<form id="FinishQueue" method="post" >
	<input id="svcinfo" name="svcinfo" style="width:230px">
	<br>
	<input type="submit" style="background-color:lightgreen;width:230px" id="FinSer" value="Submit Service Info">
</form>
</div>
	<div style="background-color:lightgreen;float:left;width:230px;height:155px;border: 1px solid #3366FF;">
		<button style="background-color:#00FFFF;width:230px;" id="nextQ" name="nextQ">Next Queue</button>
		<p style="bottom:20px">Send SMS <input type="checkbox" id="myCheck" ></p>
	</div>
	</div>

<p id="holder">&nbsp;</p>
<p id="holder">&nbsp;</p>
<p id="holder">&nbsp;</p>
<p id="holder">&nbsp;</p>
<p id="holder">&nbsp;</p>
<p id="holder">&nbsp;</p>

<div style="width:450px;float:left;position:relative;bottom:60px;left:40px;background:#DEB887;border: 2px solid #505050;">
	<h2>Queue Info</h2>
	<div id="first" style="width:200px;float:left;height:250px">
<form id="getQInfo" method='post'>

	<input id="getQInfotext" name="getQInfotext" style="width:200px">
	<input type="submit" id="getQueueInfo" value="getQueueInfo" style="width:200px">
</form>
</div>

<div id="second" style="float:left;width:200px;height:auto;">

		<span style="color:#808080">Queue Number: </span><span style="font-weight:bold;font-size:20px;" id="queue"></span><br>
		<span style="color:#808080">Status: </span><span style="font-weight:bold;font-size:20px;"id="questatus"></span><br>
		<span style="color:#808080">Branch: </span><span style="font-weight:bold;font-size:20px;"id="quebr"></span><br>
		<span style="color:#808080">User ID: </span><span style="font-weight:bold;font-size:20px;"id="queuid"></span><br>
		<span style="color:#808080">User Name: </span><span style="font-weight:bold;font-size:20px;"id="queuname"></span><br>
		<span style="color:#808080">Service: </span><span style="font-weight:bold;font-size:20px;"id="quesvc"></span><br>
		<span style="color:#808080">Service Notes: </span><span style="font-weight:bold;font-size:20px;"id="queinfo"></span>
		</div>	
</div>
<a href="logout.php"><button class="btn" style="float:right;bottom:10px">Log Out</button></a>
 <script>

 $(document).ready(function(){
 	$("#getQInfo").submit(function(e){
 		e.preventDefault();
		var inputNum = $('#getQInfotext').val();
		var reqinfo = qs.getQueueInfo(inputNum);
		var queue = reqinfo[0].toString();
		var questat = reqinfo[1].toString();
		switch (questat){
			case '0':
			var questatus = "Waiting";
			break;
			case '1':
			var questatus = "Processing";
			break;
			case '2':
			var questatus = "Completed";
			break;
			case '3':
			var questatus = "Cancelled";
			break;
			case '4':
			var questatus = "Edited";
			break;
		}
		var quebr = reqinfo[2].toString();
		var queuid = reqinfo[3];
		var queuname = reqinfo[4];
		var quesvc = reqinfo[5];
		var queinfo = reqinfo[6];
		document.getElementById('queue').innerHTML = queue;
		document.getElementById('questatus').innerHTML = questatus;
		document.getElementById('quebr').innerHTML = quebr;
		document.getElementById('queuid').innerHTML =queuid;
		document.getElementById('queuname').innerHTML =queuname;
		document.getElementById('quesvc').innerHTML =quesvc;
		document.getElementById('queinfo').innerHTML =queinfo;
	});
 	
	
	$('#nextQ').click(function(e){
		e.preventDefault();
		var nxqhash = qs.nextQueue();
		var CurrQa;
		getCQ();
		var checkBox = document.getElementById("myCheck");
		var smsuname;
		$.ajax({
			url:'deskajax.php',
			method:'post',
			data:{
				nxqueue : nxqhash,
			}
		});

		//SMS CODE BELOW, url modified to prevent sending while testing, 5 SMS left
		if (checkBox.checked == true){
			var TotalQu = $('#TotalQ').text();
			var CurreQu = $('#CurrQ').text();
			var smsbranch = $("#branchch").val();
		$.ajax({
			url: 'smsapi.php',
			method: 'post',
			data: {totq: TotalQu, curq: CurreQu, smsbranch : smsbranch},
			success: function(){
				alert('SMS sent to -5 queue!');
			}
		});
		}
		});

	$('#FinishQueue').submit(function(e){
		e.preventDefault();
		var info = $('#svcinfo').val();
		var servhash = qs.finishQueue(info);
		var thequesss = $('#CurrQ').text();
		var finbranch = $("#branchch").val();
		$.ajax({
			url:'deskajax.php',
			method:'post',
			data:{
				finqueNum : thequesss,
				finqueinfos : info,
				servhash : servhash,
				finbranch: finbranch
			}
		});
		$.ajax({
			url:'deskajax.php',
			method:'post',
			data:{
				finqueinfo : info,
				servhash : servhash,
			}
		});

	});
	$('#newDay').click(function(e){
		e.preventDefault();
		var newdayID = qs.newDay();
		alert('newDay assigned');
		$.ajax({
			url:'deskajax.php',
			method: 'post',
			data: {newdayid : newdayID}
		});

	});
 });
</script>
</body>
</html>
