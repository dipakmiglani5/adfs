<link rel="stylesheet" href="st.css"/>



<?php
include("auth_session.php");
require('db.php');
$target_dir = "uploads/";

//$maxid = 0;
//$query = " SELECT MAX (FleId) AS `maxid` FROM `Fle`";

$result = mysqli_query($con, "SELECT FleId FROM `Fle` ORDER BY `FleId` DESC LIMIT 1");
$row = mysqli_fetch_array($result);
$maxid=$row['FleId'];


//$sql= "select max(FleId) from Fle";
//$check   = mysqli_query($con, $sql);
//$check=$check+1;
$maxid =$maxid +1;
//echo $maxid ;

$t=(string)$maxid;  

$extension = end(explode(".", $_FILES["fileToUpload"]["name"]));

$t=$t.".".$extension;

$target_file = $target_dir . basename($t);



//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//echo "$target_file";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check != false) {
    // echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    
      echo "<div class='login-box'>
    <h2> Sorry, file is not an image </h2><br/>
    </div>"; 
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "<div class='login-box'>
    <h2> Sorry, file already exists</h2><br/>
    </div>"; 
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000000) {
   echo "<div class='login-box'>
    <h2> Sorry, your file is too large.</h2><br/>
    </div>"; 
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
 echo "<div class='login-box'>
    <h2> Sorry, only JPG, JPEG, PNG & GIF files are allowed.</h2><br/>
    </div>"; 

  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
   echo "<div class='login-box'>
    <h2> Sorry, your file was not uploaded.</h2><br/>
    </div>"; 
// if everything is ok, try to upload file
} else {
    //echo " move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)";
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    
    $temp = $_FILES["fileToUpload"]["name"];
    $query="insert into `Fle` (FleNme) values ('$temp')";
    $check   = mysqli_query($con,$query);


    echo "<br>";
   //echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    echo "<div class='login-box'>
    <h2> The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded</h2><br/>
    <span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
                   <span></span>
                   <span></span>
                   <span></span>upload another file</a><br>
     <span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
                   <span></span>
                   <span></span>
                   <span></span>logout</a><br>
      <span style='color:white'>Click here to </span> <a href='search.php'style='color:white'> <span></span>
                   <span></span>
                   <span></span>
                   <span></span>search</a>             
    </div>"; 
  } else {
    echo "<div class='login-box'>
    <h2> Sorry, there was an error uploading your file.</h2><br/>
    </div>"; 



  }
}






?>


<?php
  $name=$_FILES["fileToUpload"]["name"];
  $command11 = "/var/www/html/adfs/first.py3 ". $t ."";
  $message = exec($command11);
  //print_r($message);
     
  $command12 = "/var/www/html/adfs/script.py3 ". $t ."";
  // //echo $command12;
  $outputopencv = shell_exec($command12);
  //print_r($outputopencv);
  //echo $outputopencv;
  $class="Paper1"; //put the class in here
  $sql1= "insert into `DcmntCls` (DcmntClsDscrptn) values('$class')";
  $result1   = mysqli_query($con, $sql1);

  $type=$extension; //put in the document type here
  $sql2 = "insert into `DcmntTyp` (DcmntTypDscrptn, DcmntClsId) values('$type', (select max(DcmntClsId) from DcmntCls))";  
  $result2   = mysqli_query($con, $sql2);

  $BatchDescription = "batch_description_here"; //put in the batch description here
  $sql3 = "insert into `Btch` (DcmntClsId, DcmntTypId, BtchDscrptn) values((select max(DcmntClsId) from DcmntCls), 
          (select max(DcmntTypId) from DcmntTyp), '$BatchDescription')";
  $result3   = mysqli_query($con, $sql3);

  $sql4 ="insert into `Dcmnt`(DcmntClsId, DcmntTypId, BtchId, DcmntPth, DcmntNme) values(
          (select max(DcmntClsId) from DcmntCls), 
          (select max(DcmntTypId) from DcmntTyp), 
          (select max(BtchId) from Btch),
          '$target_dir',
          '$t'
          )";
  $result4  = mysqli_query($con, $sql4);

  $page_number = 1; //insert page number here
  $sql5="insert into `Pge` (DcmntId, PgeNmbr) values(
          (select max(DcmntId) from Dcmnt),
          '$page_number')"; //insert page number here, set to one as of now
  $result5  = mysqli_query($con, $sql5);

  $var1 = 0.2;
  $var2 = 0.2;
  $var3 = 0.2;
  $sql6 = "insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('$var1', '$var2', '$var3')"; //insert the values of prcs time taken here
  $result6   = mysqli_query($con, $sql6);

  $currusername = $_SESSION['username'];
  //echo $currusername;

  $sql_temp = "select UsrId from usr where username = '$currusername' LIMIT 1";
  $result_temp   = mysqli_query($con, $sql_temp);
  $row = mysqli_fetch_array($result_temp);
  $uid=$row['UsrId'];
  //echo $uid;
  // date_default_timezone_set('Australia/Melbourne');
  // $date = date('m/d/Y h:i:s a', time());

  // $date = date('Y-m-d', strtotime($date));
   $date = date("Y-m-d");
  $date=date("Y-m-d",strtotime($date));
  
 $curr_time = date('Y-d-m H:i:s',time());

 //echo $message;
 //echo $outputopencv;
 $outputopencv = stripslashes($outputopencv);    // removes backslashes
 $outputopencv = mysqli_real_escape_string($con,$outputopencv);
 $message = stripslashes($message);    // removes backslashes
 $message = mysqli_real_escape_string($con,$message);

