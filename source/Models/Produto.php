<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\Categoria;

class Produto extends Model
{
    public function __construct()
    {
        parent::__construct("produto", ["id"], ["name","qty", "categoria_id"]);
    }

    public function bootstrap(string $name, int $qty, int $categoria_id, string $descricao = null)
    {
        $this->name = $name;
        $this->qty = $qty;
        $this->categoria_id = $categoria_id;
        $this->descricao = $descricao;
    }

    public function save()
    {
        if(!$this->required()){
            $this->message->warning("Nome, quantidade e categoria são obrigatórios");
            return false;
        }


        if (!empty($this->id)){
            $productID = $this->id;

            $this->update($this->safe(), "id = :id", "id={$productID}");
            if ($this->fail()){
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        if (empty($this->id)){
          
            $productID = $this->create($this->safe());
            if ($this->fail()){
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($productID))->data();
        return true;
 
    }

    public function categoria()
    {
        if ($this->categoria_id) {
            return (new Categoria())->findById($this->categoria_id);
        }

        return null;
    }
}