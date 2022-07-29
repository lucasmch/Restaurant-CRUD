<?php
header('Access-Control-Allow-Origin: *'); // RETIRAR QUANDO FOR PRA PROD
require_once("./database.php");
$dados = json_decode(file_get_contents('php://input'), true);
$res = [];

if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['token'])) {
  if(isset($_COOKIE['token'])) {
    $token = filterInput($_COOKIE['token']);
    $sql = "SELECT token, last_update FROM contas WHERE token = '$token' and actived = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) {
      $resultado = mysqli_fetch_assoc($result);
      if (strtotime(date("Y-m-d H:i:s")) - strtotime($resultado["last_update"]) > 172800) { /* 172800 */
        setcookie('token', null, -1, '/');
        http_response_code(401);
        exit;
      } else {
        $_SESSION['token'] = $token;
      }
    } else {
      http_response_code(401);
      exit;
    }
  } else {
    http_response_code(401);
    exit;
  }
}

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


  $sql = "SELECT * FROM contas";
  $result = mysqli_query($conn, $sql);
  $res["contas"] = mysqli_num_rows($result);
  
  $res["compras"] = "Fechado";

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
} else if ($dados == "Contas") {
  $sql = "SELECT * FROM contas";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
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

$sqlEmp = "SELECT * FROM empresa";
$resultEmp = mysqli_query($conn, $sqlEmp);
$rowEmp = mysqli_fetch_assoc($resultEmp);
$res["empName"] = $rowEmp["nome"];

echo json_encode($res);
mysqli_close($conn);

function filterInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>