$sql7= "insert into PgePrcs(UsrId,FleNme,FldrPth, PgeId,PrcsId,Dte, tme, FleIdNme, Cv, Ocr) values ('$uid','$name',
       '$target_dir',(select max(PgeId) from Pge), (select max(PrcsId) from Prcs),'$date','$curr_time', '$t', '$outputopencv', '$message')";
  $result7   = mysqli_query($con, $sql7);


?>


 <?php
    

  //   $my=$t;
  //   //echo $my;
  //   //$command = escapeshellcmd("/var/www/html/adfs/first.py3 '". $my ."'");
  //   $message = exec("/var/www/html/adfs/first.py3 2>&1");
  //   //echo "hey";
  //   print_r($message);
  //   //echo $message;
  //   // $output = shell_exec($command);
  //   // $output = stripslashes($output);
  //   // $output = mysqli_real_escape_string($con, $output);
  //   // echo $output;
  //  //  $message = exec("/var/www/html/adfs/script.py3 2>&1");
  //    // print_r($message);

  //   $command11 = escapeshellcmd("/var/www/html/adfs/script.py3 '". $my ."'");
  //   $outputopencv = shell_exec($command11);
  //  // print_r($outputopencv);
  //    //echo $outputopencv;




    

  //   // $sql1= "insert into `DcmntCls` (DcmntClsDscrptn) values('$class')";
  //   // $result1   = mysqli_query($con, $sql1);

  //   // $sql2 = "insert into `DcmntTyp` (DcmntTypDscrptn, DcmntClsId) values('$type', (select max(DcmntClsId) from DcmntCls))";  
  //   // $result2   = mysqli_query($con, $sql2);

         
  //   // $sql3 ="  insert into `Btch`(DcmntClsId, DcmntTypId, BtchDscrptn) values(
  //   //   (select max(DcmntClsId) from DcmntCls), (select max(DcmntTypId) from DcmntTyp ), 'BatchDescription'
  //   //   )";
  //   //   $result3   = mysqli_query($con, $sql3);


  //   //  $sql4 ="insert into `Dcmnt`(DcmntClsId, DcmntTypId, BtchId, DcmntPth, DcmntNme) values(
  //   //       (select max(DcmntClsId) from DcmntCls), 
  //   //       (select max(DcmntTypId) from DcmntTyp), 
  //   //       (select max(BtchId) from Btch),
  //   //       '$target_dir',
  //   //       '$name'
  //   //       )";
  //   //       $result4  = mysqli_query($con, $sql4);

  //   //     $sql5="insert into `Pge` (DcmntId, PgeNmbr) values(
  //   //         (select max(DcmntId) from Dcmnt),
  //   //         1)"; //insert page number here, set to one as of now
  //   //     $result5  = mysqli_query($con, $sql5);

  //   //      $sql6 = "  insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('0.2', '0.2', '0.2')" //insert the values of prcs time taken here
  //   //      $result6   = mysqli_query($con, $sql6);



  //   //    $sql_temp = "select UsrId from usr where username = '$currusername' LIMIT 1";
  //   //    $result7   = mysqli_query($con, $sql_temp);
  //   //    $row = mysqli_fetch_array($result7);
  //   //    $uid=$row['UsrId'];

       

  //   //      $sql7= "INSERT INTO PgePrcs(UsrId,Flenme,Ocr,Cv,Dte,tme,fldrpth, PgeId) values ('$uid','$name','$output','$outputopencv',NOW(),NOW(),
  //   //     '$target_dir','(select max(PgeId) from Pge)' )";
       
  //   //      $result   = mysqli_query($con, $sql7);


      
              
  //               //  $my=$_FILES["fileToUpload"]["name"];
  //               //  $command = escapeshellcmd("/var/www/html/adfs/first.py3 '". $my ."'");

  //               //  $output = shell_exec($command);
  //               //  $output = stripslashes($output);
  //               //  $output = mysqli_real_escape_string($con, $output);
  //               //  // echo $output;
  //               // //  $message = exec("/var/www/html/adfs/script.py3 2>&1");
  //               //   // print_r($message);

  //               //  $command11 = escapeshellcmd("/var/www/html/adfs/script.py3 '". $my ."'");
  //               //  $outputopencv = shell_exec($command11);
  //               // // print_r($outputopencv);
  //               //   //echo $outputopencv;





  //       // $query    = "INSERT into `img` (filename,ocr,opencv)
  //       //              VALUES ('" . basename($_FILES["fileToUpload"]["name"]) . "','$output', '$outputopencv')";
  //       //            $result   = mysqli_query($con, $query);



?> 

