<?php
require_once("./database.php");

/* 
  function minhaFuncaoPHP($param1, $param2){
    return ($param1+$param2);
  }
  $dados = json_decode(file_get_contents('php://input'), true);
  $resposta = minhaFuncaoPHP($dados['campo1'], $dados['campo2']);
  echo json_encode($resposta); 
*/


$sql = "SELECT * FROM produtos";
$result = mysqli_query($conn, $sql);
$test = '';

if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $test = $test.",".json_encode($row);
  }
  $test = substr($test, 1);
  $test = "[".$test."]";
  echo $test;
} else {
  echo "0";
}

mysqli_close($conn);
?>