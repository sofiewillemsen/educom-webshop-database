<?php

function showChangePasswordHeading()
{
  echo '<h1>Wachtwoord veranderen</h1><p>Voor hieronder uw oude en nieuwe wachtwoord in:</p>';
}



function getChangePasswordFields(){
 
  $changepasswordform_fields = array
  (
    'email'   => array('type' => 'email',
             'label'=> 'Email',
             'placeholder' => 'Email',
             'check_func' => 'validEmail'
            ),  
    'password'   => array('type' => 'password',
             'label'=> 'Huidig wachtwoord',
             'placeholder' => 'Wachtwoord',
            ),   
    'newpassword' => array('type' => 'password',
             'label'=> 'Nieuw wachtwoord',
             'placeholder' => 'Wachtwoord',
            ),   
    'newpassword2' => array('type' => 'password',
             'label'=> 'Herhaal nieuw wachtwoord',
             'placeholder' => 'Wachtwoord',
            )   
  );

  return $changepasswordform_fields;

}


function checkNewPassword(){
  $newpassword = $_POST['newpassword'];
  $newpassword2 = $_POST['newpassword2'];

  if ($newpassword !== $newpassword2){
    return false;
  }
  return true;
}

function changePassword(){
  $servername = "localhost";
  $username = "sofie";
  $password = "UOIa(27t3rzexDM@";
  $dbname = "sofies_webshop";

  $email = $_POST['email'];
  $pword = $_POST['password'];
  $newpassword = $_POST['newpassword'];


  $conn = new mysqli($servername, $username, $password, $dbname);

  $sql = "UPDATE users SET password='".$newpassword."' WHERE email='".$email."'";

  if ($conn->query($sql) === FALSE) {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

$conn->close();
}

?>