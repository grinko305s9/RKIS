<?php
session_start();
$pdo=new PDO('mysql:host=localhost;dbname=rkis;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
function user(){return $_SESSION['u']??null;}
function isAdmin(){return (user()['role']??'')==='admin';}
function need($admin=false){if(!user()||($admin&&!isAdmin())){header('Location: login.php');exit;}}
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES);}
function icon($id){$i=['📖','📚','📕','📗','📘','📙'];return $i[$id%6];}
function overdueCount($pdo){
  if(!user())return 0;
  $q='SELECT COUNT(*) FROM loans WHERE returned_at IS NULL AND due_date<CURDATE()';
  if(isAdmin())$s=$pdo->query($q);else{$s=$pdo->prepare($q.' AND user_id=?');$s->execute([user()['id']]);}
  return (int)$s->fetchColumn();
}
function booksQuery($pdo,$where='1',$p=[]){
  $s=$pdo->prepare("SELECT b.*,(SELECT COUNT(*) FROM loans l WHERE l.book_id=b.id AND l.returned_at IS NULL) busy FROM books b WHERE $where ORDER BY b.id");
  $s->execute($p);return $s->fetchAll();
}
function renderBooks($books){
  if(!$books){echo '<p class="muted">Ничего не найдено</p>';return;}
  echo '<div class="grid">';
  foreach($books as $b){
    echo '<div class="card book" tabindex="0" data-id="'.$b['id'].'" data-title="'.e($b['title']).'" data-author="'.e($b['author']).'" data-isbn="'.e($b['isbn']).'" data-genre="'.e($b['genre']).'" data-busy="'.$b['busy'].'" data-icon="'.icon($b['id']).'">'
      .'<div class="row"><span class="ico">'.icon($b['id']).'</span><span class="pill '.($b['busy']?'out':'ok').'">'.($b['busy']?'Выдана':'Доступна').'</span></div>'
      .'<h3>'.e($b['title']).'</h3><div class="muted">'.e($b['author']).'</div><span class="tag">'.e($b['genre']).'</span></div>';
  }
  echo '</div>';
}
