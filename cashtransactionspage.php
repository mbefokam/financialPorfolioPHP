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
    <h1>Cash Transactions:</h1>
    <table>
      <tr>
        <th>Transaction Type</th>
        <th>Transaction Date</th>
        <th>Amount('$')</th>
      </tr>
      <?php
          $id=$_SESSION['user_id'];
          $sql = "SELECT * FROM `CashTransactions` WHERE `userId`=$id";
          if($query_run=mysql_query($sql)){
             $query_num_rows=mysql_num_rows($query_run);        
             if($query_num_rows==0){
                echo "</table><p>No Transaction History</p>";  
             }
             else{
                while($row = mysql_fetch_row($query_run)){?>
                  <tr><td><?php echo $row[2]; ?></td>
                  <td><?php echo $row[3]; ?></td>
                  <td><?php echo $row[1]; ?></td>
                </tr>  
              <?php }
            }
          }?>
    </table>
  </body>
</html>
