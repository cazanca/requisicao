<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Models\Requisicao;

class User extends Model
{
    public function __construct()
    {
        parent::__construct("usuario", ["id"], ["first_name", "last_name", "email","access_level", "password", "departamento"]);
    }

    public function bootstrap(
        string $firstName,
        string $lastName,
        string $email,
        string $accessLevel,
        string $departamento,
        string $password
    ): User
    {
        $this->first_name = $firstName;
        $this->last_name = $lastName;
        $this->email = $email;
        $this->access_level = $accessLevel;
        $this->departamento = $departamento;
        $this->password = $password;

        return $this;
    }
    /**
     * @param int $id
     * @param string $columns
     * @return User|null
     */
   /* public function findById(int $id, string $columns = "*"): ?User
    {
        $find = $this->find("id = :id", "id={$id}", $columns);
        return $find->fetch();
    }
*/
    /**
     * @param string $email
     * @param string $columns
     * @return User|null
     */
    public function findByEmail(string $email, string $columns = "*"): ?User
    {
        $find = $this->find("email = :email", "email={$email}", $columns);
        return $find->fetch();
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->required()){
            $this->message->warning("Os campos nome, apelido, email, senha e departamento são obrigatórios");
            return false;
        }

        if (!is_email($this->email)){
            $this->message->warning("O e-mail informado não tem um formato válido");
            return  false;
        }

        if (!is_passwd($this->password)){
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;
            $this->message->warning("A senha deve ter entre {$min} e {$max} caracteres");
            return false;
        }else{
            $this->password = passwd($this->password);
        }

        if (!empty($this->id)){
            $userId = $this->id;
            
            if ($this->find("email = :email AND id != :id", "email={$this->email}&id={$userId}", "id")->fetch()){
                $this->message->warning("O e-mail informado já está cadastrado");
                return false;
            }

            $this->update($this->safe(), "id = :id", "id={$userId}");
            if ($this->fail()){
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        if (empty($this->id)){
            if ($this->findByEmail($this->email, "id")){
                $this->message->warning("O e-mail informado já esta cadastrado");
                return false;
            }

            $userId = $this->create($this->safe());
            if ($this->fail()){
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->findById($userId))->data();
        return true;
    }

    public function up(array $data = []
    ): bool
    {
        if (empty($this->email) || empty($this->first_name) || empty($this->last_name)){
            $this->message->warning("Os campos nome, apelido e email são obrigatórios");
            return false;
        }

        if (!is_email($this->email)){
            $this->message->warning("O e-mail informado não tem um formato válido");
            return  false;
        }

        if (!empty($this->id)){
            $userId = $this->id;
            if (empty($data)) {
                // profile data
                $data = array("first_name" => $this->first_name, "last_name" => $this->last_name, "email" => $this->email);
            }
            if ($this->find("email = :email AND id != :id", "email={$this->email}&id={$userId}", "id")->fetch()){
                $this->message->warning("O e-mail informado já está cadastrado");
                return false;
            }

            $this->update($data, "id = :id", "id={$userId}");
            if ($this->fail()){
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }

            $this->data = ($this->findById($userId))->data();
            return true;
        }

        $this->message->error("Erro ao atualizar, verifique os dados");
        return false;
    }

    /**
     * @return $this|bool|null
     */
    /*public function destroy()
    {
        if (!empty($this->id)){
            $this->delete("id = :id", "id={$this->id}");
        }

        if ($this->fail()){
            $this->message->error("Não foi possível remover o usuário");
            return false;
        }

        $this->data = null;
        return $this;
    }*/

    public function departamento()
    {
        if ($this->departamento) {
            return (new \Source\Models\Departamento())->findById($this->departamento);
        }

        return null;
    }


}