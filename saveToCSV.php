<?php 
    session_start();
    $id=$_SESSION['user_id'];
    require 'connect.inc.php';
    header('Content-type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=myPortfolio.csv');  
    //////Connect to sql2.njit.edu
/*    $mysql_host="sql2.njit.edu"; //NJIT's student SQL server dood!
    $mysql_user="kn259"; //admin username dood!
    $mysql_pass="7bI1DDsD"; //admin password dood!


    mysql_connect($mysql_host,$mysql_user,$mysql_pass) //connect to NJIT's SQL2 dood!
      or die('Could not connect to sql2.njit.edu' . mysql_error());

    mysql_select_db('kn259') //use database 'kn259' dood!
      or die('Could not connect to the database: ' . mysql_error());
*/
    $saveToFileQuery = mysql_query("SELECT shareNames,shareCode,NumOfShares FROM ShareStatus WHERE userId=$id"); //find all info from 'Users' table dood!

    //////Save the database info to a CSV file dood!//////
    $outputFile = fopen('php://output', 'w'); // file pointer connected to the PHP output datastream dood!

    fputcsv($outputFile, array('Stock Name','Stock Code','Number of Shares'));//write column headers to file dood!

    while($currentRow = mysql_fetch_row($saveToFileQuery))//While query is going on, read each row in order and write to file dood!
      fputcsv($outputFile, $currentRow);
      
      fclose($outputFile);
?>