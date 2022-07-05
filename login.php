<?php

function showLoginHeading()
{
  echo '<h1>Login</h1><p>Log hier in met je emailadres en je wachtwoord:</p>';
}


function getLoginFields(){
 
  $loginform_fields = array
  (
    'email'   => array('type' => 'email',
             'label'=> 'Email',
             'placeholder' => 'Email',
             'check_func' => 'validEmail'
            ),  
    'password'   => array('type' => 'password',
             'label'=> 'Wachtwoord',
             'placeholder' => 'Wachtwoord',
            )   
  );

  return $loginform_fields;

}


?>