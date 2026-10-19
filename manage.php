<?php include 'header.php'; need(true);
$total=$pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();
$out=$pdo->query('SELECT COUNT(*) FROM loans WHERE returned_at IS NULL')->fetchColumn();
$readers=$pdo->query("SELECT * FROM users WHERE role='reader' ORDER BY name")->fetchAll();
$avail=booksQuery($pdo,'(SELECT COUNT(*) FROM loans l WHERE l.book_id=b.id AND l.returned_at IS NULL)=0');
$req=$pdo->query("SELECT user_id,book_id FROM requests WHERE status='new'")->fetchAll();
$rq=[];foreach($req as $r)$rq[$r['user_id']][]=$r['book_id'];?>
<div class="stats"><div class="card"><small>Всего книг</small><b><?=$total?></b></div><div class="card"><small>Выдано</small><b class="blue"><?=$out?></b></div><div class="card red"><small>Просрочено</small><b class="redt"><?=$od?></b></div></div>
<div class="card"><h2>👥 Управление читателями</h2><p class="muted">Выдавайте, принимайте и продлевайте книги</p>
<?php foreach($readers as $r){
 $s=$pdo->prepare('SELECT l.*,b.title,DATEDIFF(CURDATE(),l.due_date) late FROM loans l JOIN books b ON b.id=l.book_id WHERE user_id=? AND returned_at IS NULL');$s->execute([$r['id']]);$ls=$s->fetchAll();
 $nreq=count($rq[$r['id']]??[]);?>
 <div class="card inner"><div class="row"><div><b><?=e($r['name'])?></b><div class="muted">Билет №<?=e($r['ticket'])?> <?=$nreq?'· заявок: '.$nreq:''?></div></div>
 <button class="btn ghost" onclick='issue(<?=$r['id']?>,<?=json_encode($r['name'])?>,<?=json_encode($rq[$r['id']]??[])?>)'>Выдать</button></div>
 <?php if(!$ls)echo '<i class="muted">Нет активных книг</i>';
 foreach($ls as $l){?><div class="row act"><span><?=e($l['title'])?><br><small class="<?=$l['late']>0?'redt':'muted'?>">До <?=date('d.m.Y',strtotime($l['due_date']))?><?=$l['late']>0?' · Просрочено '.$l['late'].' дн.':''?></small></span>
 <span><button class="ib" title="Продлить" onclick='ext(<?=$l['id']?>,<?=json_encode($l['title'])?>)'>🕓</button>
 <button class="ib" title="Принять" onclick='ret(<?=$l['id']?>,<?=json_encode($l['title'])?>)'>✓</button></span></div><?php }?></div>
<?php }?></div>
<dialog id="dI"><button class="x" onclick="dI.close()">×</button><h2 id="iT"></h2><p class="muted">Выберите книгу из доступных для выдачи (★ — по заявке)</p>
<div class="grid"><?php foreach($avail as $b)echo '<form method="post" action="actions.php?a=issue" data-b="'.$b['id'].'"><input type="hidden" name="user_id" class="uid"><input type="hidden" name="book_id" value="'.$b['id'].'"><button class="card pick"><b>'.e($b['title']).'</b><br><small class="muted">'.e($b['author']).'</small></button></form>';?></div></dialog>
<dialog id="dE"><button class="x" onclick="dE.close()">×</button><h2>🕓 Продлить срок</h2><p id="eT"></p><form method="post" action="actions.php?a=extend"><input type="hidden" name="loan_id" id="eId">
<label>Продлить на (дней)<input type="number" name="days" value="14" min="1" max="90"></label><button class="btn w">Продлить</button></form></dialog>
<dialog id="dR"><button class="x" onclick="dR.close()">×</button><h2>✅ Принять книгу</h2><p id="rT"></p><form method="post" action="actions.php?a=return"><input type="hidden" name="loan_id" id="rId"><button class="btn w green">Принять</button></form></dialog>
<script>
function issue(id,n,reqs){iT.textContent='Выдать книгу: '+n;document.querySelectorAll('#dI form').forEach(f=>{f.querySelector('.uid').value=id;const r=reqs.includes(+f.dataset.b);f.style.order=r?-1:0;f.querySelector('b').textContent=f.querySelector('b').textContent.replace('★ ','');if(r)f.querySelector('b').textContent='★ '+f.querySelector('b').textContent;});dI.showModal();}
function ext(id,t){eId.value=id;eT.textContent=t;dE.showModal();}
function ret(id,t){rId.value=id;rT.textContent=t;dR.showModal();}
</script></main></body></html>
