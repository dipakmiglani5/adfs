
    <style>
        body{
          background: linear-gradient(#141e30, #243b55);
        }
        table {
            margin: 0 auto;
            font-size: large;
            border: 1px solid black;
        }
  
        h1 {
            text-align: center;
            color: #006600; 
            font-size: xx-large;
            font-family: 'Gill Sans', 'Gill Sans MT', 
            ' Calibri', 'Trebuchet MS', 'sans-serif';
        }
        th{
          background-color: #E4F5D4;
            border: 1px solid black;

        }
  
        td {
            background-color: #E4F5D4;
            border: 1px solid black;
        }
  
        th,
        td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
  
        td {
            font-weight: lighter;
        }
    </style>


<?php

require('db.php');

 if (isset($_REQUEST['search'])) {
               $ketword = stripslashes($_REQUEST['search']);
               $ketword = mysqli_real_escape_string($con, $ketword);
             //  $sql = "SELECT * FROM `img` WHERE ocr LIKE '%$ketword%'" ;
               // $result   = mysqli_query($con, $sql);
              }
        
?>


<table>
            <tr>
                <th>Filename</th>
                <th>OCR</th>
                <th>OpenCV</th>
                <th>Time</th>
                <th>Date</th>
          
            </tr>
            <!-- PHP CODE TO FETCH DATA FROM ROWS-->
            <?php   // LOOP TILL END OF DATA 

                //$sql ="";
            
               $ketword = stripslashes($_REQUEST['search']);
               $ketword = mysqli_real_escape_string($con, $ketword);

               if($_POST['searchtype']=='ocr'){
               $sql = "SELECT * FROM `PgePrcs` WHERE Ocr LIKE '%$ketword%'" ;
                }
                elseif ($_POST['searchtype']=='opencv') {
                  $sql = "SELECT * FROM `PgePrcs` WHERE Cv LIKE '%$ketword%'" ;
                  # code...
                }
                else
                {
                   $sql = "SELECT * FROM `PgePrcs` WHERE Cv LIKE '%$ketword%' OR  Ocr LIKE '%$ketword%'" ;
                }

                 $result   = mysqli_query($con, $sql);

                while($rows=$result->fetch_assoc())
                {

                  $id=$rows["FleIdNme"];
                  $name=$rows['FleNme'];
                  $url= "uploads/".$id;
                  $url2= $rows['FleNme'];
             ?>
            <tr>
                <!--FETCHING DATA FROM EACH 
                    ROW OF EVERY COLUMN-->
                <td><a href="<?php echo $url ?>"><?php echo $rows['FleNme'];?></a></td>
                <td><?php echo $rows['Ocr'];?></td>
                <td><?php echo $rows['Cv'];?></td>
                <td><?php echo $rows['tme'];?></td>
                <td><?php echo $rows['Dte'];?></td>
            </tr>
            <?php
                }
             ?>
        </table>


   