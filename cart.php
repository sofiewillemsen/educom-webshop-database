<?php

// Laat de heading zien van winkelwagen

function showCartHeading(){
    echo '<h1>Winkelwagen</h1>';
}

// Bereken de totale prijs van de cart

function totalPrice()
    {
        if (($_SESSION['cart_products']) !== NULL){
            $totalPrice = 0;
        foreach ($_SESSION['cart_products'] as $product)
        {
            $totalPrice += $product['price'];
        }
        return $totalPrice;
    }
    }

//Creëer een uniek ordernummer

function createOrderNumber(){
        $servername = "localhost";
        $username = "sofie";
        $password = "UOIa(27t3rzexDM@";
        $dbname = "sofies_webshop";
      
        $year = date("Y");
        $number = 10001;
        $firstOrderNumber = $year.$number;

        $conn = new mysqli($servername, $username, $password, $dbname);
      
        $sql =  "SELECT max(ordernumber) FROM orders";
        $result = $conn->query($sql);
        
        if ($result !== NULL) {
            $result = mysqli_fetch_assoc($result);
            $maxOrderNumber = $result['max(ordernumber)'];
            
            if (substr($maxOrderNumber, 0, 4) == $year)
            {
            $orderNumber = $maxOrderNumber+1;
            }
            else
            {
            $orderNumber = $firstOrderNumber;
            }
        }
        else
        {
        $orderNumber = $firstOrderNumber;
        }

        return $orderNumber;
        
     
       if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
      
      $conn->close();
    }

// Toon de producten in de cart, de totale prijs en de button voor afrekenen

function showCart(){

if (isset($_SESSION['cart_products'])){
    $totalPrice = totalPrice();
    $_SESSION['ordernumber'] = createOrderNumber();
    foreach ($_SESSION['cart_products'] as $product){

    echo '<table><tr><td>
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
      
      </td><td> €'

      .$product['price']
      .',-<br></td>
      </table>';
    }
    echo '<table><tr><br><b>Totaal: €'.$totalPrice.',- 
    </b></td>
    <td>';
    require_once('forms.php');
    openForm('cart','');
    echo '<input type="hidden" name="id" value="'.$product['id'].'">';
    echo '<input type="hidden" name="ordernumber" value="'.$_SESSION['ordernumber'].'"';
    closeForm($submit_caption="Afrekenen");
    '<br></td>
    </table><br>';
    }
    else{
        echo '<p>Uw winkelwagen is leeg. Ga naar de webshop om producten toe te voegen.</p>';
    }
}

// Schrijf de productid, het ordernummer en de hoeveelheid naar de database

function writeToOrdersRegel(){
    
 foreach ($_SESSION['cart_products'] as $product){
    $ordernumber = $_SESSION['ordernumber'];
    $productid = $product['id'];
    $amount = '1';

    $servername = "localhost";
    $username = "sofie";
    $password = "UOIa(27t3rzexDM@";
    $dbname = "sofies_webshop";

    $conn = new mysqli($servername, $username, $password, $dbname);
        
    $sql =  "INSERT INTO orders_regel (productid, ordernumber, amount) VALUES (".$productid.", ".$ordernumber.", ".$amount.")";

    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        $conn->close();
    }

}

// Schrijf het ordernummer, de datum en het userid naar de database

function writeToOrders(){
    $servername = "localhost";
    $username = "sofie";
    $password = "UOIa(27t3rzexDM@";
    $dbname = "sofies_webshop";
       
    $ordernumber = $_SESSION['ordernumber'];
    $date = date("dmY");
    $userid = $_SESSION['userid'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql =  "INSERT INTO orders (ordernumber, date, userid) VALUES (".$ordernumber.", ".$date.", ".$userid.")";


    if ($conn->query($sql) === FALSE) {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
    };

?>