<?php
  session_start();
  require 'connect.inc.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Portfolio Manager</title>
    <style type='text/css'>
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
    <?php
        $id=$_SESSION['user_id'];
        $withdrawalAmount=floatVal($_POST['withdrawalAmount']);
          $sql = "SELECT * FROM `MoneyBalance` WHERE `userId`=$id";
          if($query_run=mysql_query($sql)){
             $query_num_rows=mysql_num_rows($query_run);
             if($query_num_rows==0){
               echo "Error: Empty";
             }
             else{
               while($row=mysql_fetch_row($query_run)){
                 $totalMoney=floatVal($row[2]);
                 if($totalMoney>$withdrawalAmount){
                   $difference=$totalMoney-$withdrawalAmount;
                   $sql_withdrawal = "UPDATE `MoneyBalance` SET `totalMoney`=$difference WHERE `userId`=$id";
                   if($query_run2=mysql_query($sql_withdrawal)){
                      echo "Withdrawal Successful";
                   }
                   else{
                     echo "Error";
                   }
                 }
                 else{
                   echo "Insufficient Funds";
                 }
               }
             } 
        }
        else{
          echo "Connection Error";
        }
        $sql2 = "INSERT INTO CashTransactions (amount, transactionType, userId) VALUES ($withdrawalAmount, \"withdrawal\", $id);";
        if($query_run2=mysql_query($sql2)){
          echo "Withdrawal Recorded";
        }
        else{
          echo "Failed to Record Withdrawal";
        }
    ?>
  </body>
</html>