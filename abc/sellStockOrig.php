<!DOCTYPE html>
<html>
  <head>
    <title>SELL STOCKS</title>
  </head>
  <body>
    <h1>Portfolio</h1>
    <h2>Share Transactions:</h2>
    
    
      <?php          
        
        $mysql_host="sql2.njit.edu";
        $mysql_user="kn259";
        $mysql_pass="7bI1DDsD";
        
        $connect = mysql_connect($mysql_host,$mysql_user,$mysql_pass);

        if(! $connect){
          die('Could not connect to sql2.njit.edu' . mysql_error());
        }
        
        //GET THESE FROM DB
        $getStockCodesSQL = "SELECT id, shareCode FROM ShareTransactions WHERE userId=728";
        $getNumStocksSQL = "SELECT id, numOfStocks FROM ShareTransactions WHERE userId=728";
        $stockStatusSQL = "SELECT * FROM ShareTransactions WHERE userId=728";
        
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
      echo $requestUrl = "http://finance.yahoo.com/d/quotes.csv?s=".$everyStockCode."&f=l1"; 
      
      // Pull data
      $handle = fopen($requestUrl,"r");
      $rawData = fgetcsv($handle, "\n");
      fclose($handle);

      // Split results, trim away the extra line break at the end
 /*     foreach($quotes as $quoteraw){
      $quoteraw = str_replace("\n", ",", $quoteraw);
      $quotes = explode(",",$quoteraw);
      
      }
 */
 
/*      foreach($quotes as $quoteraw) {
      $quoteraw = str_replace(", I", " I", $quoteraw);
      $quote = explode(",", $quoteraw);
      }
*/

      echo "<table id='display', border = '1'>";
      echo "<tr><td>id</td>".
           "<td>Stock Name</td>".
           "<td>Amount of Shares</td>".
           "<td>Unit Price</td>".
           "<td>Exchange Market</td>".
           "<td>Transaction Date</td>".
           "<td>Exchange Market Currency</td>".
           "<td>User ID</td>".
           "<td>Transaction Type</td>".
           "<td>Share Code</td>".
           "<td>Total Share Value (Currently)</td>";
           
           
           
      while($row = mysql_fetch_row($stockStatus)){
          $imStockValArrLoc = intval($row[0] - 1);//immediateStockValue array location starts at [0], but 'id' starts at 1, so subtract 1. 
          $amountOfEachStock[$imStockValArrLoc] = floatval($row[2]);
          
          $immediateStockValue[$imStockValArrLoc]=floatval($rawData[$imStockValArrLoc])*floatval($amountOfEachStock[$imStockValArrLoc]);
         
          echo "<tr><td>{$row[0]}</td>".
               "<td>{$row[1]}</td>".
               "<td>{$row[2]}</td>".
               "<td>$"."{$row[3]}</td>".
               "<td>{$row[4]}</td>".
               "<td>{$row[5]}</td>".
               "<td>{$row[6]}</td>".
               "<td>{$row[7]}</td>".
               "<td>{$row[8]}</td>".
               "<td>{$row[9]}</td>".
               "<td>$"."{$immediateStockValue[$imStockValArrLoc]}</td></tr>";
        }
        mysql_close($connect);
      ?>

  </body>
</html>
                      