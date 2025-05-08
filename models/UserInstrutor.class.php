<?php

/**
 * Classe responsável pelas operações relacionadas ao cadastro de instrutores.
 * Permite cadastrar um novo instrutor e obter a data/hora atual formatada.
 */
class UserInstrutor
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
     * Construtor da classe UserInstrutor.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    /**
     * Cadastra um novo instrutor no banco de dados.
     *
     * @param string $Username Nome do instrutor.
     * @param string $Email Email do instrutor.
     * @param string $Cpf CPF do instrutor.
     * @param string $unidade Unidade da academia.
     * @param string $servico Especialidade/serviço do instrutor.
     * @param string $data_entrada Data de entrada do instrutor (Y-m-d H:i:s).
     * @param string|null $data_saida Data de saída do instrutor (Y-m-d H:i:s) ou null.
     * @param string $phone Telefone do instrutor.
     * @param string $typeUser Tipo de usuário (ex: 'instrutor').
     * @return void
     */
    public function cadastrarInstrutor($Username, $Email, $Cpf, $unidade, $servico, $data_entrada, $data_saida, $phone, $typeUser)
    {
        // Se data_saida estiver vazia, converte para null
        $data_saida = empty($data_saida) ? null : $data_saida;

        $stmt = $this->link->prepare("INSERT INTO instrutor 
            (username, email, cpf, unidade, servico, data_entrada, data_saida, phone, typeUser) 
            VALUES 
            (:username, :email, :cpf, :unidade, :servico, :data_entrada, :data_saida, :phone, :typeUser)");

        $stmt->bindValue(':username', $Username);
        $stmt->bindValue(':email', $Email);
        $stmt->bindValue(':cpf', $Cpf);
        $stmt->bindValue(':unidade', $unidade);
        $stmt->bindValue(':servico', $servico);
        $stmt->bindValue(':data_entrada', $data_entrada);
        $stmt->bindValue(':data_saida', $data_saida, $data_saida === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':typeUser', $typeUser);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode(' | ', $stmt->errorInfo());
        }
    }

    /**
     * Retorna a data e hora atual formatada.
     *
     * @return string Data e hora atual no formato Y-m-d H:i:s.
     */
    public function dataInicio()
    {
        $data = new DateTime();
        return $data->format('Y-m-d H:i:s');
    }
}
