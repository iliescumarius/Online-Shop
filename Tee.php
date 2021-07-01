<?php include ('process.php');
session_start();
 if(isset($_POST["add"])&& $_POST['size']!="outofstock")
    { 
      if(isset($_SESSION['cart']))
      {
       $count=count($_SESSION['cart']);
       $item_array=array(
          'item_id' => $_POST['product_id'],
          'item_size'=> $_POST['size'],
          'item_price'=> $_GET['price']
       );
       $_SESSION['cart'][$count]=$item_array;
      } else {
        $item_array=array(
            'item_id' => $_POST['product_id'],
            'item_size'=> $_POST['size'],
            'item_price'=> $_GET['price']
        );
        $_SESSION['cart'][0]=$item_array;
     }
     
      // if(count($_SESSION['cart'])>5)
      //   {unset($_SESSION['cart']);
      //   unset($_SESSION['cart1']);}  
    }
              
?>
<html>
<head>
  <title>Shop</title>
  <link rel="stylesheet" href="Styles.css">
  <!--Awesome font-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.css">
  <!--de pastrat-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>   
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
</head>
<body >
<header>
        <div class="header_shop" "> <!--style="position: fixed;-->
        
          <button type="submit" class="cart"><a href="Cart.php"><i class="fas fa-shopping-basket" style="color:#668cff; font-size: x-large;"><h style="color:#668cff;"><?php  if( isset($_SESSION['cart']))print_r(count($_SESSION['cart'])); else echo 0; ?></h></i></button>
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
                        <a href="#">Coming soon</a>
                       
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
                        <a href="#">Hoodies</a>
                    </div>
                </li>
            </ul>
        <?php }
        ?>
         
        </div>
</header>

<!--Admin menu-->

<div class="admin_is_connected">
     <?php
     $connect=mysqli_connect('localhost','root','','login');
     
      if(isset($_COOKIE["admin_connected"]))
      {
        if(isset($_POST["insert"]))  
        {  
            $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));  
            $product_name=$_POST['product_name'];
            $price=$_POST['price'];
            $details=$_POST['details'];
            $query = "INSERT INTO products (Name,Image,Price,Details) VALUES ('$product_name','$file','$price','$details')";  
          if(mysqli_query($connect, $query))  
          {  
           echo '<script>alert("Product has been inserted into Database")</script>';  
          }  
        }  
          ?>
          <br><h1 style="text-align:center;">You are logged with admin</h1>

          <!--Add product Form-->

          <form method="post"  enctype="multipart/form-data" id="product_register">  
                     <h4 style="text-align: center; color:#fff;">Insert item</h4>
                     <input type="file" name="image" id="image" />  
                     <br />  
                     <input type="text" name="product_name" id="product_name" placeholder="Product name"/>
                     <br /><input type="text" name="details" id="details" placeholder="Details"/>
                     <br /> <input type="text" name="price" id="price" placeholder="Price"/>
                     <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-info" />                       
                </form> 

             <!--Add size for product-->

             <form method="post"  enctype="multipart/form-data" id="product_delete">  
                     <h4 style="text-align: center; color:#fff;">Insert size</h4>
                     <select name=item_delete id=item_delete>
                      <?php $query_items="Select Name from products";
                      $result=mysqli_query($connect,$query_items);
                      while($row = mysqli_fetch_array($result))
                      {
                        $item_name=$row['Name']; $item_name_list.=' '.$item_name;?>
                        <option value="<?php echo $item_name ?>"> <?php echo $item_name?></option>
                     <?php }?>
                    </select>
                    <select name=item_size id=item_delete>
                    <?php $query_size="Select Size from size";
                      $result=mysqli_query($connect,$query_size);
                      while($row = mysqli_fetch_array($result))
                      {
                        $item_name=$row['Size']; $item_name_list.=' '.$item_name;?>
                        <option value="<?php echo $item_name ?>"> <?php echo $item_name?></option>
                     <?php }?>
                    </select>
                     <input type="text" name="quantity" id="quantity" placeholder="Quantity"/>
                     <input type="submit" name="insertsize" id="insertsize" value="Insert" class="btn btn-info" />                       
                </form> 

                <?php
                if(isset($_POST['insertsize']))
                {
                  $prod_name=$_POST['item_delete'];
                  $size=$_POST['item_size'];
                  $quantity=$_POST['quantity'];
                  $query_getid="Select P.Prod_Id from products P where Name='$prod_name'";
                  $result=mysqli_query($connect,$query_getid);
                  $prod_id="";
                  if($result)
                  { 
                    $row = mysqli_fetch_array($result);
                    $prod_id=$row['Prod_Id'];
                  }
                 
                  $query_getid="Select S.Size_Id from size S where Size='$size'";
                  $result=mysqli_query($connect,$query_getid);
                  $size_id="";
                  if($result)
                  { 
                    $row = mysqli_fetch_array($result);
                    $size_id=$row['Size_Id'];
                  }
                 
                  $query_size="INSERT INTO size_details (Size_Id,Prod_Id,Quantity) VALUES ('$size_id','$prod_id','$quantity')";
                  if(mysqli_query($connect, $query_size))  
                  {  
                  echo '<script>alert("Product has been inserted into Database")</script>';  
                  }  
                }
                ?>

              <!--Delete product Form-->  

                <form method="post" action="Tee.php" enctype="multipart/form-data" id="product_delete"> 
                    <h4 style="text-align: center; color:#fff">Delete item</h4> 
                    <label id="item_delete_label">Choose product to delete</label><br>
                    <select name=item_delete id=item_delete>
                      <?php $query_items="Select Name from products";
                      $result=mysqli_query($connect,$query_items);
                      while($row = mysqli_fetch_array($result))
                      {
                        $item_name=$row['Name']; $item_name_list.=' '.$item_name;?>
                        <option value="<?php echo $item_name ?>"> <?php echo $item_name?></option>
                     <?php }?>
                    </select>
                     <input type="submit" name="select" id="select" value="Delete" class="btn btn-info" /> 
                     <?php
                     if(isset($_POST["select"]))
                      {  
                        $item_name=$_POST['item_delete'];
                        $query_delete_item="Delete from products where Name='$item_name'";
                       if(mysqli_query($connect,$query_delete_item))
                       {
                         echo '<script>alert("The product has been deleted from Database")</script>';  
                       }

                      }
                     ?>               
                </form> 

                <!--Change product price Form-->  

                <form method="post"  enctype="multipart/form-data" id="product_change">  
                     <h4 style="text-align: center; color:#fff;">Change Item Price</h4>
                     <label id="change_price_label">Choose product</label><br>
                     <select name=change_price id=change_price>
                      <?php $query_items="Select Name from products";
                      $result=mysqli_query($connect,$query_items);
                      while($row = mysqli_fetch_array($result))
                      {
                        $item_name=$row['Name']; $item_name_list.=' '.$item_name;?>
                        <option value="<?php echo $item_name ?>"> <?php echo $item_name?></option>
                     <?php }?>
                    </select>
                     <br /> <input type="text" name="price" id="price" placeholder="Price"/><br>
                     <input type="submit" name="change" id="change" value="Change" class="btn btn-info" />     
                     <?php
                     if(isset($_POST["change"]))
                      { $item_name=$_POST['change_price'];
                         $item_price=$_POST['price'];
                         $query_change_price="Update products set Price='$item_price' where Name='$item_name'";
                        if(mysqli_query($connect,$query_change_price))
                        {
                          echo '<script>alert("The product price had been changed")</script>';  
                        }
                      }
                     ?>                       
                </form>                       
             <?php } 
                ?>  
               
