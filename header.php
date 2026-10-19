<?php require_once 'config.php'; $od=overdueCount($pdo); $u=user();
function tab($f,$t,$cur){return '<a href="'.$f.'" class="'.($cur==$f?'on':'').'">'.$t.'</a>';}
$cur=basename($_SERVER['SCRIPT_NAME']);?>
<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Библиотека</title><link rel="stylesheet" href="style.css"></head><body>
<header><a class="logo" href="index.php"><span class="lg">📚</span><span><b>Библиотека</b><small>Система управления</small></span></a>
<div class="hr"><?php if($u){ if($od)echo '<span class="badge">🔔 '.$od.' просрочено</span>'; echo '<span class="chip">'.($u['role']=='admin'?'Администратор':e($u['name'])).'</span><a class="btn ghost" href="logout.php">Выход</a>'; }
else echo '<a class="btn ghost" href="login.php">Вход</a><a class="btn" href="register.php">Регистрация</a>';?></div></header>
<main><nav class="tabs"><?=tab('index.php','Каталог',$cur).tab('search.php','Поиск',$cur)?>
<?php if($u)echo tab('my.php','Мои книги',$cur); if(isAdmin())echo tab('manage.php','Управление',$cur);?></nav>
