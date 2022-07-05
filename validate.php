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

  $email = $_POST['email'];
  $password = $_POST['password'];
  $userFile = fopen("/Applications/XAMPP/xamppfiles/htdocs/opdracht_2.1/users.txt", "r");

  try {
    while (!feof($userFile)) {
      $str = fgets($userFile);
      $explosion = explode('|', $str, 3);


      if(count($explosion) !=3){
        throw new Exception("Userfile is corrupt");
      }
      if ($email == $explosion[1]){
        return array("name" => trim($explosion[0]), "email" => trim($explosion[1]), "password" => trim($explosion[2]));
      }
    }
        return false;

  }catch(Exception $e) {  
      echo "Userfile is corrupt.";
  }
  finally{
      fclose($userFile);
  }

  return $user;
}


function authenticateUser($email, $password){
 $email = $_POST['email'];
 $user = findUserByEmail($email);
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
  $file_content = file_get_contents('/Applications/XAMPP/xamppfiles/htdocs/opdracht_2.1/users.txt');
    
    if (strpos($file_content, $email) == true) {
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
