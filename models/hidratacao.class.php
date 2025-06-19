<?php

class WaterTracking
{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con  = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
     * Obtenha a meta diária de ingestão de água de um usuário com base em seu peso.
     * @param int $user_id
     * @return float|null Meta diária de água em litros ou nula se não for encontrada
     */
    public function getDailyGoal($user_id)
    {
        $stmt = $this->link->prepare("SELECT daily_goal FROM water_tracking WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['daily_goal'] : null;
    }

    /**
     * Calcular a meta diária de água com base no peso (peso * 35 ml)
     * @param float $peso em kg
     * @return float meta diária em litros
     */
    public function calculateDailyGoal($weight)
    {
        return ($weight * 35) / 1000;
    }

    /**
     * Atualizar ou inserir dados de rastreamento de água para um usuário
     * @param int $user_id
     * @param float $peso
     * @return bool
     */
    public function updateWaterTracking($user_id, $weight)
    {
        $daily_goal = $this->calculateDailyGoal($weight);
        $now = date('Y-m-d H:i:s');

        
        $stmt = $this->link->prepare("SELECT COUNT(*) as count FROM water_tracking WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        if ($count > 0) {
            
            $stmt = $this->link->prepare("
                UPDATE water_tracking 
                SET weight = :weight, daily_goal = :daily_goal, last_updated = :last_updated
                WHERE user_id = :user_id
            ");
        } else {
           
            $stmt = $this->link->prepare("
                INSERT INTO water_tracking (user_id, weight, daily_goal, last_updated)
                VALUES (:user_id, :weight, :daily_goal, :last_updated)
            ");
        }

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':daily_goal', $daily_goal);
        $stmt->bindParam(':last_updated', $now);

        return $stmt->execute();
    }
}
