<?php

namespace Source\Models;

use Source\Core\Model;

class Categoria extends Model
{
    public function __construct()
    {
        parent::__construct("categoria", ["id"], ["name"]);
    }

    public function bootstrap($name)
    {
        $this->name = $name;
    }

    public function save(): bool
    {
        if(!$this->required()){
            $this->message->warning("O campo nome é obrigatórios");
            return false;
        }

        if (!empty($this->id)){
            $catId = $this->id;

            $this->update($this->safe(), "id = :id", "id={$catId}");
            if ($this->fail()){
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        if (empty($this->id)){
          
            $catId = $this->create($this->safe());
            if ($this->fail()){
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($catId))->data();
        return true;
    }

}