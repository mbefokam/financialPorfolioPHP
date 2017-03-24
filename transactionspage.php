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
    <h1>Stock Transactions:</h1>
    <table>
      <tr>
        <th>Transaction Type</th>
        <th>Transaction Date</th>
        <th>Symbol</th>
        <!--<th>Name</th>-->
        <th>Stock Exchange</th>
        <th>Number of Shares</th>
        <th>Unit Price</th>
        <th>Total Value</th>
        <th>Foreign Currency</th>
        <th>Foreign Unit Price</th>
      </tr>
      <?php
        $id=$_SESSION['user_id'];
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

          
        
          $sql = "SELECT * FROM `ShareTransactions` WHERE `userId`=$id";
          if($query_run=mysql_query($sql)){
             $query_num_rows=mysql_num_rows($query_run);        
             if($query_num_rows==0){
                
                echo "</table><p>No Transaction History</p>";  
             }
             else{
                while($row = mysql_fetch_row($query_run)){ 
                     
                  $numOfShares=floatVal($row[2]);
                  $currency=$row[6];
                      if($currency="INR"){
                       //   $shareValue=0.015005*$row[3];
                          //echo ($currency); 
                          $shareValue = $row[3] /*currency("INR", "USD", $row[3])*/;
                      }
                      else{
                          echo ($currency);
                        $shareValue=$row[3];
                      }?>
                  <tr><td><?php echo $row[8]; ?></td>
                  <td><?php echo $row[5]; ?></td>
                  <td><?php echo $row[9]; ?></td>
                  
                  <td><?php echo $row[4]; ?></td>
                  <td><?php echo $row[2]; ?></td>
                  <td><?php echo "$".$shareValue; ?></td>
                  <td><?php echo "$".$numOfShares*$shareValue; ?></td>
                  <td><?php echo $row[10]; ?></td>
                  <td><?php echo $row[11]; ?></td>
                </tr>  
              <?php }
            }
          }
      ?>
    </table>
  </body>
</html>
