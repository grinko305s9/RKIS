<?php include 'header.php';
$q=trim($_GET['q']??''); $l="%$q%";
$books=booksQuery($pdo,'title LIKE ? OR author LIKE ? OR genre LIKE ?',[$l,$l,$l]);?>
<?=$_GET['m']??''?'<div class="flash">'.e($_GET['m']).'</div>':''?>
<form class="row sbar"><input name="q" value="<?=e($q)?>" placeholder="Найти книгу, автора или жанр...">
<?php if(isAdmin())echo '<button type="button" class="btn" onclick="dAdd.showModal()">+ Добавить книгу</button>';?></form>
<?php renderBooks($books);?>
<dialog id="dAdd"><button class="x" onclick="dAdd.close()">×</button><h2>Добавить книгу</h2>
<form method="post" action="actions.php?a=add"><input name="title" placeholder="Название" required><input name="author" placeholder="Автор" required>
<input name="isbn" placeholder="ISBN"><input name="genre" placeholder="Жанр"><button class="btn w">Добавить</button></form></dialog>
<?php include 'footer.php';?>
