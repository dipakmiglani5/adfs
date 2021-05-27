<?php
//include auth_session.php file on all user panel pages
include("auth_session.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="st.css" />
</head>
<body>
   <!--  <div class="form">
        <p>Hey, <?php echo $_SESSION['username']; ?>!</p>
        <p>You are in user dashboard page.</p>
        <form action="search2.php" method="post" enctype="multipart/form-data">
            Search an image:<br>
        <input type="text" name="search"><br>
        <input type="submit" value="Search" name="submit">
        </form>

        <p><a href="logout.php">Logout</a></p>
      
    </div> -->
<div class="login-box">
  <h2>Hey, <?php echo $_SESSION['username']; ?>!</h2>
  <form action="search2.php" method="post" enctype="multipart/form-data">
    <div class="user-box">
      <input type="text" name="search" required="">
      <label>Search an image</label>
    </div>
    <div class="user-box">
     <input type="submit" value="Search" name="submit">
    </div>
    <a href="logout.php">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      Logout
    </a>
  </form>
</div>




</body>
</html>