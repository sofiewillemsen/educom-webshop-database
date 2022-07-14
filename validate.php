<?php 


function validEmail(string $value) : bool
{
  return filter_var($value, FILTER_VALIDATE_EMAIL);
}

function checkField(string $fieldname, array $fieldinfo) : array
{
  $result = array();
  $result['ok'] = false;
  if (isset($_POST[$fieldname]))
  {
    $value = $_POST[$fieldname];
    $value = trim($value); 
    $value = stripslashes($value); 
    $value = htmlspecialchars($value); 
    $result[$fieldname] = $value;

    if (empty($value))
    {
      $result[$fieldname.'_err'] = $fieldinfo['label'].' is verplicht in te vullen.';
    }
    else
    {
      if (isset($fieldinfo['check_func']) && !empty($fieldinfo['check_func']))
      {
        $valid = call_user_func($fieldinfo['check_func'], $value);
        if ($valid)
        {
          $result['ok'] = true;
        }
        else
        {
          $result[$fieldname.'_err'] = $fieldinfo['label'].' is niet correct.';
        }     
      }   
      else
      { 
        $result['ok'] = true;
      } 
    } 

  }
  else
  {
    $result[$fieldname.'_err'] = $fieldname.' niet gevonden.';
  }
  return $result;
}


function checkFields(array $arr_fieldinfo) : array
{
  $result = array('arr_fieldinfo' => $arr_fieldinfo);
  $result['ok'] = true;
  foreach ($arr_fieldinfo as $fieldname => $fieldinfo)
  {
    $check = checkField($fieldname, $fieldinfo);
    if ($check['ok'])
    {
      $result[$fieldname] = $check[$fieldname];
    } 
    else
    {
      $result['ok'] = false;
      $result[$fieldname.'_err'] = $check[$fieldname.'_err'];
    }     
  }
  return $result;
}

function findUserByEmail(){

  $servername = "localhost";
  $username = "sofie";
  $password = "UOIa(27t3rzexDM@";
  $dbname = "sofies_webshop";

  $email = $_POST['email'];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, name, email, password FROM users WHERE email='".$email."'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    $user['id'] = $row['id'];
    $user['name'] = $row['name'];
    $user ['password'] = $row['password'];
    $user['email'] = $row['email'];
  }
  }else{
    $user = NULL;
} 
return $user;
mysqli_close($conn);

}


function authenticateUser($email, $password){
 $email = $_POST['email'];
 $user = findUserByEmail();
 $password = $_POST['password'];

 if ($user == null) {
  return false;
 }
 if ($user['password']!== $password) {
  return false;
 }

return $user;

}

function checkRegisterUsers(){
  $email = $_POST['email'];
  $user = findUserByEmail();
    
    if (isset($user)) {
      return false;
    }
    return true;
}

function checkRegisterPassword(){
  $password = $_POST['password'];
  $password2 = $_POST['password2'];

  if ($password !== $password2){
    return false;
    
  }
  return true;
}


?>
