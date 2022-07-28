<?php
require_once("../actions/database.php");

if(!filterInput($_POST["submit"])) {
  header('Location: ../');
  exit;
}

if(filterInput($_POST["submit"]) == "updateConfig") {
  $nome = filterInput($_POST["nome"]);
  $telefone = filterInput($_POST["telefone"]);
  $endereco = filterInput($_POST["endereco"]);
  $email = filterInput($_POST["email"]);
  $descricao = filterInput($_POST["descricao"]);

  if(!$nome or !$telefone or !$endereco or !$email or !$descricao){
    header('Location: ../pages/config.html?error=missingArguments');
    exit;
  }

  $sql = "UPDATE empresa SET nome='$nome', email='$email', telefone='$telefone', endereco='$endereco', descricao='$descricao'";
  if (mysqli_query($conn, $sql)) {
    header('Location: ../pages/config.html');
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  
} else if (filterInput($_POST["submit"]) == "updateProduct") {
  $id = filterInput($_POST["id"]);
  $imageVersion = filterInput($_POST["imageVersion"]);
  $nome = filterInput($_POST["nome"]);
  $valor = filterInput($_POST["valor"]);
  $categoria = filterInput($_POST["categoria"]);
  $descricao = filterInput($_POST["descricao"]);
  $ativado = "0";

  if(isset($_POST["ativado"])) {
    $ativado = "1";
  }

  /* VALIDAR SE TODOS OS CAMPOS ESTÃO PREENCHIDOS */
  if(!$nome or !$valor or !$categoria or !$descricao){
    if(!$categoria) {
      header('Location: ../pages/newCategory.php?error=errorCategory');
    } else {
      header('Location: ../pages/produtos.html?error=missingArguments');
    }
    exit;
  }

  /* VALIDAR SE A IMAGEM FOI ENVIADA E SE SIM, TROCAR ELA*/
  if(array_key_exists("image", $_FILES) and $_FILES["image"]["name"] != "" and $_FILES["image"]["name"] != null and getimagesize($_FILES["image"]["tmp_name"]) !== false){
    /* VALIDAR A EXTENSÃO DA IMAGEM */
    $imageFileType = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    if($imageFileType == "jpg" or $imageFileType == "png" or $imageFileType == "jpeg" or $imageFileType == "gif" ) {
      $target_file = '../images/produtos/' . "produto".$id.".png";
      move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
      $imageVersion = $imageVersion + 0.1;
    }
  }
  

  $sql = "UPDATE produtos SET name='$nome', value='$valor', description='$descricao', category='$categoria', imageVersion='$imageVersion', actived='$ativado' WHERE id = $id";
  if (mysqli_query($conn, $sql)) {
    header('Location: ../pages/produtos.html');
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
} else if (filterInput($_POST["submit"]) == "updateCategory") {
  $id = filterInput($_POST["id"]);
  $imageVersion = filterInput($_POST["imageVersion"]);
  $nome = filterInput($_POST["nome"]);
  $descricao = filterInput($_POST["descricao"]);

  /* VALIDAR SE TODOS OS CAMPOS ESTÃO PREENCHIDOS */
  if(!$nome or !$descricao){
    header('Location: ../pages/categorias.html?error=missingArguments');
    exit;
  }

  /* VALIDAR SE A IMAGEM FOI ENVIADA E SE SIM, TROCAR ELA*/
  if(array_key_exists("image", $_FILES) and $_FILES["image"]["name"] != "" and $_FILES["image"]["name"] != null and getimagesize($_FILES["image"]["tmp_name"]) !== false){
    /* VALIDAR A EXTENSÃO DA IMAGEM */
    $imageFileType = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    if($imageFileType == "jpg" or $imageFileType == "png" or $imageFileType == "jpeg" or $imageFileType == "gif" ) {
      $target_file = '../images/categorias/' . "categoria".$id.".png";
      move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
      $imageVersion = $imageVersion + 0.1;
    }
  }

  $sql = "UPDATE categorias SET name='$nome', description='$descricao', imageVersion='$imageVersion' WHERE id = $id";
  if (mysqli_query($conn, $sql)) {
    header('Location: ../pages/categorias.html');
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