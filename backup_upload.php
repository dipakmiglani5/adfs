<link rel="stylesheet" href="st.css"/>
<?php
 include("auth_session.php");
	require('db.php');
 //echo isset($_POST['prc']);

function fill_tables($clsdscrptn, $typdscrptn, $btchdscrptn, $nme, $pth, $prcs, $mdfd, $usrid, $con)
{
	$sql= "insert into `DcmntCls` (DcmntClsDscrptn) values('$clsdscrptn')";
	$result = mysqli_query($con, $sql);

	$sql = "insert into `DcmntTyp` (DcmntTypDscrptn, DcmntClsId) values('$typdscrptn', (select max(DcmntClsId) from DcmntCls))";  
	$result = mysqli_query($con, $sql);

	$sql = "insert into `Btch` (DcmntClsId, DcmntTypId, BtchDscrptn) values((select max(DcmntClsId) from DcmntCls), 
	(select max(DcmntTypId) from DcmntTyp), '$btchdscrptn')";
	$result = mysqli_query($con, $sql);

	$sql ="insert into `Dcmnt`(DcmntClsId, DcmntTypId, BtchId, DcmntPth, DcmntNme, Mdfd) values(
		(select max(DcmntClsId) from DcmntCls), 
		(select max(DcmntTypId) from DcmntTyp), 
		(select max(BtchId) from Btch),
		'$pth','$nme', '$mdfd')";
	$result = mysqli_query($con, $sql);

	$page_number = 1;
	$sql="insert into `Pge` (DcmntId, PgeNmbr) values(
		(select max(DcmntId) from Dcmnt),
		'$page_number')"; //insert page number here, set to one as of now
	$result = mysqli_query($con, $sql);

	if($prcs == "ocr")
	{
		$cmdocr = "pytesseract.image_to_string(Image.open(filename))";
		$cmdcv = "";
		$sql = "insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('$prcs', '$cmdocr', '$cmdcv')"; //insert the values of prcs time taken here
		$result = mysqli_query($con, $sql);
		//$command = "/var/www/html/adfs/first.py3 ". $nme ."";
		$start_time = microtime(true); 
		//$outputocr = shell_exec($command);
		$outputocr = shell_exec("/var/www/html/adfs/first.py3 '".$nme."'");
		$end_time = microtime(true);
		$outputocr = stripslashes($outputocr);    // removes backslashes
		$outputocr = mysqli_real_escape_string($con,$outputocr);
		$outputopencv = "";
		$tmetkn = strval($end_time - $start_time);
		//echo $tmetkn;
		//echo $outputocr;
		//echo $nme;
		$date = date("Y-m-d");
		$date = date("Y-m-d",strtotime($date));
	    $curr_time = date('Y-d-m H:i:s',time());

		//echo "hey";
		// $sql= "insert into `PgePrcs` ( PgeId, PrcsId, Dte, Tme, TmeTkn, UsrId, Ocr, Cv) values (
		// (select max(PgeId) from Pge), (select max(PrcsId) from Prcs),'$date','$curr_time', '$tmetkn', '$usrid', '$outputocr', '$outputopencv')";
		$sql= "INSERT into `PgePrcs` ( PgeId, PrcsId, TmeTkn, Ocr, Cv, UsrId, Dte) values (
		  (select max(PgeId) from Pge), (select max(PrcsId) from Prcs), '$tmetkn', '$outputocr', '$outputopencv', '$usrid', '$date')";

  		$result = mysqli_query($con, $sql);
	}
	elseif($prcs == "cv")
	{
		$cmdocr = "";
		$cmdcv = "darknet detect cfg/yolov4.cfg yolov4.weights <filename> -thresh 0.25 2>nul";
		$sql = "insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('$prcs', '$cmdocr', '$cmdcv')"; //insert the values of prcs time taken here
		$result = mysqli_query($con, $sql);

		//$command = "/var/www/html/adfs/script.py3 ". $nme ."";
		$start_time = microtime(true);
		$outputopencv = shell_exec("/var/www/html/adfs/script.py3 '".$nme."'");
		$end_time = microtime(true);
		$outputopencv = stripslashes($outputopencv);    // removes backslashes
		$outputopencv = mysqli_real_escape_string($con,$outputopencv);
		$outputocr = "";
		$date = date("Y-m-d");
		$date = date("Y-m-d",strtotime($date));
	    $curr_time = date('Y-d-m H:i:s',time());
		$tmetkn = strval($end_time - $start_time);
		//echo $outputopencv;
		// $sql= "insert into `PgePrcs` ( PgeId, PrcsId, Dte, Tme, TmeTkn, UsrId, Ocr, Cv) values (
		// 	(select max(PgeId) from Pge), (select max(PrcsId) from Prcs),'$date','$curr_time', '$tmetkn', '$usrid', '$outputocr', '$outputopencv')";
		$sql= "INSERT into `PgePrcs` ( PgeId, PrcsId, TmeTkn, Ocr, Cv, UsrId, Dte) values (
			(select max(PgeId) from Pge), (select max(PrcsId) from Prcs), '$tmetkn', '$outputocr', '$outputopencv', '$usrid', '$date')";
  		$result = mysqli_query($con, $sql);
	}
	elseif($prcs == "both")
	{
		$cmdocr = "pytesseract.image_to_string(Image.open(filename))";
		$cmdcv = "./darknet detect cfg/yolov4.cfg yolov4.weights <filename> -thresh 0.25 2>nul";
		$sql = "insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('$prcs', '$cmdocr', '$cmdocr')"; //insert the values of prcs time taken here
		$result = mysqli_query($con, $sql);

		//$command1 = "/var/www/html/adfs/first.py3 ". $nme ."";
		//$command2 = "/var/www/html/adfs/script.py3 ". $nme ."";
		$start_time = microtime(true);
		$outputocr = shell_exec("/var/www/html/adfs/first.py3 '".$nme."'");
		$outputopencv = shell_exec("/var/www/html/adfs/script.py3 '".$nme."'");
		$end_time = microtime(true);
		$outputocr = stripslashes($outputocr);    // removes backslashes
		$outputocr = mysqli_real_escape_string($con,$outputocr);
		$outputopencv = stripslashes($outputopencv);    // removes backslashes
		$outputopencv = mysqli_real_escape_string($con,$outputopencv);
		$date = date("Y-m-d");
		$date = date("Y-m-d",strtotime($date));
	    $curr_time = date('Y-d-m H:i:s',time());
		$tmetkn = strval($end_time - $start_time);
		//echo $outputopencv;
		//echo $outputocr;
		// $sql= "insert into `PgePrcs` ( PgeId, PrcsId, Dte, Tme, TmeTkn, UsrId, Ocr, Cv) values (
		// 	(select max(PgeId) from Pge), (select max(PrcsId) from Prcs),'$date','$curr_time', '$tmetkn', '$usrid', '$outputocr', '$outputopencv')";
		$sql= "INSERT into `PgePrcs` ( PgeId, PrcsId, TmeTkn, Ocr, Cv, UsrId, Dte) values (
			(select max(PgeId) from Pge), (select max(PrcsId) from Prcs), '$tmetkn', '$outputocr', '$outputopencv', '$usrid', '$date')";
  		$result = mysqli_query($con, $sql);
	} 
}

