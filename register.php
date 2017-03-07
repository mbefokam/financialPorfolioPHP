<?php
echo '<a href="index.php">"Return"</a><br><br>';

require 'core.inc.php';
require 'connect.inc.php';

if(!loggedin()){
   if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['firstname'])&&isset($_POST['surname'])
   &&isset($_POST['email'])&&isset($_POST['phonenumber'])&&isset($_POST['street_address'])&&isset($_POST['city'])&&isset($_POST['state'])
   &&isset($_POST['country'])&&isset($_POST['zipcode']))
   {
       $username=$_POST['username'];
       $password=$_POST['password'];
       $firstname=$_POST['firstname'];
       $surname=$_POST['surname'];
       $email=$_POST['email'];
       $phonenumber=$_POST['phonenumber'];
       $street_address=$_POST['street_address'];
       $city=$_POST['city'];
       $state=$_POST['state'];
       $country=$_POST['country'];
       $zipcode=$_POST['zipcode'];
       $type="user";
       if(!empty($username)&&!empty($password)&&!empty($firstname)&&!empty($surname)&&!empty($email)&&
       !empty($phonenumber)&&!empty($street_address)&&!empty($city)&&!empty($state)&&!empty($country)&&!empty($zipcode))
       {
           $query=" select `username` from `Users` where `username` = '$username' ";
           $query_run=mysql_query($query);

           if(mysql_num_rows($query_run)==1){
              echo 'Username ' .$username. ' already exists';
           }
           else{
              $query="insert into `Users` values('','".mysql_real_escape_string($firstname)."',
              '".mysql_real_escape_string($surname)."',
              '".mysql_real_escape_string($email)."','".mysql_real_escape_string($phonenumber)."',
              '".mysql_real_escape_string($password)."','".mysql_real_escape_string($username)."',
              '".mysql_real_escape_string($street_address)."','".mysql_real_escape_string($city)."',
              '".mysql_real_escape_string($state)."','".mysql_real_escape_string($country)."','".mysql_real_escape_string($zipcode)."','".mysql_real_escape_string($type)."')";

              if($query_run=mysql_query($query)){
                 header('Location: register_success.php');
              }
              else{
                 echo 'Sorry, you can\'t register at this moment.';
              }
           }
       }
       else{
          echo 'All fields are required.';
       }
   }

?>
<form action="register.php" method="POST">
Username:      <br><input type="text" name="username" value="<?php if(isset($username)){echo $username;}?>" ><br><br>
Password:      <br><input type="password" name="password"><br><br>
FirstName:     <br><input type="text" name="firstname" value="<?php if(isset($firstname)){echo $firstname;}?>" ><br><br>
LastName:      <br><input type="text" name="surname" value="<?php if(isset($surname)){echo $surname;}?>" ><br><br>
Email Address: <br><input type="text" name="email" value="<?php if(isset($email)){echo $email;}?>" ><br><br>
Phone Number:  <br><input type="text" name="phonenumber" value="<?php if(isset($phonenumber)){echo $phonenumber;}?>" ><br><br>
Street_Address: <br><input type="text" name="street_address" value="<?php if(isset($street_address)){echo $street_address;}?>" ><br><br>
City:  <br><input type="text" name="city" value="<?php if(isset($city)){echo $city;}?>" ><br><br>
State: <br><input type="text" name="state" value="<?php if(isset($state)){echo $state;}?>" ><br><br>
Counrty:  <br><input type="text" name="country" value="<?php if(isset($country)){echo $country;}?>" ><br><br>
Zipcode: <br><input type="text" name="zipcode" value="<?php if(isset($zipcode)){echo $zipcode;}?>" ><br><br>

<input type="submit" value="Register">
</form>

<?php
}
else if(loggedin()){
   echo 'You\'re already registered and logged in.';
}


?>