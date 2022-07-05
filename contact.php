<?php

//=============================================================================
// TEST FORM 
//=============================================================================


//=============================================================================
// FORM DISPLAY
//=============================================================================

function showContactHeading()
{
  echo '<h1>Contact</h1> <p>Vul onderstaand contactformulier in om met mij in contact te komen.</p>';
}


function getContactOptions() : array
{
  return array(
  'Email' => 'email',
  'Telefoon' => 'telefoon',
  );
}

//=============================================================================
// Loop door alle velden en toon per veld-type het juiste inputfield.
// Zijn er geposte data meegegeven, toon dan of de value of de error.
//=============================================================================


//=============================================================================
// FORM RESULT
// Loop door alle velden, toon per veld naam, type en geposte waarde
//=============================================================================
function showThanks(array $arr_postresult)
{
  echo '<table>'.PHP_EOL; 
  echo '<tr><th>Bedankt voor uw bericht:</th></tr>'.PHP_EOL;
  foreach ($arr_postresult['arr_fieldinfo'] as $fieldname => $fieldinfo)
  {
    echo '<tr><td>'
      .$fieldinfo['label']
      .'</td><td>'
      .$arr_postresult[$fieldname]
      .'</td></tr>'
      .PHP_EOL;
  }
  echo '</table>'.PHP_EOL;  
}

function getContactFields(){

   $contactform_fields = array
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
   'phone'   => array('type' => 'tel',
             'label'=> 'Telefoonnummer',
             'placeholder' => 'Telefoonnummer',
            ),  
   'contact'   => array('type' => 'select',
             'label'=> 'Voorkeur voor communicatie per',
             'options_func' => 'getContactOptions'
            ),        
   'message'   => array('type' => 'textarea',
             'label'=> 'Bericht',
             'placeholder' => 'Bericht',
            )   
  );

  return $contactform_fields;

}

//=============================================================================

