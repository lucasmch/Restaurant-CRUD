<?php
if (!isset($_SESSION)) session_start();
  setcookie('token', null, -1, '/');
  session_destroy();
?>