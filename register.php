<?php include ('process.php')?>
<html>
<head>
  <title>Register</title>
  <link rel="stylesheet" href="Styles.css">
</head>
<body>
<header>
        <div>
            <ul>
                <li><a href="HomePage.php">Home</a></li>
                <li class="active"><a href="login.php">Sign In</a></li>
                <li class="dropdown"><a href="" class="dropbtn">Shop</a>
                <div class="dropdown-content">
                    <a href="Tee.php">Tees</a>
                   
         </div></li>
            </ul>
        </div>
    </header>
    <div class="logo">
        <img src="OutRuledLogoBlue.png" >
    </div>
    <form method="post" action="register.php" id="register_form">
        <h1 >Create account</h1>
        <div>
        <input type="text" name="fname" placeholder="Surname" value="<?php echo $fname;?>"><br>
        <input type="text" name="sname" placeholder="First Name" value="<?php echo $sname;?>">   
        </div>
        <div <?php if(isset($name_error)): ?> class="form_error" <?php endif ?>>
            <input  type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
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
     </form>         
</body>
</html>