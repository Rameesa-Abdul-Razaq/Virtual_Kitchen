<?php
// setting the database connection parameters

$host = 'localhost';  // the local phpmyadmin host
$dbname = 'u_240130808_virtual_kitchen';  // the database name
$username = 'u-240130808';  // database username
$password = '297jmUNnmSQzMD6';  // charset for connection

// set up the DSN 
//$dsn = "mysql:host=$host;dbname=$dbname;charset=$pa";

// set up options for the PDO
$options = [
   PDO::ATTR_ERRMODE  => PDO::ERRMODE_EXCEPTION, // this enables exceptions for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Set default mode to associative array
    PDO::ATTR_EMULATE_PREPARES => false,  // disable emulation of prepared statements
];

try {
    // creating a PDO instance to connect the database
   // $db = new PDO("mysql:dbname=$dbname;host=$host", $username, $password); 
   $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $options);

    // if the connection is successful, we can use the $db object to interact with the database
   // echo "Connection successful";
} catch (PDOException $e) {
    // if an error occurs, catch the exception and display the error
    echo "Connection failed: " . $e->getMessage();
}
?>
