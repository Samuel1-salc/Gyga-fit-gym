<?php

/**
 * Classe responsável pelas operações relacionadas às solicitações de treino dos alunos.
 * Permite criar, buscar, contar e atualizar solicitações de treino.
 */
class SolicitacaoTreino
{
    /**
     * @var PDO $link Instância da conexão PDO.
     */
    private $link;

    /**
     * @var Database $con Instância da conexão com o banco de dados.
     */
    private $con;

    /**
     * Construtor da classe SolicitacaoTreino.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    /**
     * Solicita um novo treino para o aluno.
     *
     * @param string $data_created Data de criação da solicitação (Y-m-d H:i:s).
     * @param int $id_aluno ID do aluno.
     * @param string $experiencia Nível de experiência do aluno.
     * @param string $objetivo Objetivo do aluno.
     * @param int $treinos Quantidade de treinos desejados.
     * @param string $sexo Sexo do aluno.
     * @param float $peso Peso do aluno.
     * @param float $altura Altura do aluno.
     * @param string $status Status da solicitação.
     * @return bool Retorna true em caso de sucesso ou false em caso de erro.
     */
    public function SolicitarTreino($data_created, $id_aluno, $experiencia, $objetivo, $treinos, $sexo, $peso, $altura, $status)
    {
        try {
            $stmt = $this->link->prepare("
                INSERT INTO formulario (data_created,id_aluno, experiencia, objetivo, treinos, sexo, peso, altura,status)
                VALUES (:data_created,:id_aluno, :experiencia, :objetivo, :treinos, :sexo, :peso, :altura,:status)
            ");

            $stmt->bindParam(':data_created', $data_created);
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':experiencia', $experiencia);
            $stmt->bindParam(':objetivo', $objetivo);
            $stmt->bindParam(':treinos', $treinos);
            $stmt->bindParam(':sexo', $sexo);
            $stmt->bindParam(':peso', $peso);
            $stmt->bindParam(':altura', $altura);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erro ao solicitar treino: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca todos os formulários de solicitação de treino.
     *
     * @return array|false Retorna um array com todos os formulários ou false em caso de erro.
     */
    public function getTodosFormularios()
    {
        try {
            $stmt = $this->link->prepare("
                SELECT id_aluno, status FROM formulario 
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitações de treino: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca todas as solicitações de treino de um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com as solicitações ou false em caso de erro.
     * @throws InvalidArgumentException Se o ID do aluno for inválido.
     */
    public function getSolicitacaoTreino($id_aluno)
    {
        if (!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if (empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT * FROM formulario WHERE id_aluno = :id_aluno
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetchALL(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitação de treino: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca a data e hora da última solicitação de treino de um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com a data da última solicitação ou false em caso de erro.
     * @throws InvalidArgumentException Se o ID do aluno for inválido.
     */
    public function getDataTimeSolicitacaoTreino($id_aluno)
    {
        if (!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if (empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT data_created FROM formulario WHERE id_aluno = :id_aluno ORDER BY data_created DESC LIMIT 1
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitação de treino: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca o formulário mais recente para criação de treino de um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com o formulário ou false em caso de erro.
     * @throws InvalidArgumentException Se o ID do aluno for inválido.
     */
    public function getFormularioForCriacaoDeTreino($id_aluno)
    {
        if (!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if (empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT * FROM formulario WHERE id_aluno = :id_aluno ORDER BY id DESC LIMIT 1
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitação de treino: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Conta o número de solicitações de treino de um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return int Retorna o total de solicitações.
     */
    public function contarSolicitacoesTreino($id_aluno)
    {
        try {
            $stmt = $this->link->prepare("
                SELECT COUNT(*) as total FROM formulario WHERE id_aluno = :id_aluno
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            echo "Erro ao contar solicitações de treino: " . $e->getMessage();
            return 0;
        }
    }

    /**
     * Conta o número de solicitações de treino com determinado status.
     *
     * @param string $statuss Status a ser contado (ex: 'em andamento', 'atendido').
     * @return int Retorna o total de solicitações com o status informado.
     */
    public function contarPendentes($statuss)
    {
        try {
            $stmt = $this->link->prepare("
             SELECT COUNT(*) as total FROM formulario WHERE status = :status
            ");
            $stmt->bindParam(':status', $statuss);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            echo "Erro ao contar solicitações de treino: " . $e->getMessage();
            return 0;
        }
    }

    /**
     * Atualiza o status da solicitação de treino de um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @param string $status Novo status da solicitação.
     * @return bool Retorna true em caso de sucesso ou false em caso de erro.
     */
    public function atualizarStatusSolicitacao($id_aluno, $status)
    {
        try {
            $stmt = $this->link->prepare("
                UPDATE formulario SET status = :status WHERE id_aluno = :id_aluno
            ");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id_aluno', $id_aluno);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar status da solicitação: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca todos os formulários para alunos de um instrutor específico.
     *
     * @param int $id_instrutor ID do instrutor.
     * @param string|null $status Status opcional para filtrar os formulários.
     * @return array|false Retorna um array com todos os formulários dos alunos do instrutor ou false em caso de erro.
     * @throws InvalidArgumentException Se o ID do instrutor for inválido.
     */
    public function getFormulariosByIdInstrutor($id_instrutor, $status = null)
    {
        if (!is_numeric($id_instrutor)) {
            throw new InvalidArgumentException("ID do instrutor deve ser um número.");
        }
        if (empty($id_instrutor)) {
            throw new InvalidArgumentException("ID do instrutor não pode ser vazio.");
        }

        try {
            $sql = "
                SELECT f.*, ai.nome_aluno, ai.contato_aluno, ai.data_solicitacao
                FROM formulario f
                INNER JOIN aluno_instrutor ai ON f.id_aluno = ai.id_Aluno
                WHERE ai.id_instrutor = :id_instrutor
            ";

            // Adiciona filtro por status se fornecido
            if ($status !== null) {
                $sql .= " AND f.status = :status";
            }

            // Ordena por data de criação do mais recente para o mais antigo
            $sql .= " ORDER BY f.data_created DESC";

            $stmt = $this->link->prepare($sql);
            $stmt->bindParam(':id_instrutor', $id_instrutor);

            if ($status !== null) {
                $stmt->bindParam(':status', $status);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar formulários dos alunos do instrutor: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Conta o número de solicitações pendentes para um instrutor específico.
     *
     * @param int $id_instrutor ID do instrutor.
     * @return int Retorna o total de solicitações pendentes do instrutor.
     */
    public function contarPendentesInstrutor($id_instrutor)
    {
        try {
            $stmt = $this->link->prepare("
                SELECT COUNT(*) as total 
                FROM formulario f
                INNER JOIN aluno_instrutor ai ON f.id_aluno = ai.id_Aluno
                WHERE ai.id_instrutor = :id_instrutor AND f.status = 'em andamento'
            ");
            $stmt->bindParam(':id_instrutor', $id_instrutor);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            echo "Erro ao contar solicitações pendentes do instrutor: " . $e->getMessage();
            return 0;
        }
    }
}
