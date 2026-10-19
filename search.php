<?php include 'header.php';
$f=['title','author','isbn','genre'];$w=['1'];$p=[];$sent=isset($_GET['go']);
foreach($f as $k)if(trim($_GET[$k]??'')!==''){$w[]="$k LIKE ?";$p[]='%'.trim($_GET[$k]).'%';}?>
<div class="card"><h2>🔍 Расширенный поиск</h2><p class="muted">Найдите нужную книгу по различным критериям</p>
<form class="f2"><input type="hidden" name="go" value="1">
<label>Название книги<input name="title" placeholder="Например: 1984" value="<?=e($_GET['title']??'')?>"></label>
<label>Автор<input name="author" placeholder="Например: Оруэлл" value="<?=e($_GET['author']??'')?>"></label>
<label>ISBN<input name="isbn" placeholder="978-5-17-098825-6" value="<?=e($_GET['isbn']??'')?>"></label>
<label>Жанр<input name="genre" placeholder="Например: Фэнтези" value="<?=e($_GET['genre']??'')?>"></label>
<button class="btn w full">Найти книги</button></form></div>
<?php if($sent)renderBooks(booksQuery($pdo,implode(' AND ',$w),$p)); include 'footer.php';?>
