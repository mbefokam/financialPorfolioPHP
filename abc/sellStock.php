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
         $databaseID = []; //ID tags for each column
         $amountOfEachStock= []; //array to hold the amount of shares per stock
         $incrementVar = 0;
         $rawDataArrayLoc = 0;
                   
                   
        // echo "<table id='display', border = '1'>";
        while($row = mysql_fetch_row($getStockCodes)){ //get Portfolio's stock codes
          $everyStockCode.= $row[1].",";
        }
        while($row = mysql_fetch_row($getNumStocks)){ //get Portfolio's stock codes
         $databaseID[$incrementVar] = $row[0];
         $amountOfEachStock[$incrementVar] = intval($row[1]);
         $incrementVar++;
        }
         


        $numStockBasedOnID = array_combine($databaseID, $amountOfEachStock); //create an array where the 'key' is the same as the 'id' for the relative stock

      //Read Stock's current value from yahoo
      echo $requestUrl = "http://finance.yahoo.com/d/quotes.csv?s=".$everyStockCode."&f=l1"; 
      
      // Pull data
      $handle = fopen($requestUrl,"r");
      $rawData = fgetcsv($handle, "\n");
      fclose($handle);


      echo "<table id='display', border = '1'>";
      echo "<tr><td>id</td>"."<td>Stock Name</td><td>Amount of Shares</td><td>Unit Price</td><td>Exchange Market</td><td>Transaction Date</td><td>Exchange Market Currency</td><td>User ID</td><td>Transaction Type</td><td>Share Code</td><td>Total Share Value (Currently)</td>";
           
           
 
      while($row = mysql_fetch_row($stockStatus)){
          $imStockValArrLoc = intval($row[0]-1);
          
          
          //$amountOfEachStock[$imStockValArrLoc] = floatval($row[2]);
          
          $immediateStockValue[$rawDataArrayLoc]=floatval($rawData[$rawDataArrayLoc])*floatval($numStockBasedOnID[intval($row[0])]);
         
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
               "<td>$"."{$immediateStockValue[$rawDataArrayLoc]}</td></tr>";
               $rawDataArrayLoc++;
        }
        mysql_close($connect);
      ?>

  </body>
</html>
