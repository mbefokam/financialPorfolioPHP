
       

<!--/*echo'<table border="1" >
<th > Stock Name </th><th > Stock Symbol </th><th> Number of Stock(s) </th><th>  Unit Price </th><th> Transaction  Price </th><th> Transaction Date </th><th> Exchange Place </th><th> Exchange Place Currency </th><th> Transaction Type </th>';
echo'<tr><td>'.$shareNames.'</td><td>'.$shareCodeR.'</td><td>'.$numOfShares.'</td><td>'.$unitPrice.'</td><td>'.$priceDb.'</td><td>'.$transactionDate.'</td><td>'.$exchangePlace.'</td><td>'.$exchangePlCurrency.'</td><td>'.$transactionType.'</td></tr>';
echo'</table>';*/
      /*   echo($row['shareNames']);
      $priceDb = $row['shareNames'] * $row['unitPrice'];
      echo'<tr><td>'.$row['shareNames'].'</td><td>'.$row['shareCode'].'</td><td>'.$row['numOfShares'].'</td><td>'.$row['unitPrice'].'</td><td>'.$priceDb.'</td><td>'.$row['transactionDate'].'</td><td>'.$row['exchangePlace'].'</td><td>'.$row['exchangePlCurrency'].'</td><td>'.$row['transactionType'].'</td></tr>';*/-->
             
      

<!--<!DOCTYPE html>
<html>
  <head>
    <title>Portfolio Manager</title>
    <style type='text/css'>
      table, th, td {    border: 1px solid black; }
      li {
        display: inline;
      }
    </style>
  </head>
  <body>
    <ul>
        <li><a href="https://web.njit.edu/~ajw38/index.php">Home</a></li>
        <li><a href="https://web.njit.edu/~ajw38/myportfolio.php">Portfolio</a></li>
        <li><a href="https://web.njit.edu/~ajw38/transactionspage.php">Stock Transaction History</a></li>
        <li><a href="https://web.njit.edu/~ajw38/buystocks.php">Buy Stocks</a></li>
        <li><a href="https://web.njit.edu/~ajw38/sellstocks.php">Sell Stocks</a></li>
        <li><a href="https://web.njit.edu/~ajw38/transactionreports.php">Transaction Reports</a></li>
        <li><a href="https://web.njit.edu/~ajw38/expectedreturn.php">View 'Expected Return'</a></li>
        <li><a href="https://web.njit.edu/~ajw38/cashtransactionspage.php">Cash Transaction History</a></li>
        <li><a href="https://web.njit.edu/~ajw38/withdraw.php">Withdraw</a></li>
        <li><a href="https://web.njit.edu/~ajw38/deposit.php">Deposit</a></li>
        <li><a href="https://web.njit.edu/~ajw38/checkbalance.php">Check Balance</a></li>
     </ul>
      <h1>Transaction Reports:</h1>-->
      
<?php
      
      session_start();
/*require 'core.inc.php';      
require 'connect.inc.php';*/

//$stockList = "GOOG,YHOO,T,AAPL";
$stockList =  $_POST['stock'];
$numOfStocks = $_POST['NumOfStock'];
$explace = $_POST['expl'];
$buy = "buy";
$stockFormat = "xsnl1d1t1c1hgwx";
$host = "http://finance.yahoo.com/d/quotes.csv";
$date = "";

$useId = $_SESSION['user_id']; 
 
//$requestUrl = $host."?x=".$explace."&s=MNS&amp;amp;amp;amp;f=".$stockFormat."&amp;amp;amp;amp;e=.csv";
//$requestUrl = $host."?x=".$explace."&s=".$stockList."&amp;amp;amp;amp;f=".$stockFormat."&amp;amp;amp;amp;e=.csv";
$requestUrl = $host."?s=".$stockList."&amp;amp;amp;amp;f=".$stockFormat."&amp;amp;amp;amp;e=.csv";
 
// Pull data (download CSV as file)
$filesize=2000;
$handle = fopen($requestUrl, "r");
$raw = fread($handle, $filesize);
fclose($handle);
 
// Split results, trim way the extra line break at the end
$quotes = explode("\n",trim($raw));
 
