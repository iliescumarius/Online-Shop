<?php include ('process.php');
 $db=mysqli_connect('localhost','root','','login');
 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="Styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>   
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
</head>
<body>
    <header>
    <div class="header_shop">
        
      <?php
      if(isset($_COOKIE['user_connected']))
        { ?>
             <ul>
              <li><a href="HomePage.php">Home</a></li>

              <li class="dropdown"><a href="" class="dropbtn">My account</a>
                  <div class="dropdown-content">
                    <a href="#"><i class="fas fa-history"></i>Orders</a>
                    <form action="Tee.php" method="post">
                      <div style="">
                        <i class="fas fa-sign-out-alt" style="color:#668cff;padding-left:10px;"></i>
                        <input  type="submit" name="logout" id="logout" value="Sign-out" style="border:none; background-color:transparent; color:#668cff; font-size=large;">
                      </div>
                    </form>                    
                  </div>
              </li>
              <li class="dropdown"><a href="" class="dropbtn">Shop</a>
                  <div class="dropdown-content">
                      <a href="Tee.php">Tees</a>
                      <a href="#">Hoodies</a>
                  </div>
              </li>
            </ul>
            <?php 
            if(isset($_POST['logout']))
            {
                $user_connected=false;
                setcookie("user_connected",$user_connected,time()+(10*365*24*60*60));
                setcookie("admin_connected",$user_connected,time()+(10*365*24*60*60));
                setcookie("username","",time()+(10*365*24*60*60));
                unset($_SESSION['cart']);
                header("Refresh:0");
            }
            ?>
       <?php }else { ?>

        <ul>
              <li><a href="HomePage.html">Home</a></li>
              <li ><a href="login.php">Login</a></li>
              <li class="dropdown"><a href="" class="dropbtn">Shop</a>
                  <div class="dropdown-content">
                      <a href="Tee.php">Tees</a>
                      <a href="#">Hoodies</a>
                  </div>
              </li>
          </ul>
      <?php }
      ?>
       
      </div>
    </header>
    <div class="logo">
        <img src="OutRuledLogoBlue.png" >
    </div>
    <?php
    $username= $_COOKIE['username'];
    
    $query="SELECT U.User_Id FROM user U where Username='$username'";// get the unsername id in order to find all his orders 
    $result=mysqli_query($db,$query);
    $id="";

    if(!empty($result))
        {
            $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
            $id=$row['User_Id'];
        }

    $query="Select * from Orders where User_Id='$id'";//get the address id and order id base on user id 
    
    $result=mysqli_query($db,$query);
    $numRows = MySQLi_num_rows($result);
    if($numRows!=0)
   {
    $address_id="";
    $order_id="";
    for($i=0;$i<$numRows;$i++)
    {
        $row=MySQLi_fetch_array($result,MYSQLI_ASSOC);
        $address_id=$row['Address_Id'];
        $order_id=$row['Id'];
        ?>
        <h2 style = 'text-align:center;'>Order number <?php echo $i+1 ?></h2> 
        <?php
        add_order($order_id,$address_id,);

    }
   } 
   else 
   { ?>
       <h2 style="text-align:center"> No previous orders</h2>
       <?php
   }
    ?>
   
    
    
</body>
</html>