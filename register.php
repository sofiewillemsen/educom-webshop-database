<?php


function showRegisterHeading()
{
  echo '<h1>Registreren</h1><p>Registreer je gegevens:</p>';
}



function writeUser(){
  $usersFile = fopen("/Applications/XAMPP/xamppfiles/htdocs/opdracht_2.1/users.txt", "a") or die("Unable to open file!"); 
  $userRegister = PHP_EOL. '[naam]|[email]|[wachtwoord]'.PHP_EOL. $_POST['naam'].'|'.$_POST['email'].'|'.$_POST['password'];
  fwrite($usersFile, $userRegister);
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