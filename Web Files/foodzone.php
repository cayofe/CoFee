<?php
//checking connection and connecting to a database
require_once('connection/config.php');
//Connect to mysql server
require_once('connection/config.php');
error_reporting(1);
//Connect to mysql server
    $conn = new mysqli($servername, $username, $password, $database);
    if(!$conn) {
        die('Failed to connect to server: ' . $conn->error);
    }
    $excellent = 6 ; $good = 7 ;

//selecting all records from the food_details table. Return an error if there are no records in the table
$result=$conn->query("SELECT * FROM food_details,categories WHERE food_details.food_category=categories.category_id ") or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours.");
?>
<?php
    //retrive categories from the categories table
    $categories=$conn->query("SELECT * FROM categories")
    or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours.");

    $categories_copy = array();
?>
<?php
    //retrive a currency from the currencies table
    //define a default value for flag_1
    $flag_1 = 1;
    $currencies=$conn->query("SELECT * FROM currencies WHERE flag='$flag_1'")
    or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours.");
?>
<?php
  // Função para limpar valores recebidos do formulário para evitar SQL injection
function clean($str) {
  // Remove espaços em branco no início e no final da string
  $str = trim($str);
  
  // Verifica se a função mysqli_real_escape_string está disponível (usando a extensão MySQLi)
  if(function_exists('mysqli_real_escape_string')) {
      global $conn; // Supondo que você já tenha uma conexão com o banco de dados
      $str = mysqli_real_escape_string($conn, $str);
  } else {
      // Se mysqli_real_escape_string não estiver disponível, você pode lidar com isso de acordo com a extensão de banco de dados que está usando
      // Por exemplo, para extensões PDO, você usaria prepare statements
      // Para outros tipos de extensões, você deve usar a função apropriada para escapar os dados
  }

  return $str;
}
        //get category id
        $id = clean($_POST['category']);

        //selecting all records from the food_details and categories tables based on category id. Return an error if there are no records in the table
        $result=$conn->query("SELECT * FROM food_details,categories WHERE food_category='$id' AND food_details.food_category=categories.category_id ")
        or die("A problem has occured ... \n" . "Our team is working on it at the moment ... \n" . "Please check back after few hours.");
    
?>
<?php   //----------------- Getting Recommended Foods ------------------

$excellent_qry=$conn->query("SELECT * FROM food_details, polls_details WHERE polls_details.food_id=food_details.food_id AND (polls_details.rate_id='$excellent' OR polls_details.rate_id='$good' )")
or die("Something is wrong ... \n" . mysqli_error());

//actual values
$excellent_value=mysqli_num_rows($excellent_qry);
 // echo "<script>alert('Total $excellent_value')</script>";
$ids  = array();
while ($row_x=mysqli_fetch_array($excellent_qry))
{
  $food_id=$row_x['food_id'];
  $x = $conn->query("SELECT  * from polls_details WHERE food_id=$food_id AND (rate_id='$excellent' OR rate_id='$good')");
  $count_value = mysqli_num_rows($x);
  if($count_value>=5)  // careiteria for Recommendation
  {
    array_push($ids , $row_x['food_id']);
  }
}

$ids = array_unique($ids);  // removing duplicates
// echo "<script>alert(".sizeof($ids).")</script>";
   $recommended_foods = $conn->query("Select * from food_details WHERE food_id IN ( '".implode("', '", $ids)."' )");
   $z = mysqli_num_rows($recommended_foods);
   // echo "<script>alert('Total ,  $z')</script>";
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo "APP_NAME"; ?>:Foods</title>

<!--  Including Boostrap and JQuery Files   -->
   <link rel="stylesheet"  href="Assests/css/bootstrap.css">
   <script src="Assests/js/bootstrap.js"> </script>
   <script src="Assests/js/jquery.js"> </script>

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!---------------------------------------------->
<link href="stylesheets/user_styles.css"  rel="stylesheet" type="text/css">
<script language="JavaScript" src="validation/user.js"> </script>

