<?php

namespace Source\App;


use Source\Core\Controller;
use Source\Models\User;
use Source\Models\Auth;
use Source\Models\Departamento;

class UserController extends Controller
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

    public function index(?array $data)
    {
        if (!empty($data)) {
            $json['success'] = array('success' => false, 'message' => array());

            /*if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Por favor use o formulário")->render();
                echo json_encode($json);
                return;
            }*/

            if (in_array("", $data, true)) {
                $json['message'] = $this->message->warning("Por favor preencha todos os campos")->render();
                echo json_encode($json);
                return;
            }
    
            if (!is_email($data["email"])) {
                $json['message'] = $this->message->warning("O e-mail informado não é válido")->render();
                echo json_encode($json);
                return;
            }
    
            $user = (new User())->findByEmail($data["email"]);
            if ($user) {
                $json['message'] = $this->message->error("O e-mail informado já esta cadastrado")->render();
                echo json_encode($json);
                return;
            }
    
            $user = new User();
            $user->bootstrap($data["first_name"], $data["last_name"], $data["email"], $data["access_level"], $data["departamento"], $data["password"]);
            if (!$user->save()) {
                $json["message"] = $user->message()->render();
                echo json_encode($json);
                return;
            }
            $json["message"] = $this->message->success("Usuário cadastrado com sucesso.")->render();
        $json["success"] = true;
        echo json_encode($json);
            return;
        }

        $departamentos = (new Departamento())->find()->fetch(true);
        echo $this->view->render("user/list", [
            "title" => "Lista de usuários",
            "departamentos" => $departamentos
        ]);
    }

    public function fetch()
    {
        $users = (new User())->find()->fetch(true);

        $output = array('data' => array());

        foreach($users as $user){
            $userId = $user->id;
            $enc = \base64_encode($userId);
            $edit = '<a href='.url("usuario/{$enc}").' class="edit" style="cursor:pointer" onclick="editUser('.$userId.')"><ion-icon name="create-outline"></ion-icon></a> '
            /*$del =*/ .' <a class="remove" style="cursor:pointer" type="button" onclick="removeUser('.$userId.')"><ion-icon name="trash-outline"></ion-icon></a>';
          
            $departamento = "<span class='btn-label btn-warning'>não definido</span>";

            if ($user->departamento) {
                $departamento = $user->departamento()->name;
            }
            
            $output['data'][] = array(
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->access_level,
                $departamento,
                \date_fmt($user->created_at),
                $edit
            );
        }

        echo json_encode($output);

    }

     public function delete(array $data)
    {
        $userId = filter_var($data['id'], FILTER_VALIDATE_INT);
        if (!$userId) {
           $this->message->error("O id informado não é válido.")->flash();
            \redirect('/usuario');
            return;
        }

        $user = (new User())->findById($userId);
        if (!$user) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/usuario');
            return;
        }

        $delete = $user->destroy();
        if ($delete) {
            $this->message->success("Usuário removido com sucesso")->flash();
            \redirect('/usuario');
        }else {
            $this->message->warning("Erro ao remover, verifique os dados")->flash();
            \redirect('/usuario');
        }
    }

    public function edit(?array $data)
    {
       if (!empty($data['action']) && $data['action'] == "update") {
            $json['success'] = array('success' => false, 'message' => array());
           $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

           $userUpdate = (new User())->findById($data["id"]);

            if (!$userUpdate) {
                $this->message->error("ERRO: Voce tentou atualizar um usuario que não existe")->flash();
                $json['redirect'] = url('usuario');
                echo json_encode($json);
                return;
            }

            $userId = $userUpdate->id;
            $dados = array(
               "first_name" => $data['first_name'],
               "last_name" => $data['last_name'],
               "email" => $data['email'],
               "access_level" => $data['access_level'],
               "departamento" => $data['departamento']
            ); 

            if (!empty($data["password"])) {
                $dados["password"] = passwd($data["password"]);
            }
            
            if (!$userUpdate->up($dados, "id = :id", "id={$userId}")) {
                $json["message"] = $userUpdate->message()->render();
                echo \json_encode($json);
                return;
            }else {
                # code...
            }
            
           $json["message"] = $this->message->success("Dados actualizado com sucesso!")->render();
           echo \json_encode($json);
           return;
       }

        $id = base64_decode($data['id']);
        $id = (int) $id;
       
        $user = (new User())->findById($id);

        if (!$user) {
            $this->message->error("O id informado não é válido.")->flash();
            \redirect('/usuario');
            
        }else {
            $departamentos = (new Departamento())->find()->fetch(true);
            echo $this->view->render('user/edit', [
                "title" => "Actualizar dados",
                "user" => $user,
                "departamentos" => $departamentos
            ]);
        }

       
    }
}