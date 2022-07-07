<?php


function showRegisterHeading()
{
  echo '<h1>Registreren</h1><p>Registreer je gegevens:</p>';
}



function writeUser(){

  $servername = "localhost";
  $username = "sofie";
  $password = "UOIa(27t3rzexDM@";
  $dbname = "sofies_webshop";

  $name = $_POST['naam'];
  $email = $_POST['email'];
  $pword = $_POST['password'];


  $conn = new mysqli($servername, $username, $password, $dbname);

  $sql = "INSERT INTO users (name, email, password)
    VALUES ('".$name."', '".$email."', '".$pword."')";

  if ($conn->query($sql) === FALSE) {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

$conn->close();
}

function getRegisterFields(){

  $registerform_fields = array
  (
    'naam'    => array('type' => 'text',    
             'label'=> 'Naam',
             'placeholder' => 'Naam',
            ),    
    'email'   => array('type' => 'email',
             'label'=> 'Email',
             'placeholder' => 'Email',
             'check_func' => 'validEmail'
            ),  
    'password'   => array('type' => 'password',
             'label'=> 'Wachtwoord',
             'placeholder' => 'Wachtwoord',
            ),
    'password2'   => array('type' => 'password',
             'label'=> 'Herhaal wachtwoord',
             'placeholder' => 'Wachtwoord',
            )      
  );

  return $registerform_fields;
}


?>