</div>

<!--Filter menu-->

<div class="filter">
  <ul> 
     <li class="filter_dropdown"><a href="" class="filter_dropbtn"><i class="fas fa-filter" >Filter by</i></a>
        <div class="dropdown-content">
        <form action="Tee.php" method="post">
          <input type='radio' name='filter' value='Price'>
          <label for="filter"> Price</label><br>
          <input type='radio' name='filter' value='Name'>
          <label for="filter"> Name</label><br>
          <button type="submit" name="ascending_order"style="background-color: transparent;border: none;" ><a href="Tee.php" ><i class="fas fa-sort-amount-up-alt"></i></a></button>
          <button type="submit" name="desscending_order"style="background-color: transparent;border: none;"><a href="Tee.php"><i class="fas fa-sort-amount-down-alt"></i></a></button>
        </form>
      </div></li>
  </ul>
</div>

<!--FILTER SELCTION-->

<?php
if(!isset($_SESSION['order']))
  $_SESSION['order']=1;

  if( isset($_POST['ascending_order']))
{
    $_SESSION['order']=1;
    if(!empty($_POST['filter']))
      {
        $_SESSION['order_by']=$_POST['filter'];
       
      }
    
}
else 
{
  $_SESSION['order']=0;
  if(!empty($_POST['filter']))
      {
        $_SESSION['order_by']=$_POST['filter'];
        
      }

}
?>

<!--Show items from DB-->

<div class="tee_title">
<?php if(isset($_SESSION['order_by']))
        { 
         $order_by=$_SESSION['order_by'];
        }
        else 
        {
          $order_by="Name";
        }
     
        
      if($_SESSION['order']==1)
      $query = "SELECT * FROM products ORDER BY $order_by ";  
      else 
      $query = "SELECT * FROM products ORDER BY $order_by DESC";
                $result = mysqli_query($connect, $query);  
                if(!$result)
                die ("does not work");
                while($row = mysqli_fetch_array($result))
                { 
                  $source='data:image/jpeg;base64,'.base64_encode($row['Image'] );
                  $product_id=$row['Prod_Id'];
                  $product_name=$row['Name'];
                  $product_price=$row['Price'];
                  $product_details=$row['Details'];
                 add_product($product_id,$product_name,$source,$product_price,$product_details);
                }?>
                
</div> 

</body>
</html>
<script>
$(document).ready((function(){
  $('#insert').click((function){
    var image_name=$('#image').val();
    if(image_name=='')
    {
      alert("Please Select Image:");
      return false;
    }
    else 
    {
      var extention=$('#image').val().split('.').pop().toLowerCase();
      if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)  
                {  
                     alert('Invalid Image File');  
                     $('#image').val('');  
                     return false;  
                }  
    }
  });
});
</script>
