<?php
  /* IMPORTA DB E INICIA SESSÃO */
  require_once("../actions/database.php");
  if (!isset($_SESSION)) session_start();

  /* VERIFICA SE A PESSOA JÁ ESTÁ LOGADA */
  if(isset($_SESSION["token"])) {
    header('Location: ../');
    exit;
  }

  /* VERFICA SE VEIO DO FORM */
  if(!isset($_POST["submit"])) {
    header("Location: ../pages/sign-in.html?error=expiresSession");
    exit;
  }

  /* FILTRA OS CAMPOS */
  $email = filterInput($_POST["email"]);
  $senha = filterInput($_POST["senha"]);

  /* VERIFICA CAMPOS VAZIOS */
  if (!$email or !$senha) {
    header("Location: ../pages/sign-in.html?error=missingArguments");
    exit;
  }

  /* PROCURAR CONTA */
  $sql = "SELECT * FROM contas WHERE email = '$email' and actived = 1 LIMIT 1";
  $result = mysqli_query($conn, $sql);

  /* ACHOU A CONTA */
  if(mysqli_num_rows($result) == 1) {
    $resultado = mysqli_fetch_assoc($result);

    /* VERIFICAR SENHA */
    if(!password_verify($senha, $resultado["senha"])) {
      header("Location: ../pages/sign-in.html?error=invalidLogin");
      exit;
    }

    /* GERAR TOKEN */
    $generateToken = generateRandomString(30);

    /* SALVAR TOKEN DB */
    $sql2 = "UPDATE contas SET token = '$generateToken' WHERE email = '$email'";
    $result = mysqli_query($conn, $sql2);

    /* SALVAR TOKEN EM COOKIES E SESSION */
    $_SESSION['token'] = $generateToken;
    if(isset($_POST["remember"])) {
      setcookie("token",$generateToken, time() + 172800,  $path = "/");
    }
    
    header("Location: ../"); 
    exit;
  } else {
    header("Location: ../pages/sign-in.html?error=invalidLogin");
    exit;
  }

  mysqli_close($conn);
  function filterInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  function generateRandomString($size = 7){
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuwxyz0123456789";
    $randomString = '';
    for($i = 0; $i < $size; $i = $i+1){
       $randomString .= $chars[mt_rand(0,60)];
    }
    return $randomString;
 }