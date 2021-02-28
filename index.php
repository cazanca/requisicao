<?php
ob_start();

require_once __DIR__ . "/vendor/autoload.php";



new \Source\Core\Session();
$route = new \CoffeeCode\Router\Router(url(), ":");

$route->namespace("Source\App");

$route->get('/home', "DashboardController:index");
$route->get('/', "Home:index");
$route->post('/', "Home:index");
$route->get('/logout', "Home:logout");
$route->get('/profile', "Home:profile");
$route->post('/profile', "Home:profile");
$route->post('/password', "Home:password");

/**
 * Usuarios
 */
$route->get('/usuario/{id}', "UserController:edit");
$route->post('/usuario/{id}', "UserController:edit");
$route->get('/usuario', "UserController:index");
$route->post('/usuario', "UserController:index");
$route->get('/usuario/fetch', "UserController:fetch");
$route->get('/usuario/{id}/remove', "UserController:delete");


/**
 * Categorias 
 */
$route->get('/categoria', "CategoriasController:index");
$route->post('/categoria', "CategoriasController:save");
$route->get('/categoria/{id}', "CategoriasController:edit");
$route->post('/categoria/{id}', "CategoriasController:edit");
$route->get('/categoria/{id}/delete', "CategoriasController:delete");

/**
 * Repartições
 */
$route->get('/reparticao', "DepartamentoController:index");
$route->post('/reparticao', "DepartamentoController:save");
$route->get('/reparticao/{id}', "DepartamentoController:edit");
$route->post('/reparticao/{id}', "DepartamentoController:edit");
$route->get('/reparticao/{id}/delete', "DepartamentoController:delete");

/**
 * Produtos
 */
$route->get('/produto/{id}', "ProdutoController:edit");
$route->post('/produto/{id}', "ProdutoController:edit");
$route->get('/produto', "ProdutoController:index");
$route->post('/produto/create', "ProdutoController:create");
$route->get('/produto/fetch', "ProdutoController:fetch");
$route->get('/produto/{id}/remove', "ProdutoController:delete");

/**
 * Requisição
 */
$route->get('/requisicao', "RequisicaoController:index");
$route->get('/requisicao/{id}', "RequisicaoController:approve");
$route->post('/requisicao/{id}', "RequisicaoController:down");
$route->post('/requisicao', "RequisicaoController:store");
$route->get('/requisitar', "RequisicaoController:create");
$route->get('/requisicao/{id}/remove', "RequisicaoController:delete");
$route->get('/pedidos', "RequisicaoController:my");

/*
 * ERROR
 */

$route->namespace("Source\App")->group("error");
$route->get("/{errcode}", "Home:error");

$route->dispatch();


/*
 * Error
 */

if ($route->error()){
    $route->redirect("/error/{$route->error()}");
}

ob_end_flush();
