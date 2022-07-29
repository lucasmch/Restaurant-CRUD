<?php
require_once("../actions/database.php");

if (!isset($_SESSION)) session_start();
require_once("./checkSession.php");

if(!filterInput($_POST["submit"])) {
  header('Location: ../');
  exit;
}

if(filterInput($_POST["submit"]) == "deleteProduct") {
  $id = filterInput($_POST["id"]);

  $sql = "DELETE FROM produtos WHERE id = ".$id;
  $result = mysqli_query($conn, $sql);

  if(file_exists("../images/produtos/produto".$id.".png")){
    unlink("../images/produtos/produto".$id.".png");
  }

  header('Location: ../pages/produtos.html');
  exit;
} else if(filterInput($_POST["submit"]) == "deleteCategory") {
  $id = filterInput($_POST["id"]);

  $sql = "DELETE FROM categorias WHERE id = ".$id;
  $result = mysqli_query($conn, $sql);

  if(file_exists("../images/categorias/categoria".$id.".png")){
    unlink("../images/categorias/categoria".$id.".png");
  }

  $sql2 = "SELECT * FROM produtos WHERE category = ".$id;
  $result2 = mysqli_query($conn, $sql2);

  while($row = mysqli_fetch_assoc($result2)) {
    if(file_exists("../images/produtos/produto".$row["id"].".png")){
      unlink("../images/produtos/produto".$row["id"].".png");
    }
  }

  $sql3 = "DELETE FROM produtos WHERE category = ".$id;
  $result3 = mysqli_query($conn, $sql3);

  header('Location: ../pages/categorias.html');
  exit;
} else if(filterInput($_POST["submit"]) == "deleteAccount") {
    $id = filterInput($_POST["id"]);
  
    $sql = "DELETE FROM contas WHERE id = ".$id;
    $result = mysqli_query($conn, $sql);
  
    header('Location: ../pages/contas.html');
    exit;
} else {
  header('Location: ../');
  exit;
}

mysqli_close($conn);

function filterInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>