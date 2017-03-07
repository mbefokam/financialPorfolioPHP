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
        $depositAmount=floatVal($_POST['depositAmount']);    
          $sql = "SELECT * FROM `MoneyBalance` WHERE `userId`=$id";
          if($query_run=mysql_query($sql)){
             $query_num_rows=mysql_num_rows($query_run);
             if($query_num_rows==0){
               $sql2 = "INSERT INTO MoneyBalance (userId, totalMoney) VALUES ($id, $depositAmount);";
                  if($query_run2=mysql_query($sql2)){
                      echo "Balance started, and cash deposited";
                  }
                  else{
                      echo "Failed to start a Balance and deposit cash";
                  }
             }
             else{
               while($row=mysql_fetch_row($query_run)){
                 $totalMoney=floatVal($row[2]);
                 $sum=$totalMoney+$depositAmount;
                 $sql_deposit = "UPDATE `MoneyBalance` SET `totalMoney`=$sum WHERE `userId`=$id";
                 if($query_run2=mysql_query($sql_deposit)){
                     echo "Deposit Successful";
                 }
                 else{
                     echo "Deposit Error";
                 }
               }
             } 
        }
        else{
          echo "Connection Error";
        }
        $sql3 = "INSERT INTO CashTransactions (amount, transactionType, userId) VALUES ($depositAmount, \"deposit\", $id);";
        if($query_run3=mysql_query($sql3)){
          echo "Deposit Recorded";
        }
        else{
          echo "Failed to Record Deposit";
        }  
    ?>
  </body>
</html>

