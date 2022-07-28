<?php
if (!isset($_SESSION['token'])) {
  if($_COOKIE['token']) {
    $token = filterInput($_COOKIE['token']);
    $sql = "SELECT token, last_update FROM contas WHERE token = '$token' and actived = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) {
      $resultado = mysqli_fetch_assoc($result);
      if (strtotime(date("Y-m-d H:i:s")) - strtotime($resultado["last_update"]) > 172800) { /* 172800 */
        setcookie('token', null, -1, '/');
        header('Location: ../pages/sign-in.html');
        http_response_code(401);
        exit;
      } else {
        $_SESSION['token'] = $token;
      }
    } else {
      header('Location: ../pages/sign-in.html');
      http_response_code(401);
      exit;
    }
  } else {
    header('Location: ../pages/sign-in.html');
    http_response_code(401);
    exit;
  }
}
?>