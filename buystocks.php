<?php
  session_start();
  require 'connect.inc.php';
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Portfolio Manager</title>
        <style type='text/css'>
            table,
            th,
            td {
                border: 1px solid black;
            }
            
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
            <li><a href="https://web.njit.edu/~ajw38/expectedreturn.php">View 'Expected Return'</a></li>
            <li><a href="https://web.njit.edu/~ajw38/cashtransactionspage.php">Cash Transaction History</a></li>
            <li><a href="https://web.njit.edu/~ajw38/withdraw.php">Withdraw</a></li>
            <li><a href="https://web.njit.edu/~ajw38/deposit.php">Deposit</a></li>
            <li><a href="https://web.njit.edu/~ajw38/checkbalance.php">Check Balance</a></li>
        </ul>
        <h1>Buy Stocks</h1>
        <!--<div class="welcome">-->
        <!--<div class="BuyStock">-->
        <form action="buystocks.php" method="POST">
            <table>
                <tr>
                    <th>Quote the Stock Index:
                        <input type="text" name="stock">
                    </th>
                    <th>Number of Stock :
                        <input type="number" name="NumOfStock">
                    </th>
                   <!-- <th>Stock Exchange Pl :
                        <select name="StockExchangePl2">
                            <datalist id="StockExchangePl">
                                <option value="USEXPL">US MARKET</option>
                                <option value="INDEXPL">NSE (INDIA MARKET)</option>
                            </datalist>
                        </select>
                    </th>-->
                </tr>
            </table>
            <input type="submit" name="Buy Stock"> </form>
        <?php      
      session_start();

$stockList =  $_POST['stock'];
$numOfStocks = $_POST['NumOfStock'];
$explace = $_POST['StockExchangePl2'];

$buy = "buy";
$stockFormat = "xsnl1d1t1c1hgwx";
$host = "http://in.finance.yahoo.com/d/quotes.csv";
$date = "";     

$useId = $_SESSION['user_id']; 
 
$requestUrl = $host."?s=".$stockList."&amp;amp;amp;amp;f=".$stockFormat."&amp;amp;amp;amp;e=.csv";

$output = fopen('php://memory');
$googleSite = ("https://www.google.com/finance/info?q=NSE:".$stockList."&f=etl");
fputcsv($output, $googleSite);
$googleParse = file("https://www.google.com/finance/info?q=NSE:".$stockList."&f=etl"); 

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
  
}   
         $dbhost ="YOURHOST";  
         $dbuser="YOURDBUSERNAME"; 
         $dbpass="YOURDBPASSWORD"; 
         $conn = mysql_connect($dbhost, $dbuser, $dbpass);
      
         if(! $conn ) {
         die('Could not connect: ' . mysql_error());
         }
         mysql_select_db('YOURDATABASENAME');     
        
    
     
      

$googleParse[0] = "";
$googleParse[1] = "";
$googleParse[2] = "";


$garbageToReplace = array('"id": ',
                          ',"t" : ',
                          ',"e" : ',
                          ',"l" : ',
                          ',"l_fix" : ',
                          ',"l_cur" : ',
                          ',"s": ',
                          ',"ltt":',
                          ',"lt" : ',
                          ',"lt_dts" : ',
                          ',"c" : ',
                          ',"c_fix" : ',
                          ',"cp" : ',
                          ',"cp_fix" : ',
                          ',"ccol" : ',
                          ',"pcls_fix" : ',
                          '"');


$googleParse[3] = str_replace($garbageToReplace, "", $googleParse[3]);
$googleParse[4] = str_replace($garbageToReplace, "", $googleParse[4]);
$googleParse[5] = str_replace($garbageToReplace, "", $googleParse[5]);
$googleParse[6] = str_replace($garbageToReplace, "", $googleParse[6]);
$googleParse[7] = str_replace($garbageToReplace, "", $googleParse[7]);
$googleParse[8] = str_replace($garbageToReplace, "", $googleParse[8]);
$googleParse[9] = str_replace($garbageToReplace, "", $googleParse[9]);
$googleParse[10] = str_replace($garbageToReplace, "", $googleParse[10]);
$googleParse[11] = str_replace($garbageToReplace, "", $googleParse[11]);
$googleParse[12] = str_replace($garbageToReplace, "", $googleParse[12]);
$googleParse[13] = str_replace($garbageToReplace, "", $googleParse[13]);
$googleParse[14] = str_replace($garbageToReplace, "", $googleParse[14]);
$googleParse[15] = str_replace($garbageToReplace, "", $googleParse[15]);
$googleParse[16] = str_replace($garbageToReplace, "", $googleParse[16]);
$googleParse[17] = str_replace($garbageToReplace, "", $googleParse[17]);
$googleParse[18] = str_replace($garbageToReplace, "", $googleParse[18]);

