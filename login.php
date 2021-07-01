<?php include ('process.php')?>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="Styles.css" type="text/css">
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
        <img src="OutRuledLogoBlue.png">
    </div>
   
    <form method="post" action="login.php" id="register_form">
        <h1 >Login</h1>
        
        <div <?php if(isset($username_error)): ?> class="form_error" <?php endif ?>>
            <input  type="text" name="username" placeholder="Username" value="<?php echo $username; ?>">
            <?php if(isset($username_error)):?>
                <span><?php echo $username_error; ?></span>
                <?php endif ?>
        </div>

        <div    <?php if(isset($password_error)): ?> class="form_error" <?php endif ?>>
  		    <input type="password"  placeholder="Password" name="password">
            <?php if(isset($username_error)):?>
                <span><?php echo $password_error; ?></span>
                <?php endif ?>
        </div>

        <div>
  		    <button type="submit" name="login" id="login">Login</button>
  	    </div>
          
        <div> 
            <h1 style="font-size: x-large;">New Customer?</h1>
            <h1 style="font-size: large;"><a href="register.php" style="color: #668cff;">Create new accout    </a>    </h1>
        </div>
     </form>         
</body>
</html>