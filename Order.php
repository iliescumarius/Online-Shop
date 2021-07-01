<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
  <link rel="stylesheet" href="Styles.css">
  <!--Awesome font-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.css">
  <!--de pastrat-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>   
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
</head>
<body>
<button type="submit" class="cart" >
      <a href="Cart.php"><i class="fas fa-arrow-left" style="color:#668cff; font-size: x-large;"> My Cart <h style="color:#668cff; "></h></i></a></button>
      
      <?php
       $connect=mysqli_connect('localhost','root','','login');
      if(isset($_COOKIE['user_connected'])){?>

        <!--Already logged in-->

        <form method="post" action="Order.php" id="register_form">
        <h1 >Check Out Details</h1>
        <div>
              <h3 style="padding-left: 10%; color: white;"> Address </h3>
          <input type="text" name="City" placeholder="City" value=""><br>
          <input type="text" name="Street" placeholder="Street" value="">  
          <input type="text" name="Housenumber" placeholder="House Number" value=""> 
  
         <!-- <div style=" padding-left:10%">
            
            <div style="display: flex;flex-direction: row; align-items: center;  padding-left:10%;width:50%;  height: 10%;" >
              <input type="radio" name="courier" id="" value="DHL">
              <label for="courier" style="color: #668cff;"><h2>DHL</h2></label> 
            </div>

            <div style="display: flex;flex-direction: row; align-items: center; padding-left:14%;width:50%;  height: 10%;">
              <input type="radio" name="courier" value="fan_courier">
              <label for="courier" style="color: #668cff;"><h2>Fan Courier</h2></label>
            </div>

            <div style="display: flex;flex-direction: row; align-items: center;padding-left:15%;width:50%;  height: 10%;">
              <input type="radio" name="courier" value="posta_ro">
              <label for="courier" style="color: #668cff;"><h2>Posta Romana</h2></label>
            </div>
          </div> -->
        
          
        </div>
        <div>
  		<button type="submit" name="add_order" id="reg_btn" >Finalize the order</button>
  	    </div>
     </form>         

     <?php }  else  {?>

        <!--Create account+order-->

      <form method="post" action="Order.php" id="register_form">
        <h1 >Check Out</h1>
        <div>
        <input type="text" name="fname" placeholder="Surname" value=""><br>
        <input type="text" name="sname" placeholder="First Name" value="">   
        </div>
        <div <?php if(isset($name_error)): ?> class="form_error" <?php endif ?>>
            <input  type="text" name="username" placeholder="Username" value="">
            <?php if(isset($name_error)):?>
                <span><?php echo $name_error; ?></span>
                <?php endif ?>
          </div>
        <div>
  		<input type="password"  placeholder="Password" name="password">
  	    </div>

        <div>
  		<button type="submit" name="register" id="reg_btn" href="login.php">Register</button>
  	    </div>

        <div> 
            <h1 style="font-size: x-large;">Already have an account?</h1>
            <h1 style="font-size: large;"><a href="login.php" style="color: #668cff;">Login</a></h1>
        </div>
     </form>  <?php }
     
     //    Add in Database the Order

     if(isset($_POST['add_order'])){
      $city=$_POST['City'];
      $street=$_POST['Street'];
      $number=$_POST['Housenumber'];
      $user=$_COOKIE['user_connected'];
      $total_price=$_SESSION['total'];
      $date= date("Y-m-d");
      $query="Select U.User_Id from user U where Username='$user'";// we get the user id 
    
      $result=mysqli_query($connect,$query);

      if(!$result)
        die("Error!");

      $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
      $user_id=$row['User_Id'];
      $query="INSERT INTO address (City,Street,HouseNumber) VALUES ('$city','$street','$number')";
      echo $query;

      if(!mysqli_query($connect, $query)) //if can not add in the database, an error will be display 
      {  
                  echo '<script>alert("Failed to process the data")</script>';  
      } else
      {
        // if the address had been added in the database we take  its id in order to add it in the orders database
        $query="Select Id from address where City='$city' AND HouseNumber='$number' AND Street='$street'";//we get the id of the address we just introduce
        $result=mysqli_query($connect,$query);
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
        $address_id= $row['Id'];
        $query="INSERT INTO orders (User_id,Address_Id,Date,Total) VALUES ('$user_id','$address_id','$date','$total_price')";

        if(!mysqli_query($connect, $query))
        {  
          echo '<script>alert("Failde to process data")</script>';  
        } else
        {
          $query="Select Id from orders where User_Id='$user_id' AND Address_Id='$address_id' AND Date='$date' AND Total='$total_price'";//we get the id of the address we just introduce
          $result=mysqli_query($connect,$query);
          $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
          $order_id= $row['Id'];

          //add data in orders_detail database 

          if(isset($_SESSION['cart'])) 
        {   
          $_SESSION['aux']=$_SESSION['cart'];

          foreach($_SESSION['cart'] as $keys =>$values)
          {
            $item_id=$values['item_id'];
            $size=$values['item_size'];
            $query="Select D.Id from size_details D join size S on D.Size_Id=S.Size_Id  where Prod_Id='$item_id' AND S.Size='$size' ";
            $result=mysqli_query($connect,$query);
            $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
            $prod_size_id= $row['Id'];

            // verify if the item is already added in the order_details database if not we add it 

            $query1="Select * from order_details where Prod_Id='$prod_size_id' AND Order_Id='$order_id'";
            $result1=mysqli_query($connect,$query1);
            $row1=mysqli_fetch_array($result1,MYSQLI_ASSOC);
            $query_order="Update size_details  set Quantity=Quantity-1 where Id='$prod_size_id'  ";
             
            if(mysqli_query($connect,$query_order))
            {

            }
            if(empty($row1))
            {
              $query="INSERT INTO order_details (Order_Id,Prod_Id,Quantity) VALUES ('$order_id','$prod_size_id','1')";
              $result=mysqli_query($connect,$query);
              //echo '<script>alert("add new product in order")</script>'; 
             }
              
            else 
            {
              $query="Update order_details set Quantity=Quantity+1 where Prod_Id='$prod_size_id'";
              mysqli_query($connect,$query);
              //echo '<script>alert("Add quatity to a product ")</script>';  
            }

           
          }
        }
         echo '<script>alert("Your order is placed")</script>';  
        header("Refresh:0; url=Tee.php");
        unset($_SESSION['cart']);
        }
      }
      

         
      

     }
     
     
     ?> 
           
</body>
</html>