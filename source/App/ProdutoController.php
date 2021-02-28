<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Produto;
use Source\Models\Categoria;

class ProdutoController extends Controller
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
        $categorias = (new \Source\Models\Categoria())->find()->fetch(true);
        echo $this->view->render('produto/index', [
            "title" => "Produtos",
            "categorias" => $categorias
        ]);
    }

    public function fetch()
    {
        $produtos = (new Produto())->find()->fetch(true);

        $output = array('data' => array());

        foreach($produtos as $produto){
            $produtoId = $produto->id;
            $enc = \base64_encode($produtoId);

            if (Auth::user()->access_level != 'func') {
                $edit ='<span class="action"><a href='.url("produto/{$enc}").' class="edit" style="cursor:pointer"><ion-icon name="create-outline"></ion-icon></a> '
                /*$del =*/ .' <a class="remove" style="cursor:pointer" type="button" onclick="removeProduto('.$produtoId.')"><ion-icon name="trash-outline"></ion-icon></a></span>';
            }else {
                $edit = "<span class='btn-label'>Sem acção</span>";
            }
           
          
            $actualizado = "<span class='btn-label btn-warning'>não actualizado</span>";
            $categoria = "<span class='btn-label btn-error'>não definida</span>";
            $descricao = "<span class='btn-label btn-primary'>não definida</span>";

            if ($produto->updated_at) {
               $actualizado = \date_fmt($produto->updated_at);
            }

            if ($produto->categoria()) {
               $categoria = $produto->categoria()->name;
            }

            if ($produto->descricao) {
                $descricao = $produto->descricao;
            }
            
            $output['data'][] = array(
                $produto->name,
                $produto->qty,
                $categoria,
                $descricao,
                $actualizado,
                $edit
            );
        }

        echo json_encode($output);

    }

    public function create(array $data)
    {
        $json['success'] = array('success' => false, 'message' => array());

        if (!csrf_verify($data)) {
            $json['message'] = $this->message->error("Por favor use o formulário")->render();
            echo json_encode($json);
            return;
        }

        if (in_array("", $data, true)) {
            $json['message'] = $this->message->warning("Por favor preencha todos os campos")->render();
            echo json_encode($json);
            return;
        }

        $produto = new Produto();
        $produto->bootstrap($data["name"], $data["qty"], $data["categoria_id"], $data['descricao']);
        if (!$produto->save()) {
            $json["message"] = $produto->message()->render();
            echo json_encode($json);
            return;
            
        }

        $json["message"] = $this->message->success("Produto cadastrado com sucesso.")->render();
        $json["success"] = true;
        echo json_encode($json);
        return;
    }

    public function edit(array $data)
    {
        // Update produto
        if (!empty($data['action']) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $produtoUpdate = (new Produto())->findById($data["id"]);

            if (!$produtoUpdate) {
                $this->message->error("ERRO: Voce tentou atualizar um produto que não existe")->flash();
                $json["redirect"] = url('/produto');
                echo json_encode($json);
                return;
            }

            $produtoUpdate->name = $data['name'];
            $produtoUpdate->qty = $data['qty'];
            $produtoUpdate->categoria_id = $data['categoria_id'];
            $produtoUpdate->descricao = $data['descricao'];
            
            if (!$produtoUpdate->save()) {
                $json["message"] = $produtoUpdate->message()->render();
                echo \json_encode($json);
                return;
            }

            $this->message->success("Produto atualizada com sucesso!")->flash();
            $json["redirect"] = url('/produto');
            echo \json_encode($json);
            return;
        }

        $id = base64_decode($data['id']);
        $id = (int) $id;

        $produto = (new Produto())->findById($id);

        if (!$produto) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/produto');
            
        }else {
            $categorias = (new \Source\Models\Categoria())->find()->fetch(true);
            echo $this->view->render('produto/edit', [
                "title" => "Actualizar dados",
                "produto" => $produto,
                "categorias" => $categorias
            ]);
        }
    }

    public function delete(array $data)
    {
        $produtoId = filter_var($data['id'], FILTER_VALIDATE_INT);
        if (!$produtoId) {
           $this->message->error("O id informado não é válido.")->flash();
            \redirect('/produto');
            return;
        }

        $produto = (new Produto())->findById($produtoId);
        if (!$produto) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/produto');
            return;
        }

        $delete = $produto->destroy();
        if ($delete) {
            $this->message->success("Produto removido com sucesso")->flash();
            \redirect('/produto');
        }else {
            $this->message->warning("Erro ao remover, verifique os dados")->flash();
            \redirect('/produto');
        }
    }

}