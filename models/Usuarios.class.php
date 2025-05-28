<?php

/**
 * Classe responsável pelas operações relacionadas aos usuários (alunos e instrutores).
 * Permite buscar, cadastrar, validar e consultar dados de alunos e instrutores.
 */
class Users
{
    /**
     * @var Database $con Instância da conexão com o banco de dados.
     */
    private $con;

    /**
     * @var PDO $link Link da conexão PDO.
     */
    private $link;

    /**
     * Construtor da classe Users.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    /**
     * Retorna a data e hora atual formatada.
     *
     * @return string Data e hora atual no formato Y-m-d H:i:s.
     */
    public function dataAtual()
    {
        $data = new DateTime();
        $data_formatada = $data->format('Y-m-d H:i:s');
        return $data_formatada;
    }

    /**
     * Busca o nome do aluno pelo ID.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com o nome do aluno ou false em caso de erro.
     */
    public function getNomeAluno($id_aluno)
    {
        $stmt = $this->link->prepare("SELECT username FROM aluno WHERE id = :id_aluno");
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Busca dados de todos os alunos para o perfil de alunos.
     *
     * @return array Retorna um array com os dados dos alunos.
     */
    public function getDataAlunosForPerfilAlunos()
    {
        $stmt = $this->link->prepare("SELECT id,username, email, cpf, unidade, plano, data_termino, phone FROM aluno");
        $stmt->execute();
        $tabela =  $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $tabela;
    }

    /**
     * Busca dados de alunos pelo nome.
     *
     * @param string $nameSearch Nome (ou parte do nome) do aluno.
     * @return array Retorna um array com os dados dos alunos encontrados.
     */
    public function getDataAlunosByNome($nameSearch)
    {
        $stmt = $this->link->prepare("SELECT id,username, email, plano FROM aluno WHERE username LIKE :nameSearch");
        $nameSearch = "%$nameSearch%";
        $stmt->bindParam(':nameSearch', $nameSearch, PDO::PARAM_STR);
        $stmt->execute();
        return  $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    /**
     * Busca alunos vinculados a um instrutor.
     *
     * @param int $id_instrutor ID do instrutor.
     * @return array Retorna um array com os dados dos alunos.
     */
    public function getAlunosByInstrutor($id_instrutor)
    {
        $stmt = $this->link->prepare("SELECT nome_aluno, contato_aluno, data_solicitacao, processo FROM aluno_instrutor WHERE id_instrutor = :id_instrutor");
        $stmt->bindParam(':id_instrutor', $id_instrutor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    /**
     * Busca alunos vinculados a um instrutor (incluindo ID do aluno).
     *
     * @param int $id_instrutor ID do instrutor.
     * @return array Retorna um array com os dados dos alunos.
     */
    public function getAlunosINSTRUTOR($id_instrutor)
    {
        $stmt = $this->link->prepare("SELECT id_aluno, nome_aluno, contato_aluno, data_solicitacao, processo FROM aluno_instrutor WHERE id_instrutor = :id_instrutor");
        $stmt->bindParam(':id_instrutor', $id_instrutor);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    /**
     * Busca o nome do instrutor vinculado a um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com o nome do instrutor ou false em caso de erro.
     */
    public function getNomePersonalByAluno($id_aluno)
    {
        $stmt = $this->link->prepare("SELECT nome_instrutor  FROM aluno_instrutor WHERE id_Aluno = :id_aluno");
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Busca o ID do instrutor vinculado a um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com o ID do instrutor ou false em caso de erro.
     */
    public function getIPersonalByAluno($id_aluno)
    {
        $stmt = $this->link->prepare("SELECT id_instrutor  FROM aluno_instrutor WHERE id_Aluno = :id_aluno");
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Busca um aluno pelo ID.
     *
     * @param int $id ID do aluno.
     * @return array|false Retorna um array com os dados do aluno ou false em caso de erro.
     */
    public function getAlunoById($id)
    {
        $stmt = $this->link->prepare("SELECT * FROM aluno WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Busca um aluno pelo CPF.
     *
     * @param string $cpf CPF do aluno.
     * @return array|false Retorna um array com os dados do aluno ou false em caso de erro.
     */
    public function getDataAlunoByCpf($cpf)
    {
        $stmt = $this->link->prepare("SELECT * FROM aluno WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Busca um instrutor pelo CPF.
     *
     * @param string $cpf CPF do instrutor.
     * @return array|false Retorna um array com os dados do instrutor ou false em caso de erro.
     */
    public function getDataPersonalByCpf($cpf)
    {
        $stmt = $this->link->prepare("SELECT * FROM instrutor WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Verifica se o CPF já está cadastrado para o tipo de usuário.
     *
     * @param string $cpf CPF a ser verificado.
     * @param int $typeUser 1 para aluno, 2 para instrutor.
     * @return array|false Retorna um array se encontrado ou false caso contrário.
     */
    public function checkByCpf($cpf, $typeUser)
    {
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM aluno WHERE cpf = :cpf LIMIT 1");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM instrutor WHERE cpf = :cpf LIMIT 1");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Verifica se o email já está cadastrado para o tipo de usuário.
     *
     * @param string $email Email a ser verificado.
     * @param int $typeUser 1 para aluno, 2 para instrutor.
     * @return array|false Retorna um array se encontrado ou false caso contrário.
     */
    public function checkByEmail($email, $typeUser)
    {
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM aluno WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM instrutor WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Verifica se o telefone já está cadastrado para o tipo de usuário.
     *
     * @param string $phone Telefone a ser verificado.
     * @param int $typeUser 1 para aluno, 2 para instrutor.
     * @return array|false Retorna um array se encontrado ou false caso contrário.
     */
    public function checkByPhone($phone, $typeUser)
    {
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM aluno WHERE phone = :phone LIMIT 1");
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM instrutor WHERE phone = :phone LIMIT 1");
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Busca o primeiro login do usuário pelo ID.
     *
     * @param int $id ID do usuário.
     * @return array|false Retorna um array com os dados do formulário ou false em caso de erro.
     */
    public function firstLogin($id)
    {
        //formulario é a tabela no banco de dados
        $stmt = $this->link->prepare("SELECT * FROM formulario WHERE id_user = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca todos os dados do usuário pelo ID.
     *
     * @param int $id ID do aluno.
     * @return array|false Retorna um array com os dados do aluno ou false em caso de erro.
     */
    public function printDadosUser($id)
    {
        $stmt = $this->link->prepare("SELECT * FROM aluno WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $tabela =  $stmt->fetch(PDO::FETCH_ASSOC); 
        return $tabela;
    }
}