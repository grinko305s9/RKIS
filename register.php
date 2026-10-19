<?php require 'config.php'; $err='';
if($_SERVER['REQUEST_METHOD']=='POST'){
  $n=trim($_POST['name']);$em=trim($_POST['email']);$p=$_POST['password'];
  if(!filter_var($em,FILTER_VALIDATE_EMAIL)||strlen($p)<6||$n==='')$err='Проверьте данные (пароль от 6 символов)';
  else{$c=$pdo->prepare('SELECT COUNT(*) FROM users WHERE email=?');$c->execute([$em]);
    if($c->fetchColumn())$err='Такой email уже зарегистрирован';
    else{$pdo->prepare("INSERT INTO users(name,email,password,role,ticket) VALUES(?,?,?,'reader',?)")->execute([$n,$em,password_hash($p,PASSWORD_DEFAULT),'1'.rand(1000,9999)]);
      $s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([$em]);$_SESSION['u']=$s->fetch();header('Location: index.php');exit;}}}
include 'header.php';?>
<div class="card auth"><h2>Регистрация читателя</h2><?=$err?'<div class="flash bad">'.$err.'</div>':''?>
<form method="post"><input name="name" placeholder="ФИО" required><input type="email" name="email" placeholder="Email" required><input type="password" name="password" placeholder="Пароль" required><button class="btn w">Зарегистрироваться</button></form>
<p class="muted">Уже есть аккаунт? <a href="login.php">Войти</a></p></div></main></body></html>
