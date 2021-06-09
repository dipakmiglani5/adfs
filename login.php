

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="st.css"/>
</head>
<body>
<?php
    require('db.php');
    session_start();
    
    // When form submitted, check and create user session.
    if (isset($_POST['username'])) {
      $username = stripslashes($_REQUEST['username']);    // removes backslashes
      $username = mysqli_real_escape_string($con, $username);
      $password = stripslashes($_REQUEST['password']);
      $password = mysqli_real_escape_string($con, $password);
      // Check user is exist in the database
      $query    = "SELECT * FROM `usr` WHERE username='$username'
                   AND password='" . md5($password) . "'";
      $result = mysqli_query($con, $query) or die(mysql_error());
      $rows = mysqli_num_rows($result);
      if ($rows == 1) {
          $_SESSION['username'] = $username;
          // Redirect to user dashboard page
          header("Location: dashboard.php");
      } else {
          echo "<div class='form'>
                <h3>Incorrect Username/password.</h3><br/>
                <p class='link'>Click here to <a href='login.php'>Login</a> again.</p>
                </div>";
      }
  } else {
?>
<div class="login-box">
  <h2>Login</h2>
  <form method="post" name="login">
    <div class="user-box">
      <input type="text" name="username" required="">
      <label>Username</label>
    </div>
    <div class="user-box">
      <input type="password" name="password"  required="">
      <label>Password</label>
      <input type="submit" name="submit" value="Click here to Login">
    </div>

      <a href="registration.php">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      Register now
    </a>

<?php
    }
?>
</body>
</html>
