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

	<h1 id="Branch" class="col-lg-2 control-label" style="font-size: 35px;align-content: center"></h1>
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
        url: 'tvajax.php',
        async:false,
        data: {u_role: u_role},
        success: function(roleadd){
            Role_Address = roleadd;
        
        }});

	var Cont_Address;

	$("#branchche").submit(function(e){

		e.preventDefault();
		var branchid = $("#branchch").val();
		document.getElementById('branchche').style.display = 'none';
		document.getElementById('Branch').innerHTML = branchid;
		var tes;
		$.ajax({
			url: 'tvajax.php',
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
	body {
  background-color: lightblue;
}
</style>
<body>
	<h2 style="text-align:center">Current Queue</h2>
	<div  style="background-color:lightblue;width: auto;margin:auto;border:2px solid #73AD21;height:auto;"> <p id="CurrQ" style="text-align:center;font-size:3vw; font-color: black"></p></div>
	<h2 style="text-align:center">Total Queue</h2>
	<div style="background-color:orange;width: auto;margin:auto;border:2px solid #73AD21;max-height:auto;"> <p style="text-align:center;font-size:3vw; font-color: black" id="TotalQ"></p>
	</div>
</body>
