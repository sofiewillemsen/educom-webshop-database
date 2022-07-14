<?php

/* Laat het aangeklikte product met
koopbutton afhankelijk van login*/

function showProduct($product){
    require_once('webshop.php');
    $products = getProducts();
    
    $id = $_GET['id'];
    var_dump($id);
    
    $product = $products[$id];

    echo '<h1>'
      .$product['name']
      .'
      </h1>
      <p>
      <img src="/educom-webshop-database/Images/'
      .$product['picture']
      .'" alt="'
      .$product['picture']
      .'" style="width:300px;height:380px;">
      </p>
      
      <p>'
      .$product['description']
      .'</p>
      
      <p>€'
      .$product['price']
      .',-';
      
      if (checkSession()){
        require_once('forms.php');
        openForm('detail','');
        echo '<input type="hidden" name="id" value="'.$product['id'].'">';
        closeForm($submit_caption="Koop");
      }else{
       echo '<p><a href="index.php?page=login">Log in</a> om te bestellen.</p>';
   }
   var_dump($product);
}

// Voeg product toe aan de cart

function addToCart(){
    require_once('webshop.php');
    $products = getProducts();
    $id = $_POST['id'];
    $product = $products[$id];
    $_SESSION['cart_products'][$id] = $product;
    var_dump($_SESSION['cart_products']);
}

?>