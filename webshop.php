<?php

function showWebshopHeading(){
    echo '<h1>Webshop</h1> <p>Zie hier al mijn mooie tweedehands en vintage kleding! 
    Log in om producten te bestellen.</p>';
}

function getProducts(){
  $servername = "localhost";
  $username = "sofie";
  $password = "UOIa(27t3rzexDM@";
  $dbname = "sofies_webshop";

  $conn = new mysqli($servername, $username, $password, $dbname);

  $sql =  "SELECT id, name, price, description, picture FROM products";
  $result = $conn->query($sql);
  $products = array();

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $products[$row['id']]['id'] = $row['id'];
        $products[$row['id']]['name'] = $row['name'];
        $products[$row['id']]['description'] = $row['description'];
        $products[$row['id']]['price'] = $row['price']; 
        $products[$row['id']]['picture'] = $row['picture'];      
      }
    } else {
    echo "0 results";
    }
    return $products;
  if ($conn->query($sql) === FALSE) {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

$conn->close();
}


function showProducts(){
    require_once('index.php');
    $products = getProducts();

    var_dump($products);

    echo '<table>'.PHP_EOL; 
    foreach ($products as $product)
    {
      echo '<tr><td>
      <img src="/educom-webshop-database/Images/'
      .$product['picture']
      .'" alt="'
      .$product['picture']
      .'" style="width:128px;height:160px;">
      </td><td>

      <a href="index.php?page=detail&id='
      .$product['id']
      .'">'
      .$product['name']
      .'</a>
      
      </td><td>€'

      .$product['price']
      .',-';

      if (checkSession()){
         cartButton();
      }else{
        echo '</td><td><a href="index.php?page=login">Log in</a> om te bestellen.';
    }
    }
echo '</table>'.PHP_EOL;    
}

function cartButton(){
    echo '</td><td>
         <button type="submit" value="submit">Koop</button>';
}

?>