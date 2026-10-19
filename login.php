<?php require 'config.php'; $err='';
if($_SERVER['REQUEST_METHOD']=='POST'){
  $s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([trim($_POST['email'])]);$u=$s->fetch();
  if($u&&password_verify($_POST['password'],$u['password'])){$_SESSION['u']=$u;header('Location: index.php');exit;}
  $err='Неверный email или пароль';}
include 'header.php';?>
<div class="card auth"><h2>Вход</h2><?=$err?'<div class="flash bad">'.$err.'</div>':''?>
<form method="post"><input type="email" name="email" placeholder="Email" required><input type="password" name="password" placeholder="Пароль" required><button class="btn w">Войти</button></form>
<p class="muted">Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p></div></main></body></html>