function update_tables($nme,$clsdscrptn,$typdscrptn,$btchdscrptn, $prcs, $usrid, $mdfd, $con)
{
	$sql="select * from `Dcmnt` where  DcmntNme = '$nme' LIMIT 1";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $clsid=$row['DcmntClsId'];
    $typid=$row['DcmntTypId'];
    $btchid=$row['BtchId'];
	$dcmntid=$row['DcmntId'];
    
  
    $sql =" UPDATE `Dcmnt` SET Mdfd = '$mdfd' WHERE DcmntNme ='$nme'";
	$result = mysqli_query($con, $sql);

	$sql=" UPDATE `DcmntCls` SET DcmntClsDscrptn = '$clsdscrptn' WHERE DcmntClsId='$clsid';";
    $result = mysqli_query($con, $sql);
    
	$sql=" UPDATE `DcmntTyp` SET DcmntTypDscrptn = '$typdscrptn' WHERE DcmntTypId='$typid';";
    $result = mysqli_query($con, $sql);
	
	$sql=" UPDATE `Btch` SET BtchDscrptn='$btchdscrptn' WHERE BtchId = '$btchid';";
    $result = mysqli_query($con, $sql);

	$sql1="SELECT * FROM `Pge` WHERE DcmntId = '$dcmntid'";
	$result1 = mysqli_query($con, $sql1);
	while ($row1 = mysqli_fetch_assoc($result1)) 
	{
		$temp=$row1['PgeId'];
		$sql2= "SELECT * FROM `PgePrcs` WHERE PgeId = '$temp'";
		$result2 = mysqli_query($con, $sql2);
		$row2 = mysqli_fetch_assoc($result2);
		$prcsid=$row2['PrcsId'];
		
		$temp = $row1['PgeId'];
		$sql4="DELETE FROM `PgePrcs` WHERE PgeId = '$temp'";
		$result4 = mysqli_query($con, $sql4);

		$sql3="DELETE FROM `Prcs` WHERE PrcsId = '$prcsid'";
		$result3 = mysqli_query($con, $sql3);
		
			
	}
	$sql = "DELETE FROM `Pge` WHERE DcmntId = '$dcmntid'";
	$result = mysqli_query($con, $sql);
        
	$page_number = 1;
	$sql="insert into `Pge` (DcmntId, PgeNmbr) values(
		 $dcmntid,
		'$page_number')"; //insert page number here, set to one as of now
	$result = mysqli_query($con, $sql);

	if($prcs == "ocr")
	{
		$cmdocr = "pytesseract.image_to_string(Image.open(filename))";
		$cmdcv = "";
		$sql = "insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('$prcs', '$cmdocr', '$cmdcv')"; //insert the values of prcs time taken here
		$result = mysqli_query($con, $sql);

		//$command = "/var/www/html/adfs/first.py3 ". $nme ."";
		$start_time = microtime(true); 
		$outputocr = shell_exec("/var/www/html/adfs/first.py3 '".$nme."'");
		//$outputopencv = shell_exec("/var/www/html/adfs/script.py3 '".$nme."'");
		$end_time = microtime(true);
		$outputocr = stripslashes($outputocr);    // removes backslashes
		$outputocr = mysqli_real_escape_string($con,$outputocr);
		$outputopencv = "";
		$tmetkn = strval($end_time - $start_time);
		//echo $tmetkn;
		//echo $outputocr;
		$date = date("Y-m-d");
		$date=date("Y-m-d",strtotime($date));
	    $curr_time = date('Y-d-m H:i:s',time());

		// $sql= "insert into `PgePrcs` ( PgeId, PrcsId, Dte, Tme, TmeTkn, UsrId, Ocr, Cv) values (
		// (select max(PgeId) from Pge), (select max(PrcsId) from Prcs),'$date','$curr_time', '$tmetkn', '$usrid', '$outputocr', '$outputopencv')";
		$sql= "INSERT into `PgePrcs` ( PgeId, PrcsId, TmeTkn, Ocr, Cv, UsrId, Dte) values (
			(select max(PgeId) from Pge), (select max(PrcsId) from Prcs), '$tmetkn', '$outputocr', '$outputopencv', '$usrid', '$date')";
		$result = mysqli_query($con, $sql);
	}
	elseif($prcs == "cv")
	{
		$cmdocr = "";
		$cmdcv = "darknet detect cfg/yolov4.cfg yolov4.weights <filename> -thresh 0.25 2>nul";
		$sql = "insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('$prcs', '$cmdocr', '$cmdcv')"; //insert the values of prcs time taken here
		$result = mysqli_query($con, $sql);

		//$command = "/var/www/html/adfs/script.py3 ". $nme ."";
		$start_time = microtime(true);
		//$outputocr = shell_exec("/var/www/html/adfs/first.py3 '".$nme."'");
		$outputopencv = shell_exec("/var/www/html/adfs/script.py3 '".$nme."'");
		$end_time = microtime(true);
		$outputopencv = stripslashes($outputopencv);    // removes backslashes
		$outputopencv = mysqli_real_escape_string($con,$outputopencv);
		$outputocr = "";
		$date = date("Y-m-d");
		$date = date("Y-m-d",strtotime($date));
	    $curr_time = date('Y-d-m H:i:s',time());
		$tmetkn = strval($end_time - $start_time);
		// $sql= "insert into `PgePrcs` ( PgeId, PrcsId, Dte, Tme, TmeTkn, UsrId, Ocr, Cv) values (
		// 	(select max(PgeId) from Pge), (select max(PrcsId) from Prcs),'$date','$curr_time', '$tmetkn', '$usrid', '$outputocr', '$outputopencv')";
		$sql= "INSERT into `PgePrcs` ( PgeId, PrcsId, TmeTkn, Ocr, Cv, UsrId, Dte) values (
			(select max(PgeId) from Pge), (select max(PrcsId) from Prcs), '$tmetkn', '$outputocr', '$outputopencv', '$usrid', '$date')";
  		$result = mysqli_query($con, $sql);
	}
	elseif($prcs == "both")
	{
		$cmdocr = "pytesseract.image_to_string(Image.open(filename))";
		$cmdcv = "./darknet detect cfg/yolov4.cfg yolov4.weights <filename> -thresh 0.25 2>nul";
		$sql = "insert into `Prcs`(PrcsDscrptn, PrcsCmdOcr, PrcsCmdCv) values ('$prcs', '$cmdocr', '$cmdocr')"; //insert the values of prcs time taken here
		$result = mysqli_query($con, $sql);

		//$command1 = "/var/www/html/adfs/first.py3 ". $nme ."";
		//$command2 = "/var/www/html/adfs/script.py3 ". $nme ."";
		$start_time = microtime(true);
		$outputocr = shell_exec("/var/www/html/adfs/first.py3 '".$nme."'");
		$outputopencv = shell_exec("/var/www/html/adfs/script.py3 '".$nme."'");
		$end_time = microtime(true);
		$outputocr = stripslashes($outputocr);    // removes backslashes
		$outputocr = mysqli_real_escape_string($con,$outputocr);
		$outputopencv = stripslashes($outputopencv);    // removes backslashes
		$outputopencv = mysqli_real_escape_string($con,$outputopencv);
		$date = date("Y-m-d");
		$date = date("Y-m-d",strtotime($date));
	    $curr_time = date('Y-d-m H:i:s',time());
		$tmetkn = strval($end_time - $start_time);
		//echo $outputopencv;
		//echo $outputocr;
		// $sql= "insert into `PgePrcs` ( PgeId, PrcsId, Dte, Tme, TmeTkn, UsrId, Ocr, Cv) values (
		// 	(select max(PgeId) from Pge), (select max(PrcsId) from Prcs),'$date','$curr_time', '$tmetkn', '$usrid', '$outputocr', '$outputopencv')";
		$sql= "INSERT into `PgePrcs` ( PgeId, PrcsId, TmeTkn, Ocr, Cv, UsrId, Dte) values (
			(select max(PgeId) from Pge), (select max(PrcsId) from Prcs), '$tmetkn', '$outputocr', '$outputopencv', '$usrid', '$date')";
  		$result = mysqli_query($con, $sql);
	}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          

}
   
