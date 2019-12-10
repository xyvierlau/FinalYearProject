<?php 
session_start();
include_once 'cfg.inc.php';
if(isset($_SESSION['u_name'])){
	$username = $_SESSION['u_name'];
	$userid = $_SESSION['u_id'];
	$role= $_SESSION['user_role'];
	$waitingsyn = "select queue, q_id, branch_id, qserv_type, dept_name from jqueue where qserv_status = 'Waiting' AND user_id = '$userid'";
	$querysyn = mysqli_query($conn, $waitingsyn);
	$waitass = mysqli_fetch_all($querysyn, MYSQLI_ASSOC); 
	 $role = $_SESSION['u_role'];
}
else{
	header("Location: /loginandregister/login.php"); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title> Blockchain Queue System </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="jquery-3.4.1.js"></script>
<script src="coursetro-eth/node_modules/web3/dist/web3.min.js"></script>
<script type="text/javascript" src="ethnodeconn.js"></script> <!--conn to ethnode function file-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
  color: gray;
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

::-webkit-selection {
  color: #fff;
  background: #66D37E;
}

::-moz-selection {
  color: #fff;
  background: #66D37E;
}

::selection {
  color: #fff;
  background: #66D37E;
}
.btn {
  background-color: DodgerBlue;
  border: none;
  color: white;
  padding: 12px 16px;
  font-size: 16px;
  cursor: pointer;
}
.btn:hover {
  background-color: RoyalBlue;
  border-style: solid;
  border-color: black;
}
</style>
</head>


<body>
	<div class="wallet-container text-center" >
		<span style="font-size:20px">Your Queue:</span>

		<span id='userq' style="font-size:20px;font-color:black;font-weight:bold;"></span>
		<p>&nbsp;</p>
		<span style="font-size:20px">Current: </span>
		<span id='curreQ' style="font-size:20px;font-color:black;font-weight:bold;"></span>
<p>&nbsp;</p>
	<p id='brn' style="font-size:20px;float:right"></p>
	<p>&nbsp;</p>
	<p id='svc' style="font-size:20px;float:right"></p>
	<p>&nbsp;</p>
	<button type="button" class="btn" id="cancelQ" style="margin: 20px 0;border-radius: 20px;	font-size: 18px;"> Cancel Queue</button>
<div class="txn-history">
	<p style="font-size:22px">Your Queues : </p>
	<form id='Qchoice'>
	<select name="qchoice" id="qchoice" style="font-size: 3vw;font-family: 'Oxygen', sans-serif;">
	<?php
			echo "<option disabled selected value> -- select an option -- </option>";
		for($i=0;$i<sizeof($waitass);$i++){
			echo "<option value='" . $waitass[$i]['queue'] . "'>" .  $waitass[$i]['dept_name'] . "[" . $waitass[$i]['qserv_type'] . "]".$waitass[$i]['q_id'] ." </option>";
			
		}
		?>
	</select>
	<input type="submit" value="Choose" class='btn' style="border-style:solid;border-color:black;">
</form>
</div>

<div class="row text-center" >
	<button class="btn" onclick="location.href='history.php';" style="width:30%;float:left;">&nbsp;
		<i class="fa fa-bars"></i>
	History
	</button>

	<button class="btn" onclick="location.href='createQ.php';" style="width:30%;float:left">&nbsp;
		<i class="fa fa-folder"></i>
	Create
	</button>

	<button class="btn" onclick="location.href='logout.php';" style="width:30%;float:left;">&nbsp;
		<i class="fa fa-close"></i>
	LogOut
	</button>
</div>

</div>
</body>


<script>
//FUNCTIONS
//VARIABLES
var ctradd;
var Role_Address;
var branchname;
var brservtype;
var canqid;
//DOCUMENT READYS 
$(document).ready(function(){	
	$("#Qchoice").submit(function(e){
    	e.preventDefault();
		var u_role = '<?php echo $role; ?>';
		$.ajax({
		    type: "post",
		    url: 'cwajax.php',
		    async:false,
		    data: {u_role: u_role},
		    success: function(roleadd){
		        Role_Address = roleadd;		    
		    }});
    	buttval = $('#qchoice').val();
    	$.ajax({
    		url:'cwajax.php',
    		method: 'post',
    		data: {qval: buttval
    		},
    		success:function(data){
    			k = data;
    			ctradd = JSON.parse(k)['ctradd'];
    			canqid = JSON.parse(k)['qid'];
    			branchname = JSON.parse(k)['deptname'];
    			brservtype = JSON.parse(k)['servtype'];
    			}  
    	});
document.getElementById("brn").innerHTML = branchname;
document.getElementById("svc").innerHTML = brservtype;
		setWeb3(Role_Address,ctradd);		
    	function DisplayQ(){
    		getCQ();
			var currentQ = getCQ();
			console.log('currentQ' + currentQ);	
			document.getElementById("curreQ").innerHTML = CurrQa;
			
		}
		setTimeout(function(){$("#qchoice").submit();},5000);
		

		window.setInterval(DisplayQ(),5000);
		document.getElementById("userq").innerHTML = canqid;
		var notiQ = canqid - CurrQa;
		if(notiQ ==5){
			console.log('Your are 5 queues away!');}
		else if(notiQ ==1){
			console.log('You are Next!');}
		else if(notiQ ==0){
			console.log("It's your turn!");}
		
    });

	$("#cancelQ").click(function(){
		var canceltxn = qs.cancelQueue(canqid);
		alert('Your Queue at '+ '['+ canqid +'] ' + 'is cancelled!');
		$.ajax({
			url:'cwajax.php',
			method: 'post',
			data: {cancelqueue: buttval, canceltxn:canceltxn}
		});

	});
});


</script>

</html>