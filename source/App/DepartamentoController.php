<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Departamento;

class DepartamentoController extends Controller
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
        $departamento = new Departamento();
        $total = db()->query("SELECT count(id) as total FROM categoria")->fetch()->total;
        $getPage = \filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);

        $pager = new \Source\Support\Pager("?page=");
        $pager->pager($total, 10, $getPage, 3);

        $departamentos = $departamento->find()->order("id DESC")->limit($pager->limit())->offset($pager->offset())->fetch(true);
        echo $this->view->render('departamento/index', [
            "title" => "repartições",
            "reparticoes" => $departamentos,
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


        $departamento = new Departamento();
        $departamento->bootstrap($data["name"]);
        if (!$departamento->save()) {
            $json["message"] = $departamento->message()->render();
            echo json_encode($json);
            return;
            
        }

        $json["message"] = $this->message->success("Repartição cadastrada com sucesso.")->render();
        $json["success"] = true;
        echo json_encode($json);
        return;
    }

    public function delete(array $data)
    {
        $reparticaoId = filter_var($data['id'], FILTER_VALIDATE_INT);
        if (!$reparticaoId) {
           $this->message->error("O id informado não é válido.")->flash();
            \redirect('/usuario');
            return;
        }

        $reparticao = (new Departamento())->findById($reparticaoId);
        if (!$reparticao) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/reparticao');
            return;
        }

        $delete = $reparticao->destroy();
        if ($delete) {
            $this->message->success("repartição removida com sucesso")->flash();
            \redirect('/reparticao');
        }else {
            $this->message->warning("Erro ao remover, verifique os dados")->flash();
            \redirect('/reparticao');
        }
    }

    public function edit(?array $data)
    {
        // Update departamento
        if (!empty($data['action']) && $data["action"] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $departamentoUpdate = (new Departamento())->findById($data["id"]);

            if (!$departamentoUpdate) {
                $this->message->error("ERRO: Voce tentou atualizar uma repartição que não existe")->flash();
                $json["redirect"] = url('/reparticao');
                echo json_encode($json);
                return;
            }

            $departamentoUpdate->name = $data['name'];
            
            if (!$departamentoUpdate->save()) {
                $json["message"] = $departamentoUpdate->message()->render();
                echo \json_encode($json);
                return;
            }

            $this->message->success("Repartição atualizada com sucesso!")->flash();
            $json["redirect"] = url('/reparticao');
            echo \json_encode($json);
            return;
        }

        $id = base64_decode($data['id']);
        $id = (int) $id;

        $departamento = (new Departamento())->findById($id);

        if (!$departamento) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/reparticao');
            
        }else {
            echo $this->view->render('departamento/edit', [
                "title" => "Actualizar dados",
                "reparticao" => $departamento,
            ]);
        }
    
    }

}

