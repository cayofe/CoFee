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
//selecting all records from the members table. Return an error if there are no records in the tables
$result=$conn->query("SELECT * FROM members")
or die("There are no records to display ... \n" . mysqli_error());
?>
<!DOCTYPE html>
<html>
<head>
<title>Members</title>
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
		 <h2 id="subtitle">Members Management</h2>
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
		<div class="row" style="background:lightblue; padding: 10px 100px;">
			<table class="table table-responsives table-hover table-condensed">
			<h3 style="text-align: center;">Members List</h3>
			<tr class="danger">
			<th>Member ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Action</th>
			</tr>

			<?php
			//loop through all table rows
			while ($row=mysqli_fetch_array($result)){
			echo "<tr>";
			echo "<td>" . $row['member_id']."</td>";
			echo "<td>" . $row['firstname']."</td>";
			echo "<td>" . $row['lastname']."</td>";
			echo "<td>" . $row['login']."</td>";
			echo '<td><a href="delete-member.php?id=' . $row['member_id'] . '" class="btn btn-primary">Remove Member</a></td>';
			echo "</tr>";
			}
			mysqli_free_result($result);
			mysqli_close($conn);
			?>
			</table>
		</div>
	</div>
</body>
</html>
