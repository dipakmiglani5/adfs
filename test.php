<?php
// $command_exec = escapeshellcmd("/scripts/first.py3");
// echo $command_exec;
// $output=null;
// $retval=null;
// exec($command_exec, $output, $retval);
// //echo $output;
// echo $retval;
  // $command = escapeshellcmd('first.py3');
   //$a = "/var/www/html/adfs/scripts/first.py3";
    // $output = shell_exec($a);
  // echo $output;
$command = escapeshellcmd("/var/www/html/adfs/first.py3");
//$message = exec("/var/www/html/adfs/first.py3 2>&1");
//print_r($message);
$output = shell_exec($command);
echo $output;
o
?>