foreach($quotes as $quoteraw) {
$quoteraw = str_replace(", I", " I", $quoteraw);
$quote = explode(",", $quoteraw);
$price = $quote[3] * $numOfStocks;
$day = $quote[4].$quote[5];
$time = $quote[5];
$date = $day + $time;
  
}         $checkIStockSymbol= $quote[1];
          $shareNamesS = $quote[2];
          $shareUnitPriceS = $quote[3];
          $exPlaceS = $quote[0];
          $user ="tonylee";
      
         $dbhost ="sql2.njit.edu";  
         $dbuser="kn259"; 
         $dbpass="7bI1DDsD"; 
         $conn = mysql_connect($dbhost, $dbuser, $dbpass);
      
         if(! $conn ) {
         die('Could not connect: ' . mysql_error());
         }
         mysql_select_db('kn259');
     
        
      // str_replace('"', "", $string);
      
        $shareCode = preg_replace("/<!--.*?-->/", "", $checkIStockSymbol);
       $shareNames = preg_replace("/<!--.*?-->/", "", $shareNamesS);
       $shareUnitPrice = preg_replace("/<!--.*?-->/", "", $shareUnitPriceS);
       $exPlace = preg_replace("/<!--.*?-->/", "", $exPlaceS);
       /*$shareCode = stripslashes($checkIStockSymbol);
       $shareNames = stripslashes($shareNamesS);
       $shareUnitPrice = stripslashes($shareUnitPriceS);
       $exPlace = stripslashes($exPlaceS);*/
  
       /*  $sqlUserId = "SELECT * FROM Users WHERE username = $username";*/
      
      if($quote[0] !==''){
         if ($quote[0]=="NSE"){
          $currency = "INR";
      }
      else{
          $currency = "USD";
      }  
      }else {
          $currency = " ";
      }
      
      
      $sqlMoney = "SELECT totalMoney FROM MoneyBalance WHERE userId = '$useId'";
        $retvalM = mysql_query( $sqlMoney, $conn );
       if(! $retvalM ) {
          die('Could not get data: ' . mysql_error());
         }
      while($rowM = mysql_fetch_array($retvalM, MYSQL_ASSOC)) {
          $totalMoney = $rowM['totalMoney'];
      }
      
      $checkBalance = $totalMoney - $price;
      if ($totalMoney < $price or $checkBalance < 0 ){
          echo (" You don t have enough Money into your account for this transaction\n");
          echo(" You need to deposit : $ ");
          echo($checkBalance);
          echo(" to complete the transaction");
      }
      else {
          
           $sqlupdM = "UPDATE MoneyBalance SET totalMoney = '$checkBalance' WHERE userId = '$useId' " ;
             $updShareM = mysql_query( $sqlupdM, $conn );
           
             if(! $updShareM )
             {
                 die('Could not update data: ' . mysql_error());
             }
          
          if (empty($checkIStockSymbol)){
       echo("This is empty");
           
     }else{
       $sql = "INSERT INTO ShareTransactions ". "(shareNames,numOfShares, unitPrice, exchangePlace,exchangePlCurrency,userId,transactionType,shareCode)"."VALUES('$shareNames','$numOfStocks','$shareUnitPrice','$exPlace','$currency','$useId','$buy','$shareCode')"; 
     $checkStoredData = mysql_query( $sql, $conn );
     if(! $checkStoredData ) {
               die('Could not enter data: ' . mysql_error()); 
         }
         
       
       /*$sqlshareCode = "SELECT * FROM ShareStatus WHERE shareCode = YAHOO and userId = 728";*/
       $sqlshareCode = "SELECT * FROM ShareStatus WHERE shareCode = '$shareCode' AND userId = '$useId'";
      
       $retvalshareCode = mysql_query( $sqlshareCode, $conn );
         if(! $retvalshareCode ) {
            
          die('Could not get data: ' . mysql_error());
         }
      
        while($rowshareCode = mysql_fetch_array($retvalshareCode, MYSQL_ASSOC)) {
        $shareCodeDb = $rowshareCode['shareCode'];
        $numOfShareDb = $rowshareCode['numOfShares'];
        
          } 
       
         if ($shareCodeDb ==$shareCode){
          $numOfShareNew = $numOfShareDb + $numOfStocks;
         
             
           $sqlupd = "UPDATE ShareStatus SET numOfShares = '$numOfShareNew' WHERE userId = '$useId' AND shareCode = '$shareCode'" ;
             $updShare = mysql_query( $sqlupd, $conn );
           
             if(! $updShare )
             {
                 die('Could not update data: ' . mysql_error());
             }
             
         }
         else{
             $sqlInst = "INSERT INTO ShareStatus ". "(shareNames,shareCode,numOfShares,userId)"."VALUES('$shareNames','$shareCode','$numOfStocks','$useId')";  
             $insShare = mysql_query( $sqlInst, $conn );
             if(! $insShare ) {
               die('Could not enter data: ' . mysql_error()); 
         }
         } 
       
     }
          echo'<table border="1" >
         <th > Stock Name </th><th > Stock Symbol </th><th> Number of Stock(s) </th><th>  Unit Price </th><th> Transaction  Price </th><th> Transaction Date </th><th> Exchange Place </th><th> Exchange Place Currency </th><th> Transaction Type </th>';

         //  $sql2 = "SELECT * FROM ShareTransactions WHERE userId = '$userId'";
        $sql2 = "SELECT * FROM ShareTransactions WHERE userId = '$useId'";
         
         $retval = mysql_query( $sql2, $conn );
         if(! $retval ) {
          die('Could not get data: ' . mysql_error());
         }
          

        //  $transaction = array();
         while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
       
        $shareNames = $row['shareNames'];
        $shareCodeR = $row['shareCode'];
        $numOfShares = $row['numOfShares'];
        $exchangePlace = $row['exchangePlace'];
        $transactionDate = $row['transactionDate'];
        $exchangePlCurrency = $row['exchangePlCurrency'];
        $userId = $row['userId'];
        $transactionType = $row['transactionType'];
        $unitPrice = $row['unitPrice'];
        $transactionType = $row['transactionType'];
        $priceDb = $numOfShares * $unitPrice;
     echo'<tr><td>'.$shareNames.'</td><td>'.$shareCodeR.'</td><td>'.$numOfShares.'</td><td>'.$unitPrice.'</td><td>'.$priceDb.'</td><td>'.$transactionDate.'</td><td>'.$exchangePlace.'</td><td>'.$exchangePlCurrency.'</td><td>'.$transactionType.'</td></tr>';
         }
      mysql_close($conn); 
          
echo'</table>';  
      
 }
?> 
<!--</body>
</html>-->