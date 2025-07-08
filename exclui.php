<?php
session_start();

$id = intval($_GET["id"]);

array_splice( $_SESSION["carrinho"], $id, 1 );

header("location: receba.php");
