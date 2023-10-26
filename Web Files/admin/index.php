<?php
	require_once('auth.php');
?>
<?php
//checking connection and connecting to a database
require_once('connection/config.php');
//Connect to mysql server
$conn = new mysqli($servername, $username, $password, $database);
if(!$conn) {
    die('Failed to connect to server: ' . $conn->error);
}
    //define default value for flag
    $flag_1 = 1;

    //defining global variables
    $qry="";
    $excellent_qry="";
    $good_qry="";
    $average_qry="";
    $bad_qry="";
    $worse_qry="";

//count the number of records in the members, orders_details, and reservations_details tables
$members=$conn->query("SELECT * FROM members")
or die("There are no records to count ... \n" . mysqli_error());

$orders_placed=$conn->query("SELECT * FROM orders_details")
or die("There are no records to count ... \n" . mysqli_error());

$orders_processed=$conn->query("SELECT * FROM orders_details WHERE flag='$flag_1'")
or die("There are no records to count ... \n" . mysqli_error());

$tables_reserved=$conn->query("SELECT * FROM reservations_details WHERE table_flag='$flag_1'")
or die("There are no records to count ... \n" . mysqli_error());

$partyhalls_reserved=$conn->query("SELECT * FROM reservations_details WHERE partyhall_flag='$flag_1'")
or die("There are no records to count ... \n" . mysqli_error());

$tables_allocated=$conn->query("SELECT * FROM reservations_details WHERE flag='$flag_1' AND table_flag='$flag_1'")
or die("There are no records to count ... \n" . mysqli_error());

$partyhalls_allocated=$conn->query("SELECT * FROM reservations_details WHERE flag='$flag_1' AND partyhall_flag='$flag_1'")
or die("There are no records to count ... \n" . mysqli_error());

