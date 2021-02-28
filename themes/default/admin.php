<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $title ?? 'Stock' ?></title>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= url("public/datatables/datatables.min.css") ?>">
<link rel="stylesheet" href="<?= url("public/css/main.css") ?>">
<link rel="stylesheet" href="<?= url("public/css/paginator.css") ?>">
<?= $v->section('styles') ?>
</head>
<body>
<div class="response">
<?= flash() ?>
</div>
<nav class="navbar">
    <div class="left">
        <ion-icon id="toggle-menu" name="grid-outline" class="mr-2"></ion-icon>
    </div>
    <div class="right">
        <a href="<?= url('/profile') ?>" class="mr-2"><?= auth()->last_name ?></a>
        <a href="<?= url('/logout') ?>"><ion-icon name="log-out-outline"></ion-icon></a>
    </div>
</nav>
<aside class="sidebar">
    <div class="logo">
        <a href="<?= url() ?>">
            <ion-icon name="logo-amplify"></ion-icon><span> <?= CONF_SITE_NAME ?></span>
        </a>
        <ion-icon name="close-circle-outline" class="close-menu"></ion-icon>
    </div>
    <div class="menu">
    <?php if(auth()->access_level == "admin"): ?>
        <span class="menu-label">admin</span>

        <div class="menu-itens">
            <a class="home-link" href="<?= url('/home') ?>">
                <ion-icon name="home"></ion-icon>
                <span>Dashboard</span>
            </a>
            <a class="user-link" href="<?= url('/usuario') ?>">
                <ion-icon name="people"></ion-icon>
                <span>Usuários</span>
            </a>
            <a class="cat-link" href="<?= url('/categoria') ?>">
                <ion-icon name="bookmarks"></ion-icon>
                <span>Categorias</span>
            </a>
            <a class="dep-link" href="<?= url('/reparticao') ?>">
                <ion-icon name="briefcase"></ion-icon>
                <span>Repartições</span>
            </a>

        </div>

    <?php endif; ?>
    <?php if(auth()->access_level != "func"):?>
        <span class="menu-label">Armazém</span>
        <div class="menu-itens">
           
            <a  class="prod-link" href="<?= url('/produto') ?>">
                <ion-icon name="basket"></ion-icon>
                <span>Produtos</span>
            </a>
            <a class="req-link" href="<?= url("requisicao") ?>">
            <ion-icon name="information-circle"></ion-icon>
                <span>Requisições</span>
            </a>
          
        </div>
        <?php endif;?>
        <span class="menu-label">Usuário</span>
        <div class="menu-itens">
            <a class="cart-link" href="<?= url("requisitar") ?>">
                <ion-icon name="send"></ion-icon>
                <span>Requisitar</span>
            </a>
            <a class="my-link" href="<?= url("pedidos") ?>">
                <ion-icon name="cart"></ion-icon>
                <span>Minhas Requisições</span>
            </a>
        </div>
    </div>
</aside>
<main class="content">
<?= $v->section('content') ?>
</main>


    <?= $v->section('modal') ?>
<script module="" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<script src="<?= url("public/js/jquery.js") ?>"></script>
<script src="<?= url("public/datatables/datatables.min.js") ?>"></script>
<script src="<?= url("public/js/form.js") ?>"></script>
<script src="<?= url("public/js/main.js") ?>"></script>

<?= $v->section('scripts') ?>
</body>
</html>