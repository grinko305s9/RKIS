<?php include 'header.php'; need();
$s=$pdo->prepare('SELECT l.*,b.title,b.author,b.id bid,DATEDIFF(CURDATE(),l.due_date) late FROM loans l JOIN books b ON b.id=l.book_id WHERE l.user_id=? AND returned_at IS NULL ORDER BY due_date');
$s->execute([user()['id']]);$rows=$s->fetchAll();
if(!$rows)echo '<p class="muted">У вас нет книг на руках. Выберите книгу в каталоге.</p>';
foreach($rows as $r){$late=$r['late']>0;?>
<div class="card loan <?=$late?'late':''?>"><span class="ico"><?=icon($r['bid'])?></span><div><h3><?=e($r['title'])?></h3><div class="muted"><?=e($r['author'])?></div>
<small class="muted">Взята: <?=date('d.m.Y',strtotime($r['taken_at']))?> · Вернуть до: <?=date('d.m.Y',strtotime($r['due_date']))?></small></div>
<span class="pill <?=$late?'bad':'good'?>"><?=$late?'Просрочено '.$r['late'].' дн.':'В срок'?></span></div>
<?php } include 'footer.php';?>
