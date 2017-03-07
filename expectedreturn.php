<?php
  session_start();
  require 'connect.inc.php';
?>
<!DOCTYPE html>
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
        <li><a href="https://web.njit.edu/~ajw38/expectedreturn.php">View 'Expected Return'</a></li>
        <li><a href="https://web.njit.edu/~ajw38/cashtransactionspage.php">Cash Transaction History</a></li>
        <li><a href="https://web.njit.edu/~ajw38/withdraw.php">Withdraw</a></li>
        <li><a href="https://web.njit.edu/~ajw38/deposit.php">Deposit</a></li>
        <li><a href="https://web.njit.edu/~ajw38/checkbalance.php">Check Balance</a></li>
     </ul>
     <h1>Current Expected Return (Current portfolio value - total price paid for all currently owned stocks):</h1>
     <?php
        $id=$_SESSION['user_id'];
        $sum=0;
          $sql = "SELECT * FROM `ShareTransactions` WHERE `userId`=$id";
          if($query_run=mysql_query($sql)){
             $query_num_rows=mysql_num_rows($query_run);        
             if($query_num_rows==0){
                echo "Error";  
             }
             else{
                while($row = mysql_fetch_row($query_run)){
                  $numOfShares=floatVal($row[2]);
                  $currency=$row[6];
                  $transactionType=$row[8];
                  if(strcmp($currency,"INR")==0){
                      $shareValue=0.015005*$row[3];
                  }
                  else{
                     $shareValue=$row[3];
                  }
                  if(strcmp($transactionType,"buy")==0){
                    $sum=$sum+($numOfShares*$shareValue);
                  }
                  else{
                    $sum=$sum-($numOfShares*$shareValue);
                  }
              }
            }
         }   
         $sql2="SELECT * FROM  `ShareStatus` WHERE `userId`=$id";
         if($query_run2=mysql_query($sql2)){
           $query_num_rows2=mysql_num_rows($query_run2);
           if($query_num_rows2==0){
             echo "Error";
           }   
           else{
             $stockFormat = "xsnl1d1t1c1hgwx";
             $host = "http://in.finance.yahoo.com/d/quotes.csv";
             $current_sum=0;
             while($row2 = mysql_fetch_row($query_run2)){
               $curSharCodeStockList=$row2[2];
               $curSharCodeStockList=substr($curSharCodeStockList,1,-1);
               if(strcmp($curSharCodeStockList,"ATULAUTO")!=0 && strcmp($curSharCodeStockList,"VZ")==0){
                 $curNumShare=$row2[3];
                 $curExPlace=$row2[5];
                  $output = fopen('php://memory');
                   $googleSite = ("https://www.google.com/finance/info?q=NSE:".$curSharCodeStockList."&f=etl");
                    fputcsv($output, $googleSite);
                    $googleParse = file("https://www.google.com/finance/info?q=NSE:".$curSharCodeStockList."&f=etl"); 
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
                   $googleParse[6] = str_replace($garbageToReplace, "", $googleParse[6]);
                    $curStockPrice =  $googleParse[6];
                    
                 if(strcmp($curExPlace,"BNE")==0 || strcmp($curExPlace,"SNE")==0){
                      $amount = urlencode($amount);
                      $from_Currency = urlencode("INR");
                      $to_Currency = urlencode("USD");
                      $get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency");
                      $get = explode("<span class=bld>",$get);
                      $get = explode("</span>",$get[1]);  
                      $converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
                      $curStockPrice = round($converted_amount,2);
                 }
                 $curStockPrice=$curStockPrice*$curNumShare;
                 $current_sum=$current_sum+$curStockPrice;
               }
            }
          }
          $expectedReturn =$current_sum-$sum;
          echo "('$') $expectedReturn";
          }?>
  </body>
</html>