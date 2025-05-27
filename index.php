<!DOCTYPE html>
<html lan="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatitable" content="IE=edge">
        <meta name="viewpoint" content="width=device-width,intial-scale=1.0">
        <title>LOGIN</title>
		<link rel="stylesheet" type="text/css" href="style.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
<body>
	<header>
		<a href="alogin.php"><button name="Admin" placeholder="Admin" value="Admin">Admin</button><a>
	</header>
     <form action="login.php" method="post">
     	<h2>LOGIN</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
	<div class="box">	
     	<label>User Name</label>
		<div class="icon">
		<i class='bx bxs-user' ></i>
		</div>
     	<input type="text" name="uname"><br>

     	<label>Password</label>
     	<input type="password" name="password"><br>
		 <div class="icon-lock">
		 <i class='bx bxs-lock' ></i>
		</div>
	</div>	

     	<button class="btn" type="submit">Login</button>
          <a href="signup.php" class="ca">Create an account</a>
     </form>
</body>
</html>