$googleParse[19] = "";
$googleParse[20] = "";     
       
   
          
          function currency($from_Currency,$to_Currency,$amount) {
            $amount = urlencode($amount);
            $from_Currency = urlencode($from_Currency);
            $to_Currency = urlencode($to_Currency);
            $get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency");
            $get = explode("<span class=bld>",$get);
            $get = explode("</span>",$get[1]);  
            $converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
            return round($converted_amount,2);
             }
            function cleanData($a) {

            if(is_numeric($a)) {

            $a = preg_replace('/[^0-9,]/s', '', $a);
            }

            return $a;

        }
        $totalusershare=array();
        $exPlace2 = "NSE";
        $exPlace3 = "BSE";
        $rowshareCodes = array();
        $rowshareCodesForeign = array();
         $sqlSelect = "SELECT shareNames FROM ShareStatus WHERE  userId = '$useId'";
        $totalSelect = mysql_query( $sqlSelect, $conn ); 
        if(! $totalSelect) {
          die('Could not get data: ' . mysql_error());
         }
         while($rowS = mysql_fetch_array($totalSelect, MYSQL_ASSOC)) {
          $totalUserStocks = $rowS[0]; 
          array_push($totalusershare,$rowS[0]);
          echo $rowM[0];
       }
      
    
       if ((sizeof($totalusershare) <= 10) || (array_key_exists($stockList,$totalusershare))){
       
       if(strcmp($exPlace2,trim($googleParse[5]))==0 || strcmp($exPlace3,trim($googleParse[5]))==0 ){        
       
        $sqlshareCodes = "SELECT * FROM ShareStatus WHERE exchangePlace = '$googleParse[5]' AND userId = '$useId'";
       $retvalshareCodes = mysql_query( $sqlshareCodes, $conn );
         if(! $retvalshareCodes ) {
            
          die('Could not get data: ' . mysql_error());
         }
      
        while($rowshareCodes = mysql_fetch_array($retvalshareCodes, MYSQL_ASSOC)) {
      
            array_push($rowshareCodesForeign,$rowshareCodes['shareCode']);
           
          }
        
        
       if (sizeof($rowshareCodesForeign) <= 3){
        $shareUnitPrice = currency("INR", "USD", floatval(preg_replace('/[^\d.]/', '', $googleParse[6])));
       $foreignCurrency = "INR";
       $priceInForeignCurr= $googleParse[6];
       $shareCode =  $googleParse[4];
       $shareNames =  $googleParse[4];
       $exPlace = $googleParse[5]; 
       $currency = "USD";  
       }    
           
        else {
            echo("You have reached the maximum foreign stocks");
        }   
              
      }else {  
       $foreignCurrency = " ";
       $priceInForeignCurr= "";
       $shareCode =  $googleParse[4];
       $shareNames =  $googleParse[4];
       $shareUnitPrice =  $googleParse[6];
       $exPlace = $googleParse[5]; 
       $currency = "USD";            
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
          
          if (empty($shareCode)){
       
           
     }else{
       $sql = "INSERT INTO ShareTransactions ". "(shareNames,numOfShares, unitPrice, exchangePlace,exchangePlCurrency,userId,transactionType,shareCode,ForeignCurrency,UnitPriceForeignCurrency)"."VALUES('$shareNames','$numOfStocks','$shareUnitPrice','$exPlace','$currency','$useId','$buy','$shareCode','$foreignCurrency','$priceInForeignCurr')"; 
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
             $sqlInst = "INSERT INTO ShareStatus ". "(shareNames,shareCode,numOfShares,userId,exchangePlace)"."VALUES('$shareNames','$shareCode','$numOfStocks','$useId','$exPlace')";  
             $insShare = mysql_query( $sqlInst, $conn );
             if(! $insShare ) {
               die('Could not enter data: ' . mysql_error()); 
         }
         } 
       
        }
     
      mysql_close($conn); 
      
     }
       }
       else{
           echo("You have reached the maximum of 10 distinct stocks");
       }   
?> </body>

    </html>