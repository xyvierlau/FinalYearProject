<?php 
session_start();
include ('cfg.inc.php'); 
if(isset($_SESSION['u_id']))
{
    $uid = $_SESSION['u_id'];
    date_default_timezone_set("Asia/Singapore");
    $uname = $_SESSION['u_name'];
    $mysqltime = date ("Y-m-d H:i:s", time()); 
     $role = $_SESSION['u_role'];
     echo "<p style='float:right'> Hello " . $uname . "!</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="jquery-3.4.1.js"></script>
    <script src="coursetro-eth/node_modules/web3/dist/web3.min.js"></script>
    <script type="text/javascript" src="ethnodeconn.js"></script> <!--conn to ethnode function file-->
    <script type="text/javascript">
    $(document).ready(function(){
    	$("#deptname").change(function(){//populating Select drop down list
    		var dept_st = $("#state").val();
    		var dept_name = $("#deptname").val();
    		$.ajax({
    			url: 'createQajax.php',
    			method: 'post',
    			data: {deptst : dept_st, deptname : dept_name}
    		}).done(function(services){
    			var save = services;
    			console.log(save);

			document.getElementById("storeop").innerHTML = save;



    	});
    });
    });
</script>
    <title>Queue</title>
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
  width:33%;
  float:left;
}
.btn:hover {
  background-color: RoyalBlue;
  border-style: solid;
  border-color: black;
  width:33%;float:left;
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
<button class="btn" onclick="location.href='clientwallet.php';">Back</button>
<p>&nbsp;</p>
<p>&nbsp;</p>
    
    <form id="createQueue" method="post">
        <h1>Queue Creation</h1>
        <label for="state" class="col-lg-2 control-label" >State</label>
      		<select name="state" class="form-control" id="state" style="font-size:3vw;">
                <option disabled selected value> -- select an option -- </option>
        		<?php
					$redeptstate = mysqli_query($conn, "Select distinct dept_state from department");		 
		        	while ($rows = $redeptstate->fetch_assoc()){
		        		echo "<option value='" . $rows['dept_state'] . "'>" . $rows['dept_state'] . "</option>";
		        }
		        ?>
		       </select>        	
               <p>&nbsp;</p>
        <label for="branchid" class="col-lg-2 control-label">Department Name</label>
        	<select name="deptname" class="form-control" id="deptname"  style="font-size:3vw;">
                <option disabled selected value> -- select an option -- </option>
        		<?php
  					$redeptname = mysqli_query($conn, "Select distinct dept_name from department");
		            while ($rown = $redeptname->fetch_assoc()) {
		                 echo "<option value='" . $rown['dept_name'] . "'>" . $rown['dept_name'] . "</option>";
		                    		}
        	?>
        	</select>
            <p>&nbsp;</p>
        <label for="servicetype" class="col-lg-2 control-label">Service</label>
         <div id='storeop'>
         </select>
         <p>&nbsp;</p>
     </div>

        <input type="submit" id="CreateBtn" value="Create!" class="btn"/> <p id="message"></p>
    </form>
    <div style="position:relative">
        <a href="logout.php"><button class="btn" style="float:right">Log Out</button></a>
    </div>
    <script>
var Contract_Address;
var tes;
var Role_Address;
var clientQueue;
var txnid;
    $(document).ready(function(){
        $("#createQueue").submit(function(e){
            e.preventDefault();
            var u_id = '<?php echo $uid; ?>';
            var u_name = '<?php echo $uname; ?>';
            var u_role = '<?php echo $role; ?>';
            var br_st = $("#state").val();
            $.ajax({
                type: "POST",
                url: 'createQajax.php',
                async: false,
                dataType: "text",
                data: {deptname: $("#deptname").val(),state:br_st},
                success: function(ctradd){
                    tes = ctradd.replace(/[^a-zA-Z0-9 ]/g, "");
                    Contract_Address = tes;
                }});

            $.ajax({
                type: "post",
                url: 'createQajax.php',
                async:false,
                data: {u_role: u_role},
                success: function(roleadd){
                    Role_Address = roleadd;
                
                }});
            setWeb3(Role_Address, Contract_Address);//set web3 to be used, alias: qs
            txnid = qs.createQueue($("#deptname").val(), u_id, u_name, $("#services").val());
            waitTxn(txnid);//check transaction finished and get total queue
            var TotalQa = getTQ();//get total queue number as the user's queue number
            alert('Your Queue is: '+ TotalQa);
            document.getElementById('message').innerHTML = "Queue is created! Number: " + TotalQa;
            var br_st = $("#state").val();
            var br_dept = $("#deptname").val();
            var s_type = $("#services").val();
            $.ajax({
                type: "POST",
                url: 'createQajax.php',
                data:{ brst : br_st, brdept: br_dept, servtype: s_type, crehash: txnid, queueNum: TotalQa}})
        });});


    </script>

</body>
</html>
