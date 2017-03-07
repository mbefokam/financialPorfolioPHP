<?php
  session_start();
  require 'connect.inc.php';
  require 'core.inc.php';
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
<?php if(loggedin()){ ?>
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
     </ul><?php   
   echo getuserfield('firstname');
   echo " ";
   echo getuserfield('lastname');
   echo '<br>';
   echo 'You\'re logged in.';
   echo '<br>';
   echo '<br>';

   echo 'Email: ';
   echo getuserfield('email');
   echo '<br>';

   echo 'Phone number: ';
   echo getuserfield('phonenumber');
   echo '<br>';
   
   echo 'Address: ';
   echo getuserfield('street_address');
   echo " ";
   
   echo getuserfield('city');
   echo " ";
   
   echo getuserfield('state');
   echo " ";
   
   
   echo getuserfield('country');
   echo " ";
   
   
   echo getuserfield('zipcode');
   
   echo '<br>';
   echo '<br>';
   echo '<a href="logout.php">Log out</a><br><br>';
   echo '<a href="delete.php">Delete Account</a><br>';
   ?>
   </body>
   <?php
}
else{
   include 'loginform.inc.php';
}

?>
</html>