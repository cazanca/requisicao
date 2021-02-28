<?php

namespace Source\Models;

use Source\Core\Model;

class Departamento extends Model
{
    public function __construct()
    {
        parent::__construct("departamento",["id"], ["name"]);
    }

    public function bootstrap(string $name)
    {
        $this->name = $name;
    }

    public function save(): bool
    {
        if(!$this->required()){
            $this->message->warning("O campo nome é obrigatório");
            return false;
        }

        if (!empty($this->id)){
            $depId = $this->id;

            $this->update($this->safe(), "id = :id", "id={$depId}");
            if ($this->fail()){
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        if (empty($this->id)){
          
            $depId = $this->create($this->safe());
            if ($this->fail()){
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($depId))->data();
        return true;
    }
}