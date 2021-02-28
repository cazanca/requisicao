<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siana</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url("public/css/main.css") ?>">
    <?= $v->section('styles') ?>
</head>
<body>
    
    <div class="auth-wrap">
        
        <div class="auth-form">
            <h2 class="auth-title">Login</h2>
            <div class="response"></div>
           <?= $v->section('content') ?>
        </div>
    </div>
    <script src="<?= url("public/js/jquery.js") ?>"></script>
    <script src="<?= url("public/js/form.js") ?>"></script>
    <?= $v->section('scripts') ?>
</body>
</html>