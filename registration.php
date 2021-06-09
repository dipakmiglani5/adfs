<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="st.css"/>
</head>
<body>
<?php
    require('db.php');
    // When form submitted, insert values into the database.
    if (isset($_REQUEST['username'])) {
        // removes backslashes
        $username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
        $username = mysqli_real_escape_string($con, $username);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($con, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        $create_datetime = date("Y-m-d H:i:s");
        $query    = "INSERT into `usr` (username, password, email, create_datetime)
                     VALUES ('$username', '" . md5($password) . "', '$email', '$create_datetime')";
      
        $sql = "SELECT username FROM `usr` WHERE username = '$username'" ;
        $check   = mysqli_query($con, $sql);

        $row = mysqli_num_rows($check);

        

        if ($row==0 ) {
            
            $result   = mysqli_query($con, $query);
            if($result)
            {
            
            echo "<div class='login-box'>
                  <h2>You are registered successfully.</h2><br/>
                 <span style='color:white'>Click here to </span>  <a href='login.php'  style='color:white'>
                   <span></span>
                   <span></span>
                   <span></span>
                   <span></span>
                    Login</a>
                  </div>" ;
            }
            else {
                echo "<div class='login-box'>
                      <h2>Required fields are missing.</h2><br/>
                     <span style='color:white'>Click here to </span><a href='registration.php'>
                      <span></span>
                   <span></span>
                   <span></span>
                   <span></span>Register</a><span style='color:white'> again.</span>
                      </div>";
            }

        }
        elseif ($row > 0) {
             echo "<div class='login-box'>
                  <h2>Username is alredy taken</h2><br/>
                  <span style='color:white'>Click here to </span><a href='registration.php' style='color:white'>Register</a><span style='color:white'> again.</span>
                  </div>";  
         } 
    }
     else {
?>
        <div class="login-box">
      <h2>Registration</h2>
     <form action="" method="post">
    <div class="user-box">
      <input type="text" name="username" required="">
      <label>Username</label>
    </div>
    <div class="user-box">
      <input type="text" name="email" required="">
      <label>Email</label>
    </div>
    <div class="user-box">
      <input type="password" name="password" required="">
      <label>Password</label>
    </div>

     <div class="user-box">
      <input type="submit" name="submit" value="Register">
    </div>

    <a href="login.php">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      Have an account?
    </a>
  </form>
</div>



<?php
    }
?>
</body>
</html>
