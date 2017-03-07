<!DOCTYPE html>
<html>
  <head>
    <title>We should consolidate headers</title>
  </head>
  <body>
    <h1>Portfolio</h1>
    <h2>Share Transactions:</h2>
    
      <a href="saveToCSV.php">Save portfolio as CSV document</a>
      <?php          
        
        $mysql_host="sql2.njit.edu";
        $mysql_user="kn259";
        $mysql_pass="7bI1DDsD";
        
        $connect = mysql_connect($mysql_host,$mysql_user,$mysql_pass);

        if(! $connect){
          die('Could not connect to sql2.njit.edu' . mysql_error());
        }
        
        //GET THESE FROM DB
        $userInfoSQL = 'SELECT id, firstname, lastname, email, phonenumber, password, username FROM Users';
        
        mysql_select_db('kn259');
        $userInfo = mysql_query($userInfoSQL, $connect);
        
        if(! $userInfo ){
          die('Could not connect to the database: ' . mysql_error());
        }
        /**
         * Printing out the data
         */
         echo "<table id='display'>";
        while($row = mysql_fetch_assoc($userInfo)){
          echo "<tr><td>{$row['id']}</td>".
               "<td>{$row['firstname']}</td>".
               "<td>{$row['lastname']}</td>".
               "<td>{$row['email']}</td>".
               "<td>{$row['phonenumber']}</td>".
               "<td>{$row['username']}</td>".
               "<td>{$row['password']}</td></tr>";
        }
                
        mysql_close($connect);
      ?>

  </body>
</html>
