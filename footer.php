</main>
<dialog id="dBook"><button class="x" onclick="dBook.close()">×</button><h2 id="bT"></h2><div class="muted" id="bA"></div>
<p><span class="tag" id="bG"></span> <span class="pill ok" id="bS"></span></p><p class="muted" id="bI"></p>
<form method="post" action="actions.php?a=request"><input type="hidden" name="book_id" id="bId"><button class="btn w" id="bBtn">Взять книгу</button></form></dialog>
<script>
document.querySelectorAll('.book').forEach(c=>c.onclick=()=>{const d=c.dataset;bT.textContent=d.icon+' '+d.title;bA.textContent=d.author;bG.textContent=d.genre;
bS.textContent=d.busy=='1'?'Выдана':'Доступна';bS.className='pill '+(d.busy=='1'?'out':'ok');bI.textContent='ISBN: '+d.isbn;bId.value=d.id;
<?php if(user())echo "bBtn.style.display=d.busy=='1'?'none':'block';";else echo "bBtn.type='button';bBtn.onclick=()=>location='login.php';";?>dBook.showModal();});
</script></body></html>