</head>
<body>
<?php include 'navbar.php'; ?>
<div id="wrap" class="container-fluid">
   <h1>Welcome to Food Zone</h1>
  <div class="row">
    <div id="sidebar" class="col-md-2">
      <h3>Food Categories</h3>
      <form name="categoryForm" id="categoryForm" method="post" action="foodzone.php" onsubmit="return categoriesValidate(this)">
        <select name="category" id="foods_category">
           <option value="select" class="form-control">Select category
           <?php
           //loop through categories table rows
           while ($row=mysqli_fetch_array($categories)){
             $categories_copy =  $row;
            echo "<option id='option' class='form-control' value=$row[category_id]>$row[category_name]";
           }
           ?>
           </select>
           <input type="submit" name="Submit" value="Show Foods" id="categ_btn" class="form-control btn btn-danger"/>
    </form>
    </div>
    <div id="content" class="col-md-10">
      <div class="row">
        <?php
            $count = mysqli_num_rows($result);
            if(isset($_POST['Submit']) && $count < 1){
                echo "<html><script language='JavaScript'>alert('There are no foods under the selected category at the moment. Please check back later.')</script></html>";
            }
            else{
                //loop through all table rows
                //$counter = 3;
                $symbol=mysqli_fetch_assoc($currencies); //gets active currency
                while ($row=mysqli_fetch_assoc($result))
                { ?>
                  <div id="Product_box" class="col-md-3">
                    <h4 id="Product_name"> <?php echo $row['food_name']; ?> </h4>
                  <?php  echo '<img id="Product_image" src=images/'. $row["food_photo"].'>'; ?>
                    <p id="Product_desc"> <?php echo $row['food_description']; ?> </p>
                    <div id="Product_footer">
                      <div class="row">
                        <div class="col-md-6">
                          <p> PKR <?php echo $row['food_price'] ;?></p>
                        </div>
                        <div class="col-md-6">
                           <p style="text-align:right;"> <i class="glyphicon glyphicon-shopping-cart"> </i>
                             <?php echo '<a style="color:white;" href="cart-exec.php?id=' . $row['food_id'] . '">Add To Cart</a>';?> </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                    }
                }
          //  mysqli_free_result($result);
          //  mysqli_close($link);
        ?>
      </div>

     <hr> <h1> Recommended Foods </h1>
     <div class="row">
       <?php
           $count = mysqli_num_rows($recommended_foods);
           if(isset($_POST['Submit']) && $count < 1){
               echo "<html><script language='JavaScript'>alert('There are no foods under the selected category at the moment. Please check back later.')</script></html>";
           }
           else{
               //loop through all table rows
               //$counter = 3;
               $symbol=mysqli_fetch_assoc($currencies); //gets active currency
               while ($row=mysqli_fetch_assoc($recommended_foods))
               { ?>
                 <div id="Product_box" class="col-md-3">
                   <h4 id="Product_name"> <?php echo $row['food_name']; ?> </h4>
                 <?php  echo '<img id="Product_image" src=images/'. $row["food_photo"].'>'; ?>
                   <p id="Product_desc"> <?php echo $row['food_description']; ?> </p>
                   <div id="Product_footer">
                     <div class="row">
                       <div class="col-md-6">
                         <p> PKR <?php echo $row['food_price'] ;?></p>
                       </div>
                       <div class="col-md-6">
                          <p style="text-align:right;"> <i class="glyphicon glyphicon-shopping-cart"> </i>
                            <?php echo '<a style="color:white;" href="cart-exec.php?id=' . $row['food_id'] . '">Add To Cart</a>';?> </p>
                       </div>
                     </div>
                   </div>
                 </div>
                 <?php
                   }
               }
         //  mysqli_free_result($result);
         //  mysqli_close($link);
       ?>
     </div>
   </div>
 </div>
  </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
