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
  $ativado = 0;

  if(isset($_POST["ativado"])) {
    $ativado = 1;
  }

  /* VALIDAR SE TODOS OS CAMPOS ESTÃO PREENCHIDOS */
  if(!$nome or !$valor or !$categoria or !$descricao){
    if(!$categoria) {
      header('Location: ../pages/newCategory.php?error=errorCategory');
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
  
  $sql = "INSERT INTO produtos (name, value, description, category, actived) VALUES ('$nome', '$valor', '$descricao', '$categoria', '$ativado')";
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
} else if(filterInput($_POST["submit"]) == "createAccount") {
  $nome = filterInput($_POST["nome"]);
  $email = filterInput($_POST["email"]);
  $senha = filterInput($_POST["senha"]);
  $telefone = filterInput($_POST["telefone"]);
  $actived = 0;

  if(isset($_POST["ativado"])) {
    $actived = 1;
  }

  /* VALIDAR SE TODOS OS CAMPOS ESTÃO PREENCHIDOS */
  if(!$nome or !$email or !$senha or !$telefone){
    header('Location: ../pages/newAccount.php?error=missingArguments');
    exit;
  }

  $senha = password_hash($senha, PASSWORD_DEFAULT);
  
  $sql = "INSERT INTO contas (name, email, senha, telefone, actived) VALUES ('$nome', '$email', '$senha', '$telefone', '$actived')";
  if (mysqli_query($conn, $sql)) {
    header('Location: ../pages/contas.html');
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
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