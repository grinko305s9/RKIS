<?php require 'config.php'; need(); $a=$_GET['a']??''; $back='index.php'; $m='';
if($_SERVER['REQUEST_METHOD']!=='POST')exit(header('Location: index.php'));
$bid=(int)($_POST['book_id']??0);
if($a=='request'){
  $c=$pdo->prepare('SELECT COUNT(*) FROM loans WHERE book_id=? AND returned_at IS NULL');$c->execute([$bid]);
  if($c->fetchColumn())$m='Книга уже выдана';
  else{$d=$pdo->prepare("SELECT COUNT(*) FROM requests WHERE book_id=? AND user_id=? AND status='new'");$d->execute([$bid,user()['id']]);
    if(!$d->fetchColumn())$pdo->prepare('INSERT INTO requests(book_id,user_id) VALUES(?,?)')->execute([$bid,user()['id']]);
    $m='Заявка отправлена администратору';}
}elseif(isAdmin()){
  if($a=='add'){$pdo->prepare('INSERT INTO books(title,author,isbn,genre) VALUES(?,?,?,?)')->execute([trim($_POST['title']),trim($_POST['author']),trim($_POST['isbn']),trim($_POST['genre'])]);$m='Книга добавлена';}
  if($a=='issue'){$uid=(int)$_POST['user_id'];$back='manage.php';
    $pdo->prepare('INSERT INTO loans(book_id,user_id,taken_at,due_date) VALUES(?,?,CURDATE(),DATE_ADD(CURDATE(),INTERVAL 14 DAY))')->execute([$bid,$uid]);
    $pdo->prepare("UPDATE requests SET status='issued' WHERE book_id=? AND user_id=?")->execute([$bid,$uid]);}
  if($a=='extend'){$back='manage.php';$pdo->prepare('UPDATE loans SET due_date=DATE_ADD(due_date,INTERVAL ? DAY) WHERE id=?')->execute([max(1,(int)$_POST['days']),(int)$_POST['loan_id']]);}
  if($a=='return'){$back='manage.php';$pdo->prepare('UPDATE loans SET returned_at=CURDATE() WHERE id=?')->execute([(int)$_POST['loan_id']]);}
}
header('Location: '.$back.($m?'?m='.urlencode($m):''));
