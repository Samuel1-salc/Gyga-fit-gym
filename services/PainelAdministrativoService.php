<?php
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ .  '/../models/usuarioInstrutor.class.php';

class PainelAdministrativoServiceService
{
    private $dataUsers;
    private $dataClienteInstrutor;

    public function __construct()
    {
        $this->dataUsers = new Users();
        $this->dataClienteInstrutor = new aluno_instrutor();
    }

    public function getMainData(int $userId, string $filter = '', string $sort = 'name'): array
    {
        // Busca dados base
        $rawData = $this->dataUsers->getByUser($userId);

        // Processa dados
        $processedData = array_map([$this, 'processItem'], $rawData);

        // Aplica filtros
        if (!empty($filter)) {
            $processedData = $this->applyFilter($processedData, $filter);
        }

        // Aplica ordenação
        $processedData = $this->applySort($processedData, $sort);

        return $processedData;
    }

    public function create(array $data): bool
    {
        // Validação de negócio
        $this->validateCreateData($data);

        // Processa dados antes de salvar
        $processedData = $this->prepareForSave($data);

        // Salva via dataUsers
        return $this->dataUsers->create($processedData);
    }

    private function processItem(array $item): array
    {
        // Aplicar regras de negócio específicas
        $item['processed_field'] = $this->calculateSomething($item);
        $item['status_text'] = $this->getStatusText($item['status']);

        return $item;
    }

    private function validateCreateData(array $data): void
    {
        if (empty($data['field1'])) {
            throw new Exception('Campo obrigatório não preenchido');
        }

        // Mais validações...
    }

    private function applyFilter(array $data, string $filter): array
    {
        return array_filter($data, function ($item) use ($filter) {
            return stripos($item['name'], $filter) !== false;
        });
    }

    private function applySort(array $data, string $sort): array
    {
        usort($data, function ($a, $b) use ($sort) {
            return $a[$sort] <=> $b[$sort];
        });

        return $data;
    }
}
