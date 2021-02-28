<?php


namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Departamento;
use Source\Models\User;
use Source\Models\Requisicao;
use Source\Models\ItensPedido;

class DashboardController extends Controller
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

    public function index()
    {
        $departamentos = (new Departamento())->find()->order("id DESC")->limit(10)->fetch(true);
        $usuarios = (new User())->find()->order("id DESC")->limit(10)->fetch(true);
        // SELECT product_id, SUM(qty) AS Total FROM requests GROUP BY product_id ORDER BY SUM(qty) DESC LIMIT 7
        $requests = (new ItensPedido())->groupBy("produto_id", null, " produto_id, SUM(quantidade) AS total")->order("SUM(quantidade) DESC")->limit(7)->fetch(true);

        $confirmada = db()->query("SELECT count(id) as total FROM pedidos WHERE status ='confirmada'")->fetch()->total;
        $pendente = db()->query("SELECT count(id) as total FROM pedidos WHERE status ='pendente'")->fetch()->total;
        
        $status = array(
            array("label" => "Confirmada", "y" => $confirmada),
            array("label" => "Pendente", "y" => $pendente)
        );

        $data = array();
        foreach($requests as $request){
            $data[] = array("y" => $request->total, "label" => $request->produto()->name);
        }
        echo $this->view->render('dashboard/index', [
            "title" => "Home",
            "departamentos" => $departamentos,
            "usuarios" => $usuarios,
            "data" => $data,
            "status" => $status
        ]);
    }
}