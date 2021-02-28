<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Requisicao;
use Source\Models\Produto;
use Source\Models\ItensPedido;
use Source\Core\Connect;

class RequisicaoController extends Controller
{
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
        if (!Auth::user()) {
            \redirect('/logout');
        }
    }

    public function index()
    {
        if (Auth::user()->access_level == "func") {
            \redirect('/erro/negado');
        }

        $requisicao = new Requisicao();
        $total = db()->query("SELECT count(id) as total FROM pedidos")->fetch()->total;
        $getPage = \filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);

        $pager = new \Source\Support\Pager("?page=");
        $pager->pager($total, 10, $getPage, 3);
        $requisicao = $requisicao->find()->order("id DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);

        echo $this->view->render("requisicao/index", [
            "title" => "Requisições",
            "requisicao" => $requisicao,
            "pager" => $pager->render()
        ]);
    }

    public function create()
    {
        $produtos = (new Produto())->find("qty > 0")->fetch(true);

        echo $this->view->render('requisicao/create', [
            "title" => "Requisitar",
            "produtos" => $produtos
        ]);
    }

    public function store(array $data)
    {   
        
        $produtos = $data['productName'];
        $quantidades = $data['quantity'];
        
        $userId = Auth::user()->id;
        $pdo = Connect::getInstance();
       try {
        $pdo->beginTransaction();
            $pdo->query("INSERT INTO pedidos (`user_id`) VALUES ($userId)");
            $pedidoId = $pdo->lastInsertId();

            for ($i=0; $i < count($produtos) ; $i++) { 
                $pdo->query("INSERT INTO itens_pedido (`pedido_id`, `quantidade`, `produto_id`) VALUES ($pedidoId, $quantidades[$i], $produtos[$i])");
            }

        $pdo->commit();
        $this->message->success('Requisição submetida com sucesso')->flash();
        \redirect('pedidos');
       } catch (\Throwable $th) {
           $pdo->rollBack();
           
           $this->message->error('Erro ao submeter a requisição: ' . $th->getMessage())->flash();
        \redirect('pedidos');
        
       }
    }

    public function approve(array $data)
    {
        if (Auth::user()->access_level == "func") {
            \redirect('/erro/negado');
        }
        $id = base64_decode($data['id']);
        $id = (int) $id;
        $pedido = (new Requisicao())->findById($id);
        if (is_numeric($id)) {
            $itens = (new ItensPedido())->find("pedido_id = :pedido_id", "pedido_id={$id}")->fetch(true);

            if (empty($itens)) {
                $this->message->error("Nenhum pedido encontrado")->flash();
                \redirect('/requisicao');
                return;
            }

            echo $this->view->render("requisicao/approve", [
                "title" => "Aprovar a requisição",
                "itens" => $itens,
                "id" => $id,
                "pedido" => $pedido
            ]);
        }else {
            $this->message->error("O id informado não é válido")->flash();
                \redirect('/requisicao');
                return;
        }
       
    }

    public function down(array $data)
    {
        if (Auth::user()->access_level == "func") {
            \redirect('/erro/negado');
        }
        $pedidoId = $data['id'];
        $pdo = Connect::getInstance();
        $itens = (new ItensPedido())->find("pedido_id = :pedido_id", "pedido_id={$pedidoId}")->fetch(true);
        if (empty($itens)) {
            $this->message->error("Algo deu errao, por favor verifique os dados!")->flash();
            redirect('/requisicao');
            return;
        }
        
       try {
        $pdo->beginTransaction();
            //$pdo->query("UPDATE TABLE pedidos  SET status = 'confirmada' WHERE user_id = $pedidoId");
            //$pedidoId = $pdo->lastInsertId();

            /*for ($i=0; $i < count($produtos) ; $i++) { 
                $pdo->query("INSERT INTO itens_pedido (`pedido_id`, `quantidade`, `produto_id`) VALUES ($pedidoId, $quantidades[$i], $produtos[$i])");
            }*/

            foreach($itens as $item){
                $product = (new Produto())->findById($item->produto_id);
                if (!$product) {
                    $this->message->error("O id informado não é válido.")->flash();
                    \redirect('/requisicao');
                    return;
                }

                if ($item->quantidade > $product->qty) {
                    $this->message->error("Quantidade indisponível de $product->name.")->flash();
                    \redirect('/requisicao');
                    return;
                }
        
                $product->qty = $product->qty - $item->quantidade;

                $pdo->query("UPDATE pedidos  SET `status` = 'confirmada' WHERE id = {$pedidoId}");
                $pdo->query("UPDATE produto SET qty = {$product->qty} WHERE id = {$product->id}");
                $pdo->query("UPDATE itens_pedido SET `status` = 'confirmada' WHERE id = {$item->id}");
            }

        $pdo->commit();
        $this->message->success('Requisição aprovada com sucesso')->flash();
        \redirect('requisicao');
       } catch (\Throwable $th) {
           $pdo->rollBack();
           
           $this->message->error('Erro ao aprovar a requisição: ' . $th->getMessage())->flash();
        \redirect('requisicao');
        
       }
    }

    public function my()
    {   
        $userId = Auth::user()->id;
        $itens = (new ItensPedido())->find()->fetch(true);
        $pedidos = (new Requisicao())->find("user_id = :user_id", "user_id={$userId}")->fetch(true);
        $data = [];
        if (!empty($pedidos)) {
            foreach($pedidos as $pedido){
                foreach($itens as $item){
                    if ($pedido->id == $item->pedido_id) {
                        $data[] = $item;
                    }
                }
            }
        }
       
       echo $this->view->render('requisicao/my', [
           "title" => "Minhas requisições",
           "produtos" => $data
       ]);
    }

    public function delete(array $data)
    {
        if (Auth::user()->access_level == "func") {
            \redirect('/erro/negado');
        }
        
        $pedidoId = filter_var($data['id'], FILTER_VALIDATE_INT);
        if (!$pedidoId) {
           $this->message->error("O id informado não é válido.")->flash();
            \redirect('/requisicao');
            return;
        }

        $requisicao = (new Requisicao())->findById($pedidoId);
        if (!$requisicao) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/requisicao');
            return;
        }

        $delete = $requisicao->destroy();
        if ($delete) {
            $this->message->success("Requisição removida com sucesso")->flash();
            \redirect('/requisicao');
        }else {
            $this->message->warning("Erro ao remover, verifique os dados")->flash();
            \redirect('/requisicao');
        }
    }

}