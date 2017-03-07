<!DOCTYPE html>
<html>
  <head>
    <title>SELL STOCKS</title>
  </head>
  <body>
    <h1>My Portfolio</h1>
    <h2>My Stocks:</h2>
    
    
      <?php          
        
        $mysql_host="sql2.njit.edu";
        $mysql_user="kn259";
        $mysql_pass="7bI1DDsD";
        
        $connect = mysql_connect($mysql_host,$mysql_user,$mysql_pass);

        if(! $connect){
          die('Could not connect to sql2.njit.edu' . mysql_error());
        }
        
        //GET THESE FROM DB
        $getStockCodesSQL = "SELECT id, shareCode FROM ShareStatus";
        $getNumStocksSQL = "SELECT id, numOfStocks FROM ShareStatus";
        $stockStatusSQL = "SELECT * FROM ShareStatus";
        
        mysql_select_db('kn259');
        
        $getStockCodes = mysql_query($getStockCodesSQL, $connect);
        $getNumStocks = mysql_query($getNumStocksSQL);
        $stockStatus = mysql_query($stockStatusSQL);
        
        if(! $stockStatus ){
          die('Could not connect to the database: ' . mysql_error());
        }
        /**
         * Printing out the data
         */
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
      echo "<tr><td>id</td>".
           "<td>Stock Name</td>".
           "<td>Stock Code</td>".
           "<td>Amount of Shares</td>".
           "<td>User ID</td>".
           "<td>Total Share Value (Currently)</td></tr>";
           
           

      while($row = mysql_fetch_row($stockStatus)){
          $amountOfEachStock[$incrementVar] = intval($row[3]);   
          $immediateStockValue[$incrementVar]=floatval($currentPrices[$incrementVar])*intval($amountOfEachStock[$incrementVar]);
         
         
          echo "<tr><td>{$row[0]}</td>".
               "<td>{$row[1]}</td>".
               "<td>{$row[2]}</td>".
               "<td>{$row[3]}</td>".
               "<td>{$row[4]}</td>".
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
      $stockToSellRow = $_POST['rowNumber'];
      $rowIDs[$stockToSellRow-1];
      echo $urlRequestSell="http://finance.yahoo.com/d/quotes.csv?s=".$getStockCodeArray[$stockToSellRow-1]."&f=snl1x"; 

      $sqlMoney = "SELECT totalMoney FROM MoneyBalance WHERE userId = 728";
        $retvalM = mysql_query($sqlMoney)
          or die('Could not get data: ' . mysql_error());
      while($rowM = mysql_fetch_array($retvalM, MYSQL_ASSOC)) {
          $totalMoney = $rowM['totalMoney'];
      }
      
               
      // Pull data
      $handleSell = fopen($urlRequestSell,"r");
      $rawDataSell = fgetcsv($handleSell,500);
      fclose($handleSell);

      $sellStockCode = $rawDataSell[0];
      $sellCurrentMarketPrice = floatval($rawDataSell[2]);
      $sellStockName = $rawDataSell[1];
      $sellNumShares = intval($amountOfEachStock[$stockToSellRow-1]);
      $sellStockExchange = $rawDataSell[3];

      $BalanceAfterSell = $totalMoney + $immediateStockValue[$stockToSellRow-1];

$sqlupdM = "UPDATE MoneyBalance SET totalMoney = '$BalanceAfterSell' WHERE userId = 728 " ;
             $updShareM = mysql_query( $sqlupdM);
           
             if(! $updShareM )
             {
                 die('Could not update data: ' . mysql_error());
             }
          
$sql = "INSERT INTO ShareTransactions(shareNames,numOfShares, unitPrice, exchangePlace,exchangePlCurrency,userId,transactionType,shareCode,ForeignCurrency,UnitPriceForeignCurrency) VALUES (".$sellStockName.",".$sellNumShares.','.$sellCurrentMarketPrice.','.$sellStockExchange.',USD,728,sell,'.$sellStockCode.',None,None)'; 
     $checkStoredData = mysql_query( $sql);
     if(! $checkStoredData ) {
               die('Could not enter data: ' . mysql_error()); 
         }


/*

      $sqlSellQuery = "DELETE FROM ShareStatus WHERE id=".$rowIDs($stockToSellRow-1); 
     $sqlSell = mysql_query( $sqlSellQuery)
       or die('Could not enter data: ' . mysql_error()); 
      
      //$currentPrices = explode("\n",trim($rawData));
      
      
  */    
      mysql_close($connect);
    ?>
    
  </body>
</html>