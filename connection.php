<?php
    $server = "localhost";
    $user = "root";
    $pw = "";
    $dbname = "wordpress";
    //Connection
    //$conn = mysqli_connect($server,$user,$pw,$dbname);
    try{
        $conn = new PDO("mysql:host=$server;dbname=$dbname", $user, $pw);
        $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
    }catch(PDOException $e){
        echo "Erro";
    }
?>


