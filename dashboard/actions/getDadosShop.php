<?php
header('Access-Control-Allow-Origin: *'); // RETIRAR QUANDO FOR PRA PROD
require_once("./database.php");
$dados = json_decode(file_get_contents('php://input'), true);
$res = [];

if(!$dados) {
  header('Location: ../');
  exit;
}

// DADOS DA EMPRESA
$sqlEmp = "SELECT * FROM empresa";
$resultEmp = mysqli_query($conn, $sqlEmp);
$rowEmp = mysqli_fetch_assoc($resultEmp);
$res["empresa"] = $rowEmp;

// DADOS DAS CATEGORIAS E PRODUTOS
$sql = "SELECT * FROM categorias";
$result = mysqli_query($conn, $sql);
$res["categorias"] = [];
while($row = mysqli_fetch_assoc($result)) {
  $temp = $row;
  $sql2 = "SELECT * FROM produtos WHERE category = ".$row["id"];
  $result2 = mysqli_query($conn, $sql2);

  $temp["quantityProducts"] = mysqli_num_rows($result2)." Produtos";
  if (mysqli_num_rows($result2) == 1) {
    $temp["quantityProducts"] = mysqli_num_rows($result2)." Produto";
  } else if (mysqli_num_rows($result2) == 0) {
    $temp["quantityProducts"] = "Nenhum produto";
  }
  $temp["produtos"] = [];

  while($row2 = mysqli_fetch_assoc($result2)) {
    $temp["produtos"][] = $row2;
  }

  $res["categorias"][$row["name"]] = $temp;
}

echo json_encode($res);
mysqli_close($conn);
?>