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
            <input type="file" name="files[]" multiple id="fileInput">
            <input type="hidden" name="hidden1" id="hidden1" />
            <input type="hidden" name="hidden2" id="hidden2" />
      <font style='color:white'> Document Class:</font> 
      <select name="dcmntcls">
        <option value="IMAGES" selected="selected">Images</option>
        <option value="PDF" >PDF</option>
        <option value="VIDEOS" >Video(mp4)</option>
      </select>
      <br><br> 
      <font style='color:white'>Document Type:</font> 
        <select name="dcmnttyp">
        <option value="BOOKs" selected="selected">Book</option>
        <option value="FORMs" selected="selected">Form</option>
        <option value="News" selected="selected">News</option>
        <option value="Miscellaneous" selected="selected">Miscellaneous</option>
      </select>
      <br><br>    
      <font style='color:white'>Select Process:</font> <select name="prc">
        <option value="ocr" selected="selected">OCR</option>
        <option value="cv" >Object Detection</option>
        <option value="both" >Both</option>
      </select>
      <br><br>
      <font style='color:white'>Enter Batch:</font> 
      <input type="text"  name="btch">
    </div>
   <div class="user-box">
      <input type="submit" name="submit" value="Upload" required="">
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
<script type="text/javascript">
  const fileInput = document.querySelector('#fileInput');
fileInput.addEventListener('change', (event) => {
  // files is a FileList object (similar to NodeList)
  const files = event.target.files;
   var datevar="";
   var timevar="";

  for (let file of files) {
    const date = new Date(file.lastModified);
  
      datevar = datevar.concat(date.toISOString().replace('-', '-').split('T')[0].replace('-', '-'));
      datevar = datevar.concat('%');
      timevar = timevar.concat(date.toLocaleTimeString());
      timevar = timevar.concat('%');
  }
  document.getElementById("hidden1").value = datevar;
  document.getElementById("hidden2").value = timevar;

});

</script>



</body>
  
</html>                    