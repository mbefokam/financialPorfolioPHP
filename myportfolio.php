<?php
  session_start();
  $id=$_SESSION['user_id'];
  require 'connect.inc.php';
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Portfolio Manager</title>
        <style>
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
        <h1>My Portfolio</h1>
        <h2>My Stocks:</h2> <a href="saveToCSV.php">"Save Portfolio to CSV"</a>;
        <?php          
        //GET THESE FROM DB
        $getStockCodesSQL = "SELECT id, shareCode FROM ShareStatus WHERE userId=$id";
        //$getNumStocksSQL = "SELECT id, numOfStocks FROM ShareStatus WHERE userId=$id";
        $stockStatusSQL = "SELECT * FROM ShareStatus WHERE userId=$id";
        $getStockCodes = mysql_query($getStockCodesSQL); //separate query to fetch stock codes to insert into the table. doesn't work correctly if done when creating table.
        //$getNumStocks = mysql_query($getNumStocksSQL);
        $stockStatus = mysql_query($stockStatusSQL);  
        if(! $stockStatus ){
          die('Could not connect to the database: ' . mysql_error());
        }
        /**
         * Printing out the data
         */
         
         
        $sqlMoney = "SELECT totalMoney FROM MoneyBalance WHERE userId = $id";
        $retvalM = mysql_query($sqlMoney)
          or die('Could not get data: ' . mysql_error());
      while($rowM = mysql_fetch_array($retvalM, MYSQL_ASSOC)) {
          $totalMoney = $rowM['totalMoney'];
      }
      echo "\n\nYour Money: $".$totalMoney. "\n\n";
         $incrementVar = 0;
         $everyStockCode = ''; // empty variable for the stock codes to poll Yahoo finance
         $immediateStockValue = []; //array to house each stock's current share value
         $amountOfEachStock= []; //array to hold the amount of shares per stock
         $rowIDs = [];
         $getStockCodeArray = [];
         
        // echo "<table id='display', border = '1'>";
        while($row = mysql_fetch_row($getStockCodes)){ //get Portfolio's stock codes
          $everyStockCode.= $row[1].",";
          $getStockCodeArray[$incrementVar] = $row[1];
          $rowIDs[$incrementVar]=intval($row[0]);
          $incrementVar++;
        }
        //while($row = mysql_fetch_row($getNumStocks)){ 
        //}

//        $numStockBasedOnID = array_combine ($databaseID, $amountOfEachStock);
      //Read Stock's current value from yahoo
      $requestUrl = "http://finance.yahoo.com/d/quotes.csv?s=".$everyStockCode."&f=l1"; 
      
      // Pull data
      $handle = fopen($requestUrl,"r");
      $rawData = fread($handle,10000);
      fclose($handle);

      
      $currentPrices = explode("\n",trim($rawData));

      
      $incrementVar = 0;
      echo "<table id='display', border = '1'>";
      echo "<tr><td>Row</td>".
           "<td>Symbol</td>".
           "<td>Market Price/share</td>".
           "<td>Number of Shares</td>".
           "<td>Total Share Value (Currently)</td></tr>";
           
           

      while($row = mysql_fetch_row($stockStatus)){
          $amountOfEachStock[$incrementVar] = intval($row[3]);   
          $immediateStockValue[$incrementVar]=floatval($currentPrices[$incrementVar])*intval($amountOfEachStock[$incrementVar]);
          $rowNum = $incrementVar + 1;
          $symbol = trim($row[1]);
          $numOfShare = $row[3];
          $shareUnitPrice =  getStockInfo(addslashes($symbol));
          
          $totalShareValue = $shareUnitPrice * $numOfShare;
          echo "<tr><td>{$rowNum}</td>".
               "<td>{$symbol}</td>".
               "<td>$"."{$shareUnitPrice}</td>".
               "<td>{$numOfShare}</td>".
               "<td>$"."{$totalShareValue}</td></tr>";
               $incrementVar++;
        }
        echo "</table>\n\n";
      
      
      function getStockInfo($symbol) {
        
         $exPlace2 = "NSE";  
         $exPlace3 = "BSE";
         $shareUnitPrice = 0;
          
          $googleParse = file("https://www.google.com/finance/info?q=NSE:".$symbol."&f=etl"); 
            $googleParse[0] = "";
            $googleParse[1] = "";
            $googleParse[2] = "";
            $garbageToReplace = array('"id": ', ',"t" : ',',"e" : ',',"l" : ',',"l_fix" : ',',"l_cur" : ',',"s": ',
                          ',"ltt":',',"lt" : ',',"lt_dts" : ',',"c" : ',',"c_fix" : ',',"cp" : ',',"cp_fix" : ',
                          ',"ccol" : ',',"pcls_fix" : ','"');


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
          
            if(strcmp($exPlace2,trim($googleParse[5]))==0 || strcmp($exPlace3,trim($googleParse[5]))==0 ){        
            $shareUnitPrice = currency("INR", "USD", floatval(preg_replace('/[^\d.]/', '', $googleParse[6])));
            }
           else{
              $shareUnitPrice =  $googleParse[6]; 
           }
            return $shareUnitPrice;
          
            }
        
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
      
    ?>
    </body>

    </html>