//get food names and ids from food_details table
$foods=$conn->query("SELECT * FROM food_details")
or die("Something is wrong ... \n" . mysqli_error());
?>
<?php
    if(isset($_POST['Submit'])){
        //Function to sanitize values received from the form. Prevents SQL injection
        function clean($str) {
            $str = trim($str);
            
            // Check if the $conn variable is a valid MySQLi connection.
            if ($conn && is_object($conn)) {
                $str = mysqli_real_escape_string($conn, $str);
            } else {
                // Handle the case where $conn is not a valid connection.
                // You should open a MySQL connection here and use it for escaping.
            }
            
            return $str;
        }
        //get category id
        $id = clean($_POST['food']);

        //get ratings ids
        $ratings=$conn->query("SELECT * FROM ratings")
        or die("Something is wrong ... \n" . mysqli_error());
        $row_1=mysqli_fetch_array($ratings);
        $row_2=mysqli_fetch_array($ratings);
        $row_3=mysqli_fetch_array($ratings);
        $row_4=mysqli_fetch_array($ratings);
        $row_5=mysqli_fetch_array($ratings);
        if($row_1){
            $excellent=$row_1['rate_id'];
        }
        if($row_2){
            $good=$row_2['rate_id'];
        }
        if($row_3){
            $average=$row_3['rate_id'];
        }
        if($row_4){
            $bad=$row_4['rate_id'];
        }
        if($row_5){
            $worse=$row_5['rate_id'];
        }

        //selecting all records from the food_details and polls_details tables based on food id. Return an error if there are no records in the table
        $qry=$conn->query("SELECT * FROM food_details, polls_details WHERE polls_details.food_id='$id' AND food_details.food_id='$id'")
        or die("Something is wrong ... \n" . mysqli_error());

        $excellent_qry=$conn->query("SELECT * FROM food_details, polls_details WHERE polls_details.food_id='$id' AND food_details.food_id='$id' AND polls_details.rate_id='$excellent'")
        or die("Something is wrong ... \n" . mysqli_error());

        $good_qry=$conn->query("SELECT * FROM food_details, polls_details WHERE polls_details.food_id='$id' AND food_details.food_id='$id' AND polls_details.rate_id='$good'")
        or die("Something is wrong ... \n" . mysqli_error());

        $average_qry=$conn->query("SELECT * FROM food_details, polls_details WHERE polls_details.food_id='$id' AND food_details.food_id='$id' AND polls_details.rate_id='$average'")
        or die("Something is wrong ... \n" . mysqli_error());

        $bad_qry=$conn->query("SELECT * FROM food_details, polls_details WHERE polls_details.food_id='$id' AND food_details.food_id='$id' AND polls_details.rate_id='$bad'")
        or die("Something is wrong ... \n" . mysqli_error());

        $worse_qry=$conn->query("SELECT * FROM food_details, polls_details WHERE polls_details.food_id='$id' AND food_details.food_id='$id' AND polls_details.rate_id='$worse'")
        or die("Something is wrong ... \n" . mysqli_error());

        $no_rate_qry=$conn->query("SELECT * FROM food_details WHERE food_id='$id'")
        or die("Something is wrong ... \n" . mysqli_error());
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Index</title>
<link href="stylesheets/admin_styles.css" rel="stylesheet" type="text/css" />
<!--  Including Boostrap and JQuery Files   -->
  <link rel="stylesheet"  href="../Assests/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!---------------------------------------------->
<link rel="stylesheet"  href="../Assests/css/animate_css_stylesheet.css">
<script language="JavaScript" src="validation/admin.js"> </script>
</head>
<body>
	<div class="container" style="margin-top: 10px; padding:0px;">
		<div class="row">
		<h1 id="title">Administrator Control Panel</h1>
		<ul id="navbar" class="nav nav-pills">
      <li><a href="index.php"> <img src="../Assests/admin_icons/13.png" width="55px" height="55px"><br> &nbsp; Home</a></li>
      <li> <a href="profile.php"> <img src="../Assests/admin_icons/2.png" width="55px" height="55px"><br>&nbsp;Profile</a> </li>
      <li> <a href="categories.php"> &nbsp;&nbsp;<img src="../Assests/admin_icons/3.png" width="55px" height="55px"><br>Categories</a> </li>
      <li> <a href="foods.php"> <img src="../Assests/admin_icons/4.png" width="55px" height="55px"><br>Foods</a> </li>
      <li> <a href="accounts.php"> <img src="../Assests/admin_icons/5.png" width="55px" height="55px"><br>Accounts</a> </li>
      <li> <a href="orders.php"> <img src="../Assests/admin_icons/6.png" width="55px" height="55px"><br>&nbsp;Orders</a> </li>
      <li> <a href="reservations.php">&nbsp;&nbsp; <img src="../Assests/admin_icons/7.png" width="55px" height="55px"><br>Reservations</a> </li>
      <li> <a href="specials.php"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../Assests/admin_icons/8.png" width="55px" height="55px"><br>Specials Deals</a> </li>
      <li> <a href="allocation.php"> <img src="../Assests/admin_icons/9.png" width="55px" height="55px"><br>&nbsp;Allocation</a> </li>
      <li> <a href="messages.php"> &nbsp; <img src="../Assests/admin_icons/10.png" width="55px" height="55px"><br>Messages</a> </li>
      <li> <a href="options.php"> <img src="../Assests/admin_icons/1.png" width="55px" height="55px"><br> Options</a> </li>
      <li> <a href="logout.php"> <img src="../Assests/admin_icons/11.png" width="55px" height="55px"><br> Logout</a> </li>
    </ul>
	</div>
		<div class="row" style="background:lightblue;">
			<div class="col-md-6">
				<h3 style="text-align: center;">Statistics</h3> <hr>
				<?php
				        $result1=mysqli_num_rows($members);
				        $result2=mysqli_num_rows($orders_placed);
				        $result3=mysqli_num_rows($orders_processed);
				        $result4=$result2-$result3; //gets pending order(s)
				        $result5=mysqli_num_rows($tables_reserved);
				        $result6=mysqli_num_rows($tables_allocated);
				        $result7=$result5-$result6; //gets pending table(s)
				        $result8=mysqli_num_rows($partyhalls_reserved);
				        $result9=mysqli_num_rows($partyhalls_allocated);
				        $result10=$result8-$result9; //gets pending partyhall(s)
				?>
			 <ul class="list-group">
        <li class="list-group-item">Members Registered <span class="badge"><?php echo $result1; ?></span></li>
        <li class="list-group-item">Orders Placed <span class="badge"><?php echo $result2; ?></span></li>
        <li class="list-group-item">Orders Processed <span class="badge"><?php echo $result3; ?></span></li>
        <li class="list-group-item">Orders Pending <span class="badge"><?php echo $result4; ?></span></li>
        <li class="list-group-item">Table(s) Reserved <span class="badge"><?php echo $result5; ?></span></li>
        <li class="list-group-item">Table(s) Allocated <span class="badge"><?php echo $result6; ?></span></li>
        <li class="list-group-item">Table(s) Pending <span class="badge"><?php echo $result7; ?></span></li>
				<li class="list-group-item">Room(s) Reserved <span class="badge"><?php echo $result8; ?></span></li>
        <li class="list-group-item">Room(s) Allocated <span class="badge"><?php echo $result9; ?></span></li>
        <li class="list-group-item">Room(s) Pending <span class="badge"><?php echo $result10; ?></span></li>
       </ul>
			</div>

			<div class="col-md-6">
				<h3 style="text-align:center;">Customers' Ratings </h3> <hr>
				<form name="foodStatusForm" id="foodStatusForm" method="post" action="index.php" onsubmit="return statusValidate(this)">
				    <table width="360" align="center">
				         <tr>
				            <td>Food</td>
				            <td width="168"><select name="food" id="food" class="form-control">
				            <option value="select">- select food -
				            <?php
				            //loop through food_details table rows
				            while ($row=mysqli_fetch_array($foods)){
				            echo "<option value=$row[food_id]>$row[food_name]";
				            }
				            ?>
									</select></td>
				            <td><input type="submit" name="Submit" value="Show Ratings" class="form-control btn btn-primary" style="border-radius:0px;" /> <br>
										</td>
				          </tr>
				    </table>
				</form>
				<?php
				    if(isset($_POST['Submit'])){
				        //actual values
				        $excellent_value=mysqli_num_rows($excellent_qry);
				        $good_value=mysqli_num_rows($good_qry);
				        $average_value=mysqli_num_rows($average_qry);
				        $bad_value=mysqli_num_rows($bad_qry);
				        $worse_value=mysqli_num_rows($worse_qry);
				        //percentile rates
				        $total_value=mysqli_num_rows($qry);
				        if($total_value != 0){
				            $excellent_rate=$excellent_value/$total_value*100;
				            $good_rate=$good_value/$total_value*100;
				            $average_rate=$average_value/$total_value*100;
				            $bad_rate=$bad_value/$total_value*100;
				            $worse_rate=$worse_value/$total_value*100;
				        }
				        else{
				            $excellent_rate=0;
				            $good_rate=0;
				            $average_rate=0;
				            $bad_rate=0;
				            $worse_rate=0;
				        }
				        //get food name
				        if(mysqli_num_rows($qry)>0){
				            $row=mysqli_fetch_array($qry);
				            $food_name=$row['food_name'];
				        }
				        else{
				            $row=mysqli_fetch_array($no_rate_qry);
				            $food_name=$row['food_name'];
				        }
				    }
				?>
				<br>
				<?php echo "<center><h3>Food Name: ".$food_name."</h3></center>"; ?>
			 <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
           aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $excellent_rate.'%' ?>">
          <?php echo $excellent_value."  (".$excellent_rate."%".")" ; ?> &nbsp;&nbsp; Excellent
         </div>
       </div>

			 <div class="progress">
        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
           aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $good_rate.'%' ?>">
          <?php echo $good_value."  (".$good_rate."%".")" ; ?> &nbsp;&nbsp; Good
         </div>
       </div>

			 <div class="progress">
        <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar"
           aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $average_rate.'%' ?>">
          <?php echo $average_value."  (".$average_rate."%".")" ; ?> &nbsp;&nbsp; Average
         </div>
       </div>

			 <div class="progress">
        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"
           aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $bad_rate.'%' ?>">
          <?php echo $bad_value."  (".$bad_rate."%".")" ; ?> &nbsp;&nbsp; Bad
         </div>
       </div>

			 <div class="progress">
        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"
           aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $worse_rate.'%' ?>">
          <?php echo $worse_value."  (".$average_rate."%".")" ; ?> &nbsp;&nbsp; Worse 
         </div>
       </div>

			</div>
		</div>
	</div>
</body>
</html>
