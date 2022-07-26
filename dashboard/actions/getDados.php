<?php
header('Access-Control-Allow-Origin: *'); // RETIRAR QUANDO FOR PRA PROD
require_once("./database.php");
$dados = json_decode(file_get_contents('php://input'), true);
$res = [];

if($dados == "Dashboard") {
  $sql = "SELECT * FROM produtos";
  $result = mysqli_query($conn, $sql);
  $res["produtos"] = mysqli_num_rows($result);
  
  $sql = "SELECT * FROM categorias";
  $result = mysqli_query($conn, $sql);
  $res["categorias"] = mysqli_num_rows($result);
  
  $res["clientes"] = "0";
  $res["compras"] = "0";

  echo json_encode($res);
  mysqli_close($conn);
}

/* if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $res[] = $row;
  }
} else {
  $res = "0";
} */
?>