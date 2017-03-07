<?php
  session_start();
  $id=$_SESSION['user_id'];
  require 'connect.inc.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sell Stocks</title>
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
    <h2>My Stocks:</h2>
    
    
      <?php          

        
        //GET THESE FROM DB
        $getStockCodesSQL = "SELECT id, shareCode FROM ShareStatus WHERE userId=$id";
        $getNumStocksSQL = "SELECT id, numOfStocks FROM ShareStatus WHERE userId=$id";
        $stockStatusSQL = "SELECT * FROM ShareStatus WHERE userId=$id";
        

        
        $getStockCodes = mysql_query($getStockCodesSQL);
        $getNumStocks = mysql_query($getNumStocksSQL);
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
           "<td>Stock Name</td>".
           "<td>Stock Code</td>".
           "<td>Amount of Shares</td>".
           "<td>Total Share Value (Currently)</td></tr>";
           
           

      while($row = mysql_fetch_row($stockStatus)){
          $amountOfEachStock[$incrementVar] = intval($row[3]);   
          $immediateStockValue[$incrementVar]=floatval($currentPrices[$incrementVar])*intval($amountOfEachStock[$incrementVar]);
         $rowNum = $incrementVar + 1;
         
          echo "<tr><td>{$rowNum}</td>".
               "<td>{$row[1]}</td>".
               "<td>{$row[2]}</td>".
               "<td>{$row[3]}</td>".
               "<td>$"."{$immediateStockValue[$incrementVar]}</td></tr>";
               $incrementVar++;
        }
        echo "</table>\n\n";
    ?>

<!--//Choose stock to sell and Submit-->
    <form action="sellstocks.php" method="post">
    Which stock are you going to sell? <input type="number" name="rowNumber"><br>
    <input type="submit">
    </form>
<!--/////////////////////////////////-->




<!--//Take the stock chosen to be sold and sell it-->
    <?php    
      if(isset($_POST['rowNumber'])){
      $stockToSellRow = $_POST['rowNumber'];
      $rowAboutToBeSold = $rowIDs[$stockToSellRow-1];//row to be sold and deleted from ShareStatus
      $urlRequestSell="http://finance.yahoo.com/d/quotes.csv?s=".$getStockCodeArray[$stockToSellRow-1]."&f=snl1x"; 

      
      
               
      // Pull data
      $handleSell = fopen($urlRequestSell,"r");
      $rawDataSell = fgetcsv($handleSell,500);
      fclose($handleSell);

      $sellStockCode = $rawDataSell[0];
      $sellCurrentMarketPrice = floatval($rawDataSell[2]);
      $sellStockName = $rawDataSell[1];
      $sellStockNameString = mysql_real_escape_string($sellStockName);
      $sellNumShares = intval($amountOfEachStock[$stockToSellRow-1]);
      $sellStockExchange = $rawDataSell[3];

      $BalanceAfterSell = floatval($totalMoney + $immediateStockValue[$stockToSellRow-1]);

$sqlupdM = "UPDATE MoneyBalance SET totalMoney = $BalanceAfterSell WHERE userId = $id";
             $updShareM = mysql_query( $sqlupdM);
           
             if(! $updShareM )
             {
                 die('Could not update data: ' . mysql_error());
             }
          
          $theWordSellString = "Sell";
          
 $sql = "INSERT INTO ShareTransactions (shareNames,numOfShares,unitPrice,exchangePlace,exchangePlCurrency,userId,transactionType,shareCode) VALUES('$sellStockNameString',$sellNumShares,$sellCurrentMarketPrice,'$sellStockExchange','USD',$id,'$theWordSellString','$sellStockCode')"; 
     $checkStoredData = mysql_query( $sql);
     if(! $checkStoredData ) {
               die('Could not enter data: ' . mysql_error()); 
         }




      $sqlSellQuery = "DELETE FROM ShareStatus WHERE id='$rowAboutToBeSold'"; 
     $sqlSell = mysql_query($sqlSellQuery)
       or die('Could not enter data: ' . mysql_error()); 
      echo "<meta http-equiv='refresh' content='0'>";
      //$currentPrices = explode("\n",trim($rawData));
      
      
} //End isset rowNum
      mysql_close($connect);
    ?>
    
  </body>
</html>