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
         
         $everyStockCode = ''; // empty variable for the stock codes to poll Yahoo finance
         $immediateStockValue = []; //array to house each stock's current share value
         $amountOfEachStock= []; //array to hold the amount of shares per stock
         
        // echo "<table id='display', border = '1'>";
        while($row = mysql_fetch_row($getStockCodes)){ //get Portfolio's stock codes
          $everyStockCode.= $row[1].",";
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
        
      mysql_close($connect);
      ?>
 
  </body>
</html>