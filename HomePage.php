<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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
                      
                      <form action="Tee.php" method="post">
                        <div style="">
                        <i class="fas fa-history" style="color:#668cff;padding-left:10px;"></i>
                          <input  type="submit" name="lastorders" id="lastorders" onClick="window.location.href='LastOrders.php'" value="Orders" style="border:none; background-color:transparent; color:#668cff;">
                          <i class="fas fa-sign-out-alt" style="color:#668cff;padding-left:10px;"></i>
                          <input  type="submit" name="logout" id="logout" value="Sign-out" style="border:none; background-color:transparent; color:#668cff;">
                          
                        </div>
                      </form>                    
                    </div>
                </li>
                <li class="dropdown"><a href="" class="dropbtn">Shop</a>
                    <div class="dropdown-content">
                        <a href="Tee.php">Tees</a>
                        
                       
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
              if(isset($_POST['lastorders']))
              {
                header("Refresh:0; url=LastOrders.php");
              }
              ?>
         <?php }else { ?>

          <ul>
                <li><a href="HomePage.php">Home</a></li>
                <li ><a href="login.php">Login</a></li>
                <li class="dropdown"><a href="" class="dropbtn">Shop</a>
                    <div class="dropdown-content">
                        <a href="Tee.php">Tees</a>
                        
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
    <div class="title">
        <h1><a href="login.php" style="color: #668cff;">Welcome to,<br>Out Ruled</a> </h1>
    </div>
    
    
</body>

</html>