// Check if form was submited
if(isset($_POST['submit'])) {

	// Configure upload directory and allowed file types
	$upload_dir = 'uploads'.DIRECTORY_SEPARATOR;
	$allowed_types = array('jpg', 'png', 'jpeg', 'gif');
	
	// Define maxsize for files i.e 2MB
	$maxsize = 2 * 1024 * 1024*10;

	// Checks if user sent an empty form
	if(!empty(array_filter($_FILES['files']['name']))) 
	{
		$datevar=$_POST[hidden1];
		$timevar=$_POST[hidden2];

		$datearr= explode('%',$datevar);
		$timarr=explode('%',$timevar);

		array_pop($datearr);
		array_pop($timarr);
		$count=0;

		foreach ($_FILES['files']['tmp_name'] as $key => $value) 
		{
			$file_tmpname = $_FILES['files']['tmp_name'][$key];
			$file_name = $_FILES['files']['name'][$key];
			$file_size = $_FILES['files']['size'][$key];
			$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$filepath = $upload_dir.$file_name;
			// Check file type is allowed or not
			if(in_array(strtolower($file_ext), $allowed_types)) 
			{
				// Verify file size - 2MB max
				if ($file_size > $maxsize)		
					echo "<div class='login-box'>
					<h2> Error: File size is larger than the allowed limit.</h2><br/>
					<span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
					<span></span>
					<span></span>
					<span></span>upload another file</a><br>
	  				<span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
					<span></span>
					<span></span>
					<span></span>logout</a><br>
					</div>";
				// If file with name already exist then append time in
				// front of name of the file to avoid overwriting of file
				if(file_exists($filepath)) //if file with the given name already exists in the data base
				{
					//echo "here";
					//echo $file_name;
					$sql= "SELECT * FROM `Dcmnt` WHERE DcmntNme ='$file_name' LIMIT 1";
					$result = mysqli_query($con, $sql);
					//print_r($result); //debug
					$row = mysqli_fetch_assoc($result);
					$DATA=$row['Mdfd'];
					//$DATA=$rows['date']. ' ' .$rows['time'];
					$ISKADATA = $datearr[$count]. ' ' .$timarr[$count];
					//echo $ISKADATA;
					//echo $DATA;
					if($DATA  == $ISKADATA) //if the Mdfd of both the file matches do not allow upload
					{
						echo "<div class='login-box'>
						<h2>Sorry file alreaday exists</h2><br/>
						<span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
						<span></span>
						<span></span>
						<span></span>upload another file</a><br>
		  				<span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
						<span></span>
						<span></span>
						<span></span>logout</a><br>
						</div>";
					}
					else //overwrite the file otherwise
					{
						unlink($upload_dir.$file_name);
						if( move_uploaded_file($file_tmpname, $filepath)) 
						{
							$nme=$file_name;
							$clsdscrptn=$_POST['dcmntcls'];
							$typdscrptn=$_POST['dcmnttyp'];
							$btchdscrptn=$_POST['btch'];
							$prcs=$_POST['prc'];
							$mdfd=$ISKADATA;
							$currusername = $_SESSION['username'];
							$sql_temp = "select UsrId from `usr` where username = '$currusername' LIMIT 1";
							$result_temp   = mysqli_query($con, $sql_temp);
							$row = mysqli_fetch_array($result_temp);
							$usrid=$row['UsrId'];
							//function update_tables($nme,$clsdscrptn,$typdscrptn,$btchdscrptn, $prcs, $usrid, $mdfd)
							update_tables($nme,$clsdscrptn,$typdscrptn,$btchdscrptn, $prcs, $usrid, $mdfd,$con);
					 		$count=$count+1;
							
					 		//echo "{$file_name} successfully uploaded <br />";
	                    	echo "<div class='login-box'>
							<h2>The file has been overwritten </h2><br/>
							<span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
							<span></span>
							<span></span>
							<span></span>upload another file</a><br>
			  				<span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
							<span></span>
							<span></span>
							<span></span>logout</a><br>
							</div>";
						}
						
					 }
					
				}
				else // New file being uploaded (does not exist in the database so far)
				{
					//echo $file_tmpname;
					//echo $filepath;
					if( move_uploaded_file($file_tmpname, $filepath)) 
					{
						$nme=$file_name;
						$pth=$upload_dir;
						$clsdscrptn=$_POST['dcmntcls'];
						$typdscrptn=$_POST['dcmnttyp'];
						$btchdscrptn=$_POST['btch'];
						$prcs=$_POST['prc'];
						// echo $clsdscrptn;
						// echo $typdscrptn;
						// echo $btchdscrptn;
						// echo $prcs;
						$mdfd= $datearr[$count]. ' ' .$timarr[$count];
				        $currusername = $_SESSION['username'];
                 		$sql_temp = "select UsrId from `usr` where username = '$currusername' LIMIT 1";
  						$result_temp = mysqli_query($con, $sql_temp);
  						$row = mysqli_fetch_array($result_temp);
  						$usrid=$row['UsrId'];
						//echo $usrid;
						fill_tables($clsdscrptn, $typdscrptn, $btchdscrptn, $nme, $pth, $prcs, $mdfd, $usrid, $con);
						//$sql="insert into `test`(filename,date,text) VALUES ('$file_name','$datearr[$count]','$timarr[$count]')";
						//$result = mysqli_query($con, $sql) or die(mysql_error());
						$count=$count+1;
						echo "<div class='login-box'>
						<h2>{$file_name} successfully uploaded</h2> <br/>
						<span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
						<span></span>
						<span></span>
						<span></span>upload another file</a><br>
		  				<span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
						<span></span>
						<span></span>
						<span></span>logout</a><br>
						</div>";
					}
					else 
					{					
						echo "<div class='login-box'>
						<h2>Error uploading {$file_name}</h2><br/>
						<span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
						<span></span>
						<span></span>
						<span></span>upload another file</a><br>
		  				<span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
						<span></span>
						<span></span>
						<span></span>logout</a><br>
						</div>";
					}
				}
			}
			else 
			{
				// If file extension not valid
				echo "<div class='login-box'> 
				<h2>Error uploading {$file_name}</h2> <br/> 
				<h2>({$file_ext} file type is not allowed)</h2><br/>
				<span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
				<span></span>
				<span></span>
				<span></span>upload another file</a><br>
  				<span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
				<span></span>
				<span></span>
				<span></span>logout</a><br>
				</div>";
			}
		}
	}

	else 
	{
		echo "<div class='login-box'> 
		No files selected.<br/>
		    <span style='color:white'>Click here to </span> <a href='dashboard.php'style='color:white'> <span></span>
                   <span></span>
                   <span></span>
                   <span></span>upload another file</a><br>
     		<span style='color:white'>Click here to </span> <a href='logout.php'style='color:white'> <span></span>
                   <span></span>
                   <span></span>
                   <span></span>logout</a><br>
		</div>";
	}
	
}

?>		