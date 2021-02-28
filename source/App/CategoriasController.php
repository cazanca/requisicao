<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Categoria;

class CategoriasController extends Controller
{
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
        if (!Auth::user()) {
            \redirect('/logout');
        }

        if (Auth::user()->access_level != "admin") {
            \redirect('/erro/negado');
        }
    }

    public function index(?array $dados)
    {
        $categoria = new Categoria();
        $total = db()->query("SELECT count(id) as total FROM categoria")->fetch()->total;
        $getPage = \filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);

        $pager = new \Source\Support\Pager("?page=");
        $pager->pager($total, 10, $getPage, 3);
        $categorias = $categoria->find()->order("id DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
        echo $this->view->render('categoria/index', [
            "title" => "Categorias",
            "categorias" => $categorias,
            "pager" => $pager->render()
        ]);
    }

    public function save(array $data)
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


        $categoria = new Categoria();
        $categoria->bootstrap($data["name"]);
        if (!$categoria->save()) {
            $json["message"] = $categoria->message()->render();
            echo json_encode($json);
            return;
            
        }

        $json["message"] = $this->message->success("Categoria cadastrada com sucesso.")->render();
        $json["success"] = true;
        echo json_encode($json);
        return;
    }

    public function delete(array $data)
    {
        $categoriaId = filter_var($data['id'], FILTER_VALIDATE_INT);
        if (!$categoriaId) {
           $this->message->error("O id informado não é válido.")->flash();
            \redirect('/usuario');
            return;
        }

        $categoria = (new Categoria())->findById($categoriaId);
        if (!$categoria) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/categoria');
            return;
        }

        $delete = $categoria->destroy();
        if ($delete) {
            $this->message->success("Categoria removida com sucesso")->flash();
            \redirect('/categoria');
        }else {
            $this->message->warning("Erro ao remover, verifique os dados")->flash();
            \redirect('/categoria');
        }
    }

    public function edit(?array $data)
    {
        // Update categoria
        if (!empty($data['action']) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $categoriaUpdate = (new Categoria())->findById($data["id"]);

            if (!$categoriaUpdate) {
                $this->message->error("ERRO: Voce tentou atualizar um categoria que não existe")->flash();
                $json["redirect"] = url('/categoria');
                echo json_encode($json);
                return;
            }

            $categoriaUpdate->name = $data['name'];
            
            if (!$categoriaUpdate->save()) {
                $json["message"] = $categoriaUpdate->message()->render();
                echo \json_encode($json);
                return;
            }

            $this->message->success("categoria atualizada com sucesso!")->flash();
            $json["redirect"] = url('/categoria');
            echo \json_encode($json);
            return;
        }

        $id = base64_decode($data['id']);
        $id = (int) $id;

        $categoria = (new Categoria())->findById($id);

        if (!$categoria) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/categoria');
            
        }else {
            echo $this->view->render('categoria/edit', [
                "title" => "Actualizar dados",
                "categoria" => $categoria,
            ]);
        }
    
    }

  
}

