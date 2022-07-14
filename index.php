<?php
error_reporting(E_ALL);

// Start een sessie

session_start();

$page = getRequestedPage(); 
$data = processRequest($page);
showResponsePage($data); 

// Verwerk de acties van elke afzonderlijke pagina

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
             $_SESSION['userid'] = $authenticatedUser['id'];
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
             $page = 'login';
           }
         }
      }
      break;
      
      case "webshop":
         require_once('detail.php');
         if ($_SERVER['REQUEST_METHOD']=='POST'){
           addToCart();
           $page = 'cart';
         }
      break;

      case "detail":
         require_once('detail.php');
         if ($_SERVER['REQUEST_METHOD']=='POST'){
           addToCart();
           $page = 'cart';
         }
      break;

      case "cart":
         require_once('cart.php');
         if ($_SERVER['REQUEST_METHOD']=='POST'){
            writeToOrders();
            writeToOrdersRegel();
            $_SESSION['cart_products'] = NULL;
            $page = 'cart';
          }
          break;
   }

   $result['page'] = $page;
   return $result;
}

// Laat de content zien afhankelijk van de pagina

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
         showProduct($id);
         break;
       case 'cart':
         require_once('cart.php');
         showCartHeading();
         showCart();
         break;
   }     
} 

// Verwerk of het een POST of GET pagina is

function getRequestedPage() 
{     
   $requested_type = $_SERVER['REQUEST_METHOD']; 
   if ($requested_type == 'POST') 
   { 
       $requested_page = getPostVar('page','home'); 
       var_dump($requested_page);
   } 
   else 
   { 
       $requested_page = getUrlVar('page','home'); 
   } 
   return $requested_page; 
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

function checkSession()
{
   return isset($_SESSION["user_name"]);
}

// Laat de pagina zien

function showResponsePage($data) 
{ 
   beginDocument(); 
   showHeadSection(); 
   showBodySection($data); 
   endDocument(); 
}     

// Begin HTML

function beginDocument() 
{ 
   echo '<!DOCTYPE html><html>'; 
} 

// Laat de head sectie zien

function showHeadSection() 
{ 
   echo '<head>
<title>Home</title>
<link rel="stylesheet" href="stylesheet2.1.css">
</head>';
} 

// Toon de titel van de webpagina

function showHeader() 
{ 
   echo "<header><h1><br>Sofie's Webshop </h1> </header>";
} 

// Laat het body gedeelte van de pagina zien

function showBodySection($data) 
{ 

   echo '    <body>' . PHP_EOL; 
   showHeader();
   showMenu(); 
   showContent($data); 
   showFooter();
   echo '    </body>' . PHP_EOL; 
} 

// Toon het menu, afhankelijk van login

function showMenu(){
$menuItems = array('home', 'about', 'contact', 'register', 'login', 'webshop');
$menuItemsLogin = array('home', 'about', 'contact', 'webshop', 'cart');

   echo '<p>
   <ul class="nav">';

   if (checkSession()){

      foreach ($menuItemsLogin as $value){
      echo '<li><a href="index.php?page='.$value.'">'.$value.'</a></li>'.PHP_EOL;
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

// Toon de footer van de pagina

function showFooter() 
{ 
    echo '<footer> &copy; 2022 Sofie Willemsen </footer>';
} 

// Eindig html

function endDocument() 
{ 
   echo  '</html>'; 
} 

?>