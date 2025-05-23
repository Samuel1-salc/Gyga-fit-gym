<?php

class UserAluno
{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con  = new Database();
        $this->link = $this->con->getConexao();
    }

    public function cadastrarAluno($Username, $Email, $Cpf, $unidade, $plano, $data_inicio, $data_termino, $phone, $typeUser)
    {
        $stmt = $this->link->prepare("
            INSERT INTO aluno 
              (username, email, cpf, unidade, plano, data_inicio, data_termino, phone, typeUser)
            VALUES 
              (:username, :email, :cpf, :unidade, :plano, :data_inicio, :data_termino, :phone, :typeUser)
        ");
        $stmt->bindParam(':username',     $Username);
        $stmt->bindParam(':email',        $Email);
        $stmt->bindParam(':cpf',          $Cpf);
        $stmt->bindParam(':unidade',      $unidade);
        $stmt->bindParam(':plano',        $plano);
        $stmt->bindParam(':data_inicio',  $data_inicio);
        $stmt->bindParam(':data_termino', $data_termino);
        $stmt->bindParam(':phone',        $phone);
        $stmt->bindParam(':typeUser',     $typeUser);

        return $stmt->execute();
    }

    /**
     * @param int          $id
     * @param string       $username
     * @param string       $email
     * @param string       $cpf
     * @param string       $unidade
     * @param int|string   $plano
     * @param string       $phone
     * @return bool
     */
    public function editarAluno($id, $username, $email, $cpf, $unidade, $plano, $phone)
    {
        try {
            $stmt = $this->link->prepare("
                UPDATE aluno SET
                  username     = :username,
                  email        = :email,
                  cpf          = :cpf,
                  unidade      = :unidade,
                  plano        = :plano,
                  phone        = :phone
                WHERE id = :id
            ");
            $stmt->bindParam(':id',        $id,       PDO::PARAM_INT);
            $stmt->bindParam(':username',  $username);
            $stmt->bindParam(':email',     $email);
            $stmt->bindParam(':cpf',       $cpf);
            $stmt->bindParam(':unidade',   $unidade);
            $stmt->bindParam(':plano',     $plano);
            $stmt->bindParam(':phone',     $phone);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao editar aluno: " . $e->getMessage());
            return false;
        }
    }

    public function dataInicio()
    {
        $data = new DateTime();
        return $data->format('Y-m-d H:i:s');
    }

    public function dataTermino($data_inicio, $plano)
    {
        $date = new DateTime($data_inicio);
        switch ((int)$plano) {
            case 1:
                $date->modify('+1 month');
                break;
            case 2:
                $date->modify('+6 months');
                break;
            case 3:
                $date->modify('+12 months');
                break;
        }
        return $date->format('Y-m-d H:i:s');
    }
}
