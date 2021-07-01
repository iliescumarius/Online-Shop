<?php 
session_start();
$db=mysqli_connect('localhost','root','','login');
$username="";
$fname="";
$sname="";
$password_error="";
static $connected=false;
static $user_connected=false;
/*
        Register
*/
if(isset($_POST['register']))
{
    $username=$_POST['username'];
    $fname=$_POST['fname'];
    $sname=$_POST['sname'];
    $password=$_POST['password'];
    $sql_usename="Select *from user where Username='$username'";
    $res_username=mysqli_query($db,$sql_usename);
    if(mysqli_num_rows($res_username)>0)
    {
        $name_error="Sorry,this username is already taken";
    }
    else 
    {
        $query="INSERT into user (Username,Surname,Name,Password) values ('$username','$fname','$sname','$password')";
        $results=mysqli_query($db,$query);
        header("Refresh:0; url=login.php");
       // echo 'Saved!';
       // exit();
    }
}
/*
        Login 
*/
if(isset($_POST['login']))
{   
    
    $username=$_POST['username'];
    $password=$_POST['password'];
    $slq_u_p="Select U.Username,U.Password from user U where Username='$username'";
    $sql_password="Select U.Password from user U where Password='$password' AND Username='$username'";
     $res_u_p=mysqli_query($db,$slq_u_p);
     $res_password=mysqli_query($db,$sql_password);
     if(!$res_u_p)
        die("Error!");
    
        if(mysqli_num_rows($res_u_p)>0 && mysqli_num_rows($res_password)>0)
        {   
            $numRows = MySQLi_num_rows($res_u_p);
            $row=MySQLi_fetch_array($res_u_p,MYSQLI_ASSOC); 
            if($row['Username']=="marius012")
                {
                    $connected=true;
                    setcookie("admin_connected",$connected,time()+(10*365*24*60*60));
                    $user_connected=$row['Username'];;
                    setcookie("user_connected",$user_connected,time()+(10*365*24*60*60));
                    setcookie("username",$row['Username'],time()+(10*365*24*60*60));
                }
            else 
               {
                setcookie("admin_connected",$connected,time()+(10*365*24*60*60));
                $user_connected=$row['Username'];
                setcookie("user_connected",$user_connected,time()+(10*365*24*60*60));
                setcookie("username",$row['Username'],time()+(10*365*24*60*60));
               } unset($_SESSION['cart']);
               header("Refresh:0; url=Tee.php");
               
        } 
        else 
        {   $user_connected=false;
            setcookie("user_connected",$user_connected,time()+(10*365*24*60*60));
            $username_error="Sorry wrong username/password!";
        }
        
       
}

