<?php
error_reporting(E_ALL);

session_start();
$page = getRequestedPage(); 
$data = processRequest($page);
showResponsePage($data); 
var_dump($data);

function processRequest($page){

   switch($page){

      case "contact":
       require_once('contact.php');
       $result['arr_fieldinfo'] = getContactFields(); 
         if ($_SERVER['REQUEST_METHOD']=='POST')     
         {
           require_once('validate.php');
           $result = checkFields($result['arr_fieldinfo']);
           if ($result['ok'])
            { 
            $page = 'thanks';
            }
         }
       break;

      case "login":
       require_once('login.php');
       $result['arr_fieldinfo'] = getLoginFields(); 
       if ($_SERVER['REQUEST_METHOD']=='POST'){
         require_once('validate.php');
         $result = checkFields($result['arr_fieldinfo']);
         $authenticatedUser = authenticateUser($result['email'], $result['password']);
         if ($result['ok']) {
            if (findUserByEmail(['email']) ==  false){
               $result['email_err'] = 'Email niet bekend.';
            }
            elseif ($authenticatedUser == false) {
               $result['password_err'] = 'Voer het juiste wachtwoord in.';
            }else{
             $_SESSION["user_name"] = $authenticatedUser['name'];
             $page = 'home';
           }
         }
      }
      break;

      case "register":
       require_once('register.php');
       $result['arr_fieldinfo'] = getRegisterFields();
       if ($_SERVER['REQUEST_METHOD']=='POST'){
         require_once('validate.php');
         $result = checkFields($result['arr_fieldinfo']);
         if ($result['ok']){
           if (checkRegisterUsers() == false){
              $result['email_err'] = 'Uw emailadres is al geregistreerd. Log <a href="index.php?page=login"> hier </a> in'.PHP_EOL;
            }
           elseif(checkRegisterPassword() == false){
               $result['password_err'] = 'Wachtwoorden zijn niet hetzelfde';
            }else{
             writeUser();
             require_once('login.php');
             $result['arr_fieldinfo'] = getLoginFields();
             $page = 'login';

            }
         }
      }
       break;

      case "logout":
       session_destroy();
       $_SESSION["user_name"] = NULL;
       $page = 'home';
      break;

      case "changepassword":
      require_once('changepassword.php');
      $result['arr_fieldinfo'] = getChangePasswordFields();
      if ($_SERVER['REQUEST_METHOD']=='POST'){
         require_once('validate.php');
         $result = checkFields($result['arr_fieldinfo']);
         $authenticatedUser = authenticateUser($result['email'], $result['password']);
         if ($result['ok']){
            if (findUserByEmail(['email']) ==  false){
               $result['email_err'] = 'Email niet bekend.';
            }
            elseif ($authenticatedUser == false) {
               $result['password_err'] = 'Voer het juiste wachtwoord in.';
            }
            elseif (checkNewPassword() == false){
               $result['newpassword_err'] = 'Wachtwoorden zijn niet hetzelfde';
            }else{
             changePassword();
             $page = 'home';
           }
         }
      }
      break;

      case "detail":
      $page = 'detail';
      if ($_SERVER['REQUEST_METHOD']=='POST'){
         require_once('detail.php');
         addToCart();
         var_dump($_SESSION['item']);
      }
      break;
   
   }

   $result['page'] = $page;
   return $result;
}


function showContent($result) 
{ 
   switch ($result['page']) 
   { 
       case 'home':
          require_once('home.php');
          showHomeContent();
          break;
       case 'about':
          require_once('about.php');
          showAboutContent();
          break;
       case 'contact':
          require_once('forms.php');
          require_once('contact.php');
          showContactHeading();
          showForm($result);
          break;         
       case 'thanks':
          require_once('contact.php');
          require_once('validate.php');
          showThanks($result);
          break;
       case 'login':
          require_once('login.php');
          require_once('forms.php');
          require_once('validate.php');
          showLoginHeading();
          showForm($result);
          break;
       case 'register':
          require_once('register.php');
          require_once('forms.php');
          require_once('validate.php');
          showRegisterHeading();
          showForm($result);
          break;
       case 'changepassword':
          require_once('changepassword.php');
          require_once('forms.php');
          require_once('validate.php');
          showChangePasswordHeading();
          showForm($result);
          break;
       case 'webshop':
          require_once('webshop.php');
          showWebshopHeading();
          showProducts();
          break;
       case 'detail':
         require_once('detail.php');
         $id = $_GET['id'];
         showItem($id);
         break;
       case 'cart':
         require_once('cart.php');
         showCartHeading();
         showCart();
         break;
   }     
} 

function getRequestedPage() 
{     
   $requested_type = $_SERVER['REQUEST_METHOD']; 
   if ($requested_type == 'POST') 
   { 
       $requested_page = getPostVar('page','home'); 
   } 
   else 
   { 
       $requested_page = getUrlVar('page','home'); 
   } 
   return $requested_page; 
} 

function checkSession()
{
   return isset($_SESSION["user_name"]);
}


function showResponsePage($data) 
{ 
   beginDocument(); 
   showHeadSection(); 
   showBodySection($data); 
   endDocument(); 
}     


function getArrayVar($array, $key, $default='') 
{ 
   return isset($array[$key]) ? $array[$key] : $default; 
} 


function getPostVar($key, $default='') 
{ 
    return getArrayVar($_POST, $key, $default);
} 


function getUrlVar($key, $default='') 
{ 
    return getArrayVar($_GET, $key, $default);
} 


function beginDocument() 
{ 
   echo '<!DOCTYPE html><html>'; 
} 

function showHeadSection() 
{ 
   echo '<head>
<title>Home</title>
<link rel="stylesheet" href="stylesheet2.1.css">
</head>';
} 

function showHeader() 
{ 
   echo "<header> <h1> Sofies space </h1> </header>";
} 


function showBodySection($data) 
{ 

   echo '    <body>' . PHP_EOL; 
   showHeader();
   showMenu(); 
   showContent($data); 
   showFooter();
   echo '    </body>' . PHP_EOL; 
} 



function showMenu(){
$menuItems = array('home', 'about', 'contact', 'register', 'login', 'webshop', 'cart');
$menuItemsLogin = array('home', 'about', 'contact', 'webshop', 'cart');

   echo '<p>
   <ul class="nav">';

   if (checkSession()){

      foreach ($menuItemsLogin as $value){
      echo '<li><a href="index.php?page='.$value.'">'.$value.'</a></li>';
      }
      echo '<li><a href="index.php?page=logout"> Logout '.$_SESSION["user_name"].' </a></li>
            <li><a href="index.php?page=changepassword"> Wachtwoord veranderen </a></li>';

   }else{

      foreach ($menuItems as $value){
      echo '<li><a href="index.php?page='.$value.'">'.$value.'</a></li>';
      }
   }

   echo '</ul></p>';

}

function showFooter() 
{ 
    echo '<footer> &copy; 2022 Sofie Willemsen </footer>';
} 

function endDocument() 
{ 
   echo  '</html>'; 
} 

?>