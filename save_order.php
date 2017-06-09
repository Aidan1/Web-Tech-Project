<?php
header("Access-Control-Allow-Origin: *");
header("Content-type:text/plain");

// Construct PHP's "customer" and "order" object
// instances from JSON string
$jsonstr_customer = $_REQUEST["customer"];
$jsonstr_order = $_REQUEST["order"];
// ???
echo $jsonstr_customer."\n";
echo $jsonstr_order."\n";
echo "\n";

// Connect and select database.
$db_conn = mysqli_connect("localhost","tce","a13cs0201","db_tce");

// Check connection
if (mysqli_connect_errno()) {
    $error = mysqli_connect_error();
    echo "{error: \"$error\", customer: $jsonstr_customer, order: $jsonstr_order}";
    
} else {
    $customer = json_decode($jsonstr_customer);
    $orders = json_decode($jsonstr_order, true);
    // Try to save customer order information into the database.
    // ???
    echo var_dump($customer)."\n";
    echo var_dump($orders)."\n";
    echo "\n";

    $error = "";
    
    $sql = "insert into `customer` (name, address, area) VALUES('".$customer->name."', '".$customer->address."', '".$customer->area."')";
    echo $sql."\n";
    echo "\n";
    if (!mysqli_query($db_conn, $sql)) {
        $error = mysqli_error($db_conn);
    }

    $customerId = $db_conn->insert_id;
    
    foreach($orders as $key => $value) {
        $item = $key;
        foreach ($value as $key2 => $value2) {
                if(strcmp($key2, "price") == 0)
                    $price = $value2;
                else if(strcmp($key2, "qty") == 0)
                    $qty = $value2;

            }
        $error = "";
        $sql = "insert into `order` (id_customer, item, price, qty) VALUES('".$customerId."', '".str_replace("'", "\'", $item)."', '".$price."', '".$qty."')";
        echo $sql."\n";
        echo "\n";
        if (!mysqli_query($db_conn, $sql)) {
            $error .= mysqli_error($db_conn);
        }   
    }
  
    if ($error != "") {
        echo "{error: \"$error\", \n customer: $jsonstr_customer, \n order: $jsonstr_order}";
        
    } else {
        echo "{error: null, \n customer: $jsonstr_customer, \n order: $jsonstr_order}";
    }
}
?>
