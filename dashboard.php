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
<div class="login-box">
  <h2>Hey, <?php echo $_SESSION['username']; ?>!</h2>
  <h3 style='color:white'>You are in user dashboard page</h3>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <div class="user-box">
      <span style='color:white'>Select image to upload:</span>  
      <input type="file" name="fileToUpload" required="">
    </div>
    <div class="user-box">
      <input type="text" name="opencv" >
      <label>Opencv Input</label>
    </div>
   <div class="user-box">
      <input type="submit" name="submit" value="Submit" required="">
    </div>
    <a href="search.php">
      <span></span>
      <span></span>
      Search
    </a>
     <a href="logout.php">
      <span></span>
      <span></span>
      Logout
    </a>
  </form>
</div>
  

</body>
</html>
