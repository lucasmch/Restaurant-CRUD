<?php
require_once("../actions/database.php");

if(!filterInput($_POST["submit"])) {
  header('Location: ../');
  exit;
}

if(filterInput($_POST["submit"]) == "createProduct") {
  $nome = filterInput($_POST["nome"]);
  $valor = filterInput($_POST["valor"]);
  $categoria = filterInput($_POST["categoria"]);
  $descricao = filterInput($_POST["descricao"]);

  /* VALIDAR SE TODOS OS CAMPOS ESTÃO PREENCHIDOS */
  if(!$nome or !$valor or !$categoria or !$descricao){
    if(!$categoria) {
      header('Location: ../pages/newProduct.php?error=errorCategory');
    } else {
      header('Location: ../pages/newProduct.php?error=missingArguments');
    }
    exit;
  }

  /* VALIDAR SE A IMAGEM FOI ENVIADA */
  if(!array_key_exists("image", $_FILES) or $_FILES["image"]["name"] == "" or $_FILES["image"]["name"] == null or getimagesize($_FILES["image"]["tmp_name"]) === false){
    header('Location: ../pages/newProduct.php?error=noImage');
    exit;
  }
  
  /* VALIDAR A EXTENSÃO DA IMAGEM */
  $imageFileType = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    header('Location: ../pages/newProduct.php?error=incorrectImage');
    exit;
  }
  
  $sql = "INSERT INTO produtos (name, value, description, category) VALUES ('$nome', '$valor', '$descricao', '$categoria')";
  if (mysqli_query($conn, $sql)) {
    $last_id = mysqli_insert_id($conn);

    /* FAZER UPLOAD DA IMAGEM */
    $target_file = '../images/produtos/' . "produto".$last_id.".png";
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $sql2 = "DELETE FROM produtos WHERE id = ".$last_id;
      $result2 = mysqli_query($conn, $sql2);
      header('Location: ../pages/newProduct.php?error=errorUnknown');
      exit;
    }
    
    header('Location: ../pages/produtos.html');
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
} else if(filterInput($_POST["submit"]) == "createCategory") {
  $nome = filterInput($_POST["nome"]);
  $descricao = filterInput($_POST["descricao"]);

  /* VALIDAR SE TODOS OS CAMPOS ESTÃO PREENCHIDOS */
  if(!$nome or !$descricao){
    header('Location: ../pages/newCategory.php?error=missingArguments');
    exit;
  }

  /* VALIDAR SE A IMAGEM FOI ENVIADA */
  if(!array_key_exists("image", $_FILES) or $_FILES["image"]["name"] == "" or $_FILES["image"]["name"] == null or getimagesize($_FILES["image"]["tmp_name"]) === false){
    header('Location: ../pages/newCategory.php?error=noImage');
    exit;
  }
  
  /* VALIDAR A EXTENSÃO DA IMAGEM */
  $imageFileType = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    header('Location: ../pages/newCategory.php?error=incorrectImage');
    exit;
  }
  
  $sql = "INSERT INTO categorias (name, description) VALUES ('$nome', '$descricao')";
  if (mysqli_query($conn, $sql)) {
    $last_id = mysqli_insert_id($conn);

    /* FAZER UPLOAD DA IMAGEM */
    $target_file = '../images/categorias/' . "categoria".$last_id.".png";
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $sql2 = "DELETE FROM categorias WHERE id = ".$last_id;
      $result2 = mysqli_query($conn, $sql2);
      header('Location: ../pages/newCategory.php?error=errorUnknown');
      exit;
    }
    
    header('Location: ../pages/categorias.html');
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}

mysqli_close($conn);

function filterInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>