function is_admin($username_1)
{
    if($username_1=="marius012")
    return true;
    else 
    return false;
}
/*
        Display product function
*/
function add_product($prod_id,$prod_name,$prod_img,$prod_price,$prod_details){
    $db=mysqli_connect('localhost','root','','login');
    $query_size="Select  P.Name,S1.Size from size_details S join products P on P.Prod_Id=S.Prod_Id join size S1 on S.Size_Id=S1.Size_Id where S.Prod_Id=$prod_id";
    $res_size=mysqli_query($db,$query_size);

        if(!$res_size)
            die("Not working properly");
        $i=0;
        $numRows = MySQLi_num_rows($res_size); 
        $sizearr="";
        for($i=0;$i<$numRows;$i++)
        {
            $row=MySQLi_fetch_array($res_size,MYSQLI_ASSOC);
            $sizearr=array(
                'size' => $row['Size'],
                'name' => $row['Name']);
            $_SESSION['size'][$i]=$sizearr;
        }
        
       
        $select=" <select name='size' id='size'>";

         if (isset($_SESSION['size'])){
            foreach($_SESSION['size'] as $keys =>$values){
                $val=$values['size'];
               $select=$select."<option value=$val>$val</option>";
            }
        }
        else 
        {
            $select=$select."<option value='outofstock'>Sold Out</option>";
        }
        $select=$select." </select>";
       
    $element="
    <div class='card'>
    <form action='Tee.php?price=$prod_price' method='POST'>
    <img src=$prod_img style='width:100%;' >
    <h1>  $prod_name</h1>
    <p class='price' id='price'> $prod_price Ron</p>              
    <p> $prod_details</p> 
    <label id=size_label>Size:</label>".$select."
    <button type='submit' name='add' id='add'>Add to Cart <i class='fas fa-shopping-cart'></i></button>
    <input type='hidden' name='product_id' value= $prod_id>
    </form>
    </div>
    ";
    echo $element;
    if(isset($_SESSION['size']))
        { //print_r($_SESSION['size']);
        unset($_SESSION['size']);}
}
function add_order($order_id,$address_id){
    
    $query_address="Select * from address where Id='$address_id' ";// get address details 
    $db=mysqli_connect('localhost','root','','login');
    $result_address=mysqli_query($db,$query_address);
    $row_address=MySQLi_fetch_array($result_address,MYSQLI_ASSOC);

    $city=$row_address['City'];
    $street=$row_address['Street'];
    $number=$row_address['HouseNumber'];

    $query="Select Prod_Id,Quantity from order_details where Order_Id='$order_id'";// we take the id of the size for a specific item
    $result=mysqli_query($db,$query);
    $numRows = MySQLi_num_rows($result);
    $prod_size_id="";
    $quantity="";
    $element1="";
    for($i=0;$i<$numRows;$i++)
    {
        $row=MySQLi_fetch_array($result,MYSQLI_ASSOC);
        $prod_size_id=$row['Prod_Id'];
        $quantity=$row['Quantity'];

        $query_product="Select * from size_details where Id='$prod_size_id' ";// take the id of the product, in order to take the name & price
        $result_product=mysqli_query($db,$query_product);
        $row_product=mysqli_fetch_array($result_product,MYSQLI_ASSOC);
        $size_id=$row_product['Size_Id'];
        $prod_id=$row_product['Prod_Id'];

        $query_size="Select * from  size Where Size_Id='$size_id'";
        $result_size=mysqli_query($db,$query_size);
        $row_size=mysqli_fetch_array($result_size,MYSQLI_ASSOC);
        $size_name=$row_size['Size'];

        $query_prd_name="Select * from products where Prod_Id='$prod_id'";
        $result_prd_name=mysqli_query($db,$query_prd_name);
        $row_prd_name=mysqli_fetch_array($result_prd_name,MYSQLI_ASSOC);
        $price=$row_prd_name['Price'];
        $name=$row_prd_name['Name'];
        $element1=$element1."<tr> 
        <td> $prod_size_id</th>
        <td>$name</th>
        <td>$size_name</td>
        <td> $quantity</th>
        <td>$price</th>    
    </tr>";  

    }

    //$order_id,$city,$street,$prod_id,$prod_name,$size,$qty,$price
    $element="
    <table style='width:70%;margin-left: auto;margin-top:5%;  margin-right: auto; '>
  <tr>
    <th>Order Id:</th>
    <td>#$order_id</td>
  </tr>

  <tr>
    <th rowspan='3'>Address:</th>

    <th rowspan='1'>City:</th>
    <td>$city</td>
  </tr>
  <tr>
    <th rowspan=1'>Street:</th>
    <td>$street </td>
  </tr>
  <tr>
    <th rowspan='1'>Number:</th>
    <td>$number</td>
  </tr>
 
</table>
<table  style='width:70%;margin-left: auto;margin-top:5%;  margin-right: auto;'>
<tr>
    <th> Item Id</th>
    <th>Product Name</th>
    <th>Size </th>
    <th> Quantity</th>
    <th>Price </th>
</tr>".$element1."

</table> ";
echo $element;

}

session_abort();      
             
?>
