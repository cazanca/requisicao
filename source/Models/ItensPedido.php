<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\Produto;
use Source\Models\Requisicao;

class ItensPedido extends Model
{

    public function __construct()
    {
        parent::__construct("itens_pedido", ["id"], ["produto_id", "pedido_id", "quantidade"]);
    }

  public function produto(): ?Produto
  {
      if (!empty($this->produto_id)) {
          return (new Produto())->findById($this->produto_id);
      }

      return null;
  }


}