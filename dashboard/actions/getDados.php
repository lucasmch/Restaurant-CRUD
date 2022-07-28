<?php
header('Access-Control-Allow-Origin: *'); // RETIRAR QUANDO FOR PRA PROD
require_once("./database.php");
$dados = json_decode(file_get_contents('php://input'), true);
$res = [];

if(!$dados) {
  header('Location: ../');
  exit;
}

if($dados == "Dashboard") {
  $sql = "SELECT * FROM produtos";
  $result = mysqli_query($conn, $sql);
  $res["produtos"] = mysqli_num_rows($result);
  
  $sql = "SELECT * FROM categorias";
  $result = mysqli_query($conn, $sql);
  $res["categorias"] = mysqli_num_rows($result);
  
  $res["contas"] = "0";
  $res["compras"] = "0";

} else if ($dados == "Categorias") {
  $sql = "SELECT * FROM categorias";
  $result = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($result)) {
    $sql2 = "SELECT * FROM produtos WHERE category = ".$row["id"];
    $result2 = mysqli_query($conn, $sql2);

    $row["quantityProducts"] = mysqli_num_rows($result2)." Produtos";
    if (mysqli_num_rows($result2) == 1) {
      $row["quantityProducts"] = mysqli_num_rows($result2)." Produto";
    } else if (mysqli_num_rows($result2) == 0) {
      $row["quantityProducts"] = "Nenhum produto";
    }

    $res[] = $row;
  }
} else if ($dados == "Produtos") {
  $sql = "SELECT * FROM produtos";
  $result = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_assoc($result)) {
    $sql2 = "SELECT * FROM categorias WHERE id = ".$row["category"];
    $result2 = mysqli_query($conn, $sql2);
    $row["category"] = mysqli_fetch_assoc($result2)["name"];

    $res[] = $row;
  }
} else if ($dados == "Config") {
  $sql = "SELECT * FROM empresa";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    $res[] = $row;
  }
} else {
  header('Location: ../');
  exit;
}
echo json_encode($res);
mysqli_close($conn);
?>