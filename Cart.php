<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.css"> 
    <title>Cart</title>
</head>
<body>
    <?php 
    if(isset($_GET['action']))
    {   
        if($_GET['action']=='delete')
        {
            foreach($_SESSION['cart'] as $keys =>$values)
            {
                if($values['item_id']==$_GET['id']&& $values['item_size']==$_GET['size'])
                { 
                   // echo 'de sters ';
                    unset($_SESSION['cart'][$keys]);
                   // print_r($values);
                }
                else 
                { if(isset($_SESSION['cart1']))
                    {
                        $count=count($_SESSION['cart1']);
                        $new_item_array=array(
                            'item_id' =>$values['item_id'],
                            'item_size'=> $values['item_size'],
                            'item_price'=> $values['item_price']
                        );
                        $_SESSION['cart1'][$count]=$new_item_array;
                    }
                    else 
                    {
                        $new_item_array=array(
                            'item_id' =>$values['item_id'],
                            'item_size'=> $values['item_size'],
                            'item_price'=> $values['item_price']
                        );
                        $_SESSION['cart1'][0]=$new_item_array;
                    }
                }
            }
            if(isset($_SESSION['cart1']))
            { 
                $_SESSION['cart']=$_SESSION['cart1'];
                unset($_SESSION['cart1']); 
           }
        }
       
    }?>
 <header>
 <button type="submit" class="cart" >
      <a href="Tee.php"><i class="fas fa-arrow-left" style="color:#668cff; font-size: x-large;"> Shop <h style="color:#668cff; "></h></i></a></button>
 </header>
    <div class="title_cart" style=" color: #668cff;"> <h1>Your cart</h1></div>
    <?php
    if(isset($_SESSION['cart'])) 
    {   $total_price=0;
        foreach($_SESSION['cart'] as $keys =>$values)
        {
            $total_price+=$values['item_price'];
            $_SESSION['total']=$total_price;
        }
        echo "<h3 style='text-align: left; margin: auto; padding-left:10%; color: #668cff;'>Total price is: $total_price RON</h3>";
        ?>
       <div class="checkout_div"   >
       <form action="Order.php" method="POST" class="order" >
        <button type='submit' name='checkout' id='checkout'><i class="fas fa-shopping-cart"></i></i>Check-out</button>
        </form> </div>
        
        <?php
        
    }
     
    ?>
    
    <div class='shopping_grid'>
        <?php 
        $connect=mysqli_connect('localhost','root','','login');
        if(isset($_SESSION['cart']))
        {
        foreach($_SESSION['cart'] as $keys =>$values)
            { 
                if(isset($_SESSION['cart'][$keys]))
               { 
                    $id=$values['item_id'];
                    $size=$values['item_size'];
                    $query="Select * from products where Prod_Id='$id'";
                    $result=mysqli_query($connect,$query);
                    while($row=mysqli_fetch_array($result))
                    { 
                        $source='data:image/jpeg;base64,'.base64_encode($row['Image'] );
                        $price=$row['Price'];
                        $name=$row['Name'];
                        echo "
                        <div class='shopping_cart'>
                            <form action='Cart.php?action=delete&id=$id&size=$size' method='POST'>
                                <img src=$source>
                                <p>$name</p>
                                <p>Size: $size</p>
                                <h3>$price Ron<h3>
                               
                                <button type='submit' name='remove' id='remove' ><i class='fas fa-trash'></i>Remove item</button>
                                <input type='hidden' name='product_id' value= $id>
                                <input type='hidden' name='index' value=$keys>
                            </form>
                        </div>";
                    }
                }
            }?>
           
        <?php
        }
        else 
            echo "<h1> <br>No items in cart</h1>";
        ?>
    </div>
</body>
</html>