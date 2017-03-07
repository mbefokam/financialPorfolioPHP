Welcome Admin
<?php
require 'core.inc.php';
require 'connect.inc.php';

echo '<br>';echo '<br>';
echo '<a href="index.php">Log out</a><br>';
echo '<a href="register.php">Add Account</a><br>';
echo '<br>';echo '<br>';

if(isset($_POST['username'])){
   $username=$_POST['username'];
 
   if(!empty($username)){

      $query="SELECT `id`, `username` FROM `Users` where `username`=
      '".mysql_real_escape_string($username)."' ";
      
      if($query_run=mysql_query($query)){
         $query_num_rows=mysql_num_rows($query_run);
         
         if($query_num_rows==0){
            echo 'User doesn\'t exist';
         }
         else if($query_num_rows==1){
            if($username=="lucas") echo "Don't delete yourself!";
            else{
               $query1="delete  FROM `Users` where `username`='".mysql_real_escape_string($username)."' ";
               mysql_query($query1);
               echo "Account deleted!";
            }            
         }
      }
   }
   else{
      echo 'You must supply a username.';
   }
}
?>

<form action="<?php echo $admin;?>" method="POST">
Enter the account to be deleted:<input type="text" name="username">
<input type="submit" value="Delete Account">
</form>