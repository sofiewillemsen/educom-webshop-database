<?php

function showItem($item){
    require_once('webshop.php');
    $products = getProducts();
    
    $id = $_GET['id'];
    var_dump($id);
    
    $item = $products[$id];

    echo '<h1>'
      .$item['name']
      .'
      </h1>
      <p>
      <img src="/educom-webshop-database/Images/'
      .$item['picture']
      .'" alt="'
      .$item['picture']
      .'" style="width:300px;height:380px;">
      </p>
      
      <p>'
      .$item['description']
      .'</p>
      
      <p>€'
      .$item['price']
      .',-';
      
      if (checkSession()){
        echo '<p><form method="post" action="index.php">
        <button type="submit" value="submit">Koop</button>
        </form></p>';
      }else{
       echo '<p><a href="index.php?page=login">Log in</a> om te bestellen.</p>';
   }
}

function addToCart(){
    $products = getProducts();
    $id = $_GET['id']; 
    $item = $products['id'];
    $_SESSION['item'] = $item;
}

?>