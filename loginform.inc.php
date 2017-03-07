<?php
if(isset($_POST['username'])&&isset($_POST['password'])){
   $username=$_POST['username'];
   $password=$_POST['password'];

   if(!empty($username)&&!empty($password)){

      $query="SELECT `id`, `username`, `password` FROM `Users` where `username`=
      '".mysql_real_escape_string($username)."' and `password`='".mysql_real_escape_string($password)."' ";
      
      if($username=='lucas'&&$password=='1234567890'){
         header('Location: admin.php');
      }
      else if($query_run=mysql_query($query)){
         $query_num_rows=mysql_num_rows($query_run);
         
         if($query_num_rows==0){
            echo 'Invalid username/password combination';
         }
         else if($query_num_rows==1){
            $user_id=mysql_result($query_run,0,id);
            $_SESSION['user_id']=$user_id;
            header('Location: index.php');
         }
      }
   }
   else{
      echo 'You must supply a username and password';
   }
}

?>

<form action="<?php echo $current_file;?>" method="POST">
Username:<input type="text" name="username">
Password:<input type="password" name="password">
<input type="submit" value="log in">
</form>