<?php

session_start();

$id = intval( $_POST["id"] );
$quant = floatval( $_POST["quant"] );

$_SESSION["carrinho"][$id]["quant"] = $quant;
$_SESSION["carrinho"][$id]["subtotal"] =
    $quant * $_SESSION["carrinho"][$id]["valor"];

    header("location: receba.php");
   
