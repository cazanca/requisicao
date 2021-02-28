<?php


namespace Source\App;


use Source\Core\Controller;
use Source\Models\Auth;

class Home extends Controller
{
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
    }

    public function registerSeed()
    {
        $user = new \Source\Models\User();
        $user->bootstrap("Admin", "Example", "admin@example.com", "admin", "1", "jfcazanca");
        $user->save();
    }

    public function index(?array $data)
    {
        if (!empty($data)) {

            $json['success'] = array('success' => false, 'message' => array());

            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Por favor use o formulário")->render();
                echo json_encode($json);
                return;
            }
    
            #Limit de requisição
            if (request_limit("weblogin", 5, 3*60)) {
                $json['message'] = $this->message->warning("Você já efetuou 3 tentativas, por favor aguarde por 3 min para tentar novamente.")->render();
                echo \json_encode($json);
                return;
            }
    
            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu e-mail e senha para entrar")->render();
                echo \json_encode($json);
                return;
            }
    
            $auth = new Auth();
            $login = $auth->login($data["email"], $data["password"]);
            if ($login) {
                $json["redirect"] = url("/profile");
            }else{
                $json['message'] = $auth->message()->after("!")->render();
            }
    
            echo json_encode($json);
            return;
        }
        echo $this->view->render("auth-login",[
            "title" => "Página de login"
        ]);
    }

    public function logout()
    {
        Auth::logout();
        \redirect(url('/'));
    }

    public function forgot()
    {
        echo "forgot";
    }

    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode']){
            case 'problemas':
                $error->code = "OPS";
                $error->title = "Estamos enfrentando problemas";
                $error->message = "Parece que nosso serviço não esta disponível no momento. Já estamos vendo isso mas caso precise, envie um e-mail :)";
                $error->linkTitle = "Enviar";
                $error->link = "mailto:" .CONF_MAIL_SUPPORT;
                break;
            case 'manutencao':
                $error->code = "OPS";
                $error->title = "Desculpa. Estamos em manutenção";
                $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para você controlar melhor as suas contas";
                $error->linkTitle = null;
                $error->link = null;
                break;
            default:
                $error->code = $data['errcode'];
                $error->title = "Ops. Conteúdo indisponível :/";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponível no momento ou foi removido";
                $error->linkTitle = "Continue navegando!";
                $error->link = url_back();
                break;
        }

        echo $this->view->render("error", [
            "title" => "Error",
            "error" => $error
        ]);
    }

    public function password(array $data)
    {
        $user = Auth::user();
        if (!empty($data)) {
            $json['success'] = array('success' => false, 'message' => array());
            
            if (!password_verify($data['password'], $user->password)) {
                $json['message'] = $this->message->error("A senha atual não confere")->render();
                echo json_encode($json);
                return;
            }

            if (!is_passwd($data['newpassword'])) {
                $json['message'] = $this->message->warning("A senha informada não é válida!")->render();
                echo json_encode($json);
                return;
            }

            if ($data['newpassword'] !== $data['confpassword']) {
                $json['message'] = $this->message->warning("As senhas tem de ser iguais")->render();
                echo json_encode($json);
                return;
            }

            if (!$user->update(["password" => passwd($data['newpassword'])], "id = :id", "id={$user->id}")) {
                $json["message"] = $this->message->error("Erro ao atualizar, verifique os dados")->render();
                 return false;
             }
             $json["message"] = $this->message->success("Senha atualizada")->render();
             echo json_encode($json);
             return;
        }
    }

    public function profile(?array $data)
    {
        if (!Auth::user()) {
            Auth::logout();
            \redirect(url('/'));
        }

        if (!empty($data)) {
            $json['success'] = array('success' => false, 'message' => array());

            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Por favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            $user = Auth::user();
            $dados = array(
                "first_name" => $data['first_name'],
                "last_name" => $data['last_name'],
                "email" => $data['email']
            );

            if (!$user->up($dados)) {
               $json["message"] = $user->message()->render();
               echo json_encode($json);
               return;
            }
            $json["message"] = $this->message->success("Dados atualizados")->render();
            echo json_encode($json);
            return;
        }
        echo $this->view->render("user/profile", [
            "title" => Auth::user()->last_name . " perfil",
            "user" => Auth::user()
        ]);
    }
}