<?php

class UserAluno
{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    // Cadastra um novo usuário
    // Recebe os dados do formulário e insere no banco de dados
    public function cadastrarAluno($Username, $Email,  $Cpf,$unidade,$plano,$data_inicio,$data_termino, $phone, $typeUser)
    {
        
        
    
    
        $stmt = $this->link->prepare("INSERT INTO aluno (username, email, cpf,unidade,plano,data_inicio,data_termino, phone, typeUser) VALUES (:username, :email,:cpf,:unidade,:plano,:data_inicio,:data_termino,:phone,:typeUser)");
        $stmt->bindParam(':username', $Username);
        $stmt->bindParam(':email', $Email);
        $stmt->bindParam(':cpf', $Cpf);
        $stmt->bindParam(':unidade', $unidade);
        $stmt->bindParam(':plano', $plano);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_termino', $data_termino);
        $stmt->bindParam(':cpf', $Cpf);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':typeUser', $typeUser);

        // Executa a query e verifica se foi bem-sucedida


        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }
    function dataInicio(){
        $data = new DateTime();
        $data_formatada = $data->format('Y-m-d H:i:s');
        return $data_formatada;
    }
    function dataTermino($data_incio,$plano){
        $data_termino = '';
        if($plano == 1){
            $data_termino = date('Y-m-d H:i:s', strtotime($data_incio . ' + 1 month'));
        }else if($plano == 2){
            $data_termino = date('Y-m-d H:i:s', strtotime($data_incio . ' + 6 month'));
        }else if($plano == 3){
            $data_termino = date('Y-m-d H:i:s', strtotime($data_incio . ' + 12 month'));
        }
        return $data_termino;

    }

}
