<?php
require 'core.inc.php';
require 'connect.inc.php';

echo '<a href="index.php">"Return"</a><br><br>';

if(loggedin()){
   if(isset($_POST['username'])){
      $username=$_POST['username'];

      if(!empty($username)){
         if($username=="YES"){
            $query1=" delete  FROM `Users` where `username` = '".getuserfield('username')."' ";
            mysql_query($query1);
            session_destroy();
            header('Location: done.php');
         }
         else echo "Please provide a correct option.";            
      }
      else{
         echo 'You must supply an option.';
      }
   }
}

?>

<form action="<?php echo $delete;?>" method="POST">
You sure you want to delete your account? Put on code YES :<input type="text" name="username">
<input type="submit" value="Delete Account">
</form>