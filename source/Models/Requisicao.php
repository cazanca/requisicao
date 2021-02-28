<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\User;
use Source\Models\ItensPedido;

class Requisicao extends Model
{
    public function __construct()
    {
        parent::__construct("pedidos", ["id"], ["user_id"]);
    }

    public function bootstrap($user)
    {
        $this->user_id = $user;
    }

    public function save(): bool
    {
        if(!$this->required()){
            $this->message->warning("O campo usuario é obrigatório");
            return false;
        }

        if (!empty($this->id)){
            $reqId = $this->id;

            $this->update($this->safe(), "id = :id", "id={$reqId}");
            if ($this->fail()){
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        if (empty($this->id)){
          
            $reqId = $this->create($this->safe());
            if ($this->fail()){
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($reqId))->data();
        return true;
    }

    public function user(): ?User
    {
        if (!empty($this->user_id)) {
            return (new User())->findById($this->user_id);
        }

        return null;
    }

}