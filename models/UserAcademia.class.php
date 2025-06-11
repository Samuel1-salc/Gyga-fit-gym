<?php

/**
 * Classe responsável pelas operações relacionadas ao cadastro de academias.
 */
class UserAcademia
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
     * Construtor da classe UserAcademia.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
     *
     * @param string $nome Nome da unidade.
     * @param int $capacidade Capacidade máxima de alunos.
     * @param int $alunosAtivos Número de alunos ativos atualmente.
     * @param int $totalPersonais Número total de personais da unidade.
     * @return bool True em caso de sucesso, False em caso de erro.
     */
    public function cadastrarAcademia($nome, $capacidade, $alunosAtivos, $totalPersonais, $cep, $email, $cnpj, $unidade)
    {
        try {
            $stmt = $this->link->prepare("
                INSERT INTO academia (nome, capacidade, alunosAtivos, totalPersonais, cep, email, cnpj, unidade)
                VALUES (:nome, :capacidade, :alunos_ativos, :total_personais, :cep, :email, :cnpj, :unidade)
            ");

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':capacidade', $capacidade);
            $stmt->bindParam(':alunos_ativos', $alunosAtivos);
            $stmt->bindParam(':total_personais', $totalPersonais);
            $stmt->bindParam(':cep', $cep);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cnpj', $cnpj);
            $stmt->bindParam(':unidade', $unidade);


            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar academia: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Obtém informações das academias com contagem de alunos e instrutores por unidade
     * 
     * @param string|null $unidade Unidade específica (opcional, se null retorna todas)
     * @return array Lista de academias com dados completos
     */
    public function getInfoAcademia($unidade)
    {
        try {
            $sql = "
                SELECT 
                    a.*,
                    COALESCE(alunos.total_alunos, 0) as total_alunos_atual,
                    COALESCE(instrutores.total_instrutores, 0) as total_instrutores_atual
                FROM academia a
                LEFT JOIN (
                    SELECT 
                        unidade, 
                        COUNT(*) as total_alunos 
                    FROM aluno 
                    WHERE status = 'ativo' OR status IS NULL
                    GROUP BY unidade
                ) alunos ON a.unidade = alunos.unidade
                LEFT JOIN (
                    SELECT 
                        unidade, 
                        COUNT(*) as total_instrutores 
                    FROM instrutor 
                    WHERE status = 'ativo' OR status IS NULL
                    GROUP BY unidade
                ) instrutores ON a.unidade = instrutores.unidade
            ";

            // Se uma unidade específica foi fornecida, adiciona filtro
            if ($unidade !== null) {
                $sql .= " WHERE a.unidade = :unidade";
            }

            $sql .= " ORDER BY a.unidade";

            $stmt = $this->link->prepare($sql);

            if ($unidade !== null) {
                $stmt->bindParam(':unidade', $unidade, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar academias: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtém todas as academias (método básico)
     * 
     * @return array Lista de academias
     */
    public function getAllAcademias()
    {
        try {
            $stmt = $this->link->prepare("SELECT * FROM academia ORDER BY unidade");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar todas as academias: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca academia por unidade específica
     * 
     * @param string $unidade Código da unidade
     * @return array|null Dados da academia ou null se não encontrada
     */
    public function getAcademiaPorUnidade($unidade)
    {
        try {
            $stmt = $this->link->prepare("SELECT * FROM academia WHERE unidade = :unidade");
            $stmt->bindParam(':unidade', $unidade, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar academia por unidade: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Atualiza dados básicos de uma academia
     * 
     * @param string $unidade Código da unidade
     * @param array $dados Dados a serem atualizados
     * @return bool True em caso de sucesso
     */
    public function atualizarAcademia($unidade, $dados)
    {
        try {
            $campos = [];
            $valores = [];

            $camposPermitidos = ['nome', 'capacidade', 'cep', 'email', 'cnpj'];

            foreach ($camposPermitidos as $campo) {
                if (isset($dados[$campo])) {
                    $campos[] = "$campo = :$campo";
                    $valores[$campo] = $dados[$campo];
                }
            }

            if (empty($campos)) {
                return false;
            }

            $sql = "UPDATE academia SET " . implode(', ', $campos) . " WHERE unidade = :unidade";
            $stmt = $this->link->prepare($sql);

            $valores['unidade'] = $unidade;

            return $stmt->execute($valores);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar academia: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove uma academia
     * 
     * @param string $unidade Código da unidade
     * @return bool True em caso de sucesso
     */
    public function removerAcademia($unidade)
    {
        try {
            $stmt = $this->link->prepare("DELETE FROM academia WHERE unidade = :unidade");
            $stmt->bindParam(':unidade', $unidade, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao remover academia: " . $e->getMessage());
            return false;
        }
    }
}
