# Arquitetura MVC + Service: Guia Completo para PHP

## Índice
1. [Visão Geral da Arquitetura](#visão-geral)
2. [Camada Model](#camada-model)
3. [Camada View](#camada-view)
4. [Camada Controller](#camada-controller)
5. [Camada Service](#camada-service)
6. [Fluxo de Dados](#fluxo-de-dados)
7. [Exemplos Práticos](#exemplos-práticos)
8. [Boas Práticas](#boas-práticas)
9. [Erros Comuns](#erros-comuns)

## Visão Geral da Arquitetura {#visão-geral}

A arquitetura MVC + Service separa as responsabilidades em 4 camadas distintas:

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│    VIEW     │────│ CONTROLLER  │────│   SERVICE   │────│    MODEL    │
│ Apresentação│    │ Coordenação │    │ Lógica de   │    │ Acesso a    │
│             │    │ HTTP        │    │ Negócio     │    │ Dados       │
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
```

---

## Camada Model {#camada-model}

### 🎯 Responsabilidade Principal
**Acesso e manipulação de dados - APENAS isso!**

### ✅ O que DEVE fazer:
- Conectar com banco de dados
- Executar queries (SELECT, INSERT, UPDATE, DELETE)
- Mapear dados do banco para objetos PHP
- Validações básicas de dados (formato, tipo)
- Relacionamentos entre entidades

### ❌ O que NÃO DEVE fazer:
- Lógica de negócio complexa
- Formatação de dados para apresentação
- Validações de regras de negócio
- Processamento HTTP
- Cálculos complexos

### 📝 Exemplo Prático - Model Correto:

```php
<?php
// models/Usuarios.class.php
class Users {
    private $db;
    
    public function __construct() {
        $this->db = new PDO(...);
    }
    
    // ✅ CORRETO: Apenas acesso a dados
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    // ✅ CORRETO: Operação básica de dados
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (username, email, cpf) VALUES (?, ?, ?)"
        );
        return $stmt->execute([
            $data['username'],
            $data['email'], 
            $data['cpf']
        ]);
    }
    
    // ✅ CORRETO: Query com filtros simples
    public function getByStatus(string $status): array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ❌ INCORRETO: Lógica de negócio no Model
    /*
    public function getStudentsWithExpiredPlans(): array {
        $users = $this->getAll();
        $expired = [];
        
        foreach($users as $user) {
            $endDate = new DateTime($user['data_termino']);
            $today = new DateTime();
            
            if($endDate < $today) {
                $user['days_expired'] = $today->diff($endDate)->days;
                $user['status_formatted'] = 'Plano Expirado';
                $expired[] = $user;
            }
        }
        
        return $expired; // ❌ Isso é lógica de negócio!
    }
    */
}
```

### 🔧 Dicas para Model:
1. **Uma classe por tabela/entidade**
2. **Métodos pequenos e específicos**
3. **Use prepared statements sempre**
4. **Retorne tipos consistentes (array, object, null)**

---

## Camada View {#camada-view}

### 🎯 Responsabilidade Principal
**Apresentação visual - HTML, CSS, JavaScript frontend**

### ✅ O que DEVE fazer:
- Renderizar HTML
- Exibir dados recebidos do Controller
- Incluir CSS e JavaScript
- Formulários e interfaces
- Componentes reutilizáveis

### ❌ O que NÃO DEVE fazer:
- Acessar banco de dados diretamente
- Lógica de negócio
- Processamento de dados
- Validações complexas
- Cálculos

### 📝 Exemplo Prático - View Correta:

```php
<?php
// view/instructor/painel.php - ✅ CORRETO
// Dados já processados chegam do Controller
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Instrutor</title>
    <link rel="stylesheet" href="./view/style/instructor.css">
</head>
<body>
    <!-- ✅ CORRETO: Apenas apresentação -->
    <header>
        <h1>Bem-vindo, <?= htmlspecialchars($instructor['name']) ?></h1>
        <div class="metrics">
            <div class="metric-card">
                <h3><?= $metrics['total_students'] ?></h3>
                <p>Alunos Ativos</p>
            </div>
            <div class="metric-card">
                <h3><?= $metrics['pending_requests'] ?></h3>
                <p>Solicitações Pendentes</p>
            </div>
        </div>
    </header>

    <main>
        <!-- ✅ CORRETO: Formulário simples -->
        <form method="GET" class="search-form">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Buscar</button>
        </form>

        <!-- ✅ CORRETO: Loop simples de apresentação -->
        <div class="students-grid">
            <?php foreach($students as $student): ?>
                <div class="student-card">
                    <h3><?= htmlspecialchars($student['name']) ?></h3>
                    <p>Status: <span class="status-<?= $student['status_class'] ?>">
                        <?= $student['status_text'] ?>
                    </span></p>
                    <p>Plano: <?= htmlspecialchars($student['plan_name']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- ✅ CORRETO: JavaScript simples de interface -->
    <script>
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            const search = this.querySelector('input[name="search"]').value.trim();
            if(!search) {
                e.preventDefault();
                alert('Digite algo para buscar');
            }
        });
    </script>
</body>
</html>
```

### 📝 Exemplo de View Incorreta:

```php
<?php
// ❌ INCORRETO: Lógica no View
require_once '../models/Users.class.php';

$userModel = new Users(); // ❌ View acessando Model
$users = $userModel->getAll();

// ❌ INCORRETO: Processamento de dados no View
$activeUsers = [];
foreach($users as $user) {
    $endDate = new DateTime($user['data_termino']);
    $today = new DateTime();
    
    if($endDate >= $today) {
        $interval = $today->diff($endDate);
        $user['remaining_days'] = $interval->days;
        $activeUsers[] = $user;
    }
}
?>
<!-- HTML aqui... -->
```

### 🔧 Dicas para View:
1. **Dados sempre vêm do Controller**
2. **Use htmlspecialchars() para segurança**
3. **Crie componentes reutilizáveis**
4. **JavaScript apenas para interação de UI**

---

## Camada Controller {#camada-controller}

### 🎯 Responsabilidade Principal
**Coordenação entre camadas e tratamento HTTP**

### ✅ O que DEVE fazer:
- Receber requests HTTP (GET, POST)
- Validar entrada de dados
- Chamar Services para lógica de negócio
- Preparar dados para View
- Gerenciar sessões e autenticação
- Redirecionar usuário

### ❌ O que NÃO DEVE fazer:
- Lógica de negócio complexa
- Acesso direto ao banco de dados
- Formatação complexa de dados
- Cálculos de negócio

### 📝 Exemplo Prático - Controller Correto:

```php
<?php
// controllers/InstructorController.php - ✅ CORRETO
require_once __DIR__ . '/../services/InstructorService.php';

class InstructorController {
    private $instructorService;
    
    public function __construct() {
        $this->instructorService = new InstructorService();
    }
    
    // ✅ CORRETO: Coordenação e preparação de dados
    public function showPanel() {
        try {
            // ✅ Validação de entrada
            $instructorId = $_SESSION['user_id'] ?? null;
            if (!$instructorId) {
                header('Location: telaLogin.php');
                exit;
            }
            
            // ✅ Coleta parâmetros HTTP
            $search = trim($_GET['search'] ?? '');
            $statusFilter = $_GET['status'] ?? 'all';
            
            // ✅ Chama Service para lógica de negócio
            $instructor = $this->instructorService->getInstructorProfile($instructorId);
            $metrics = $this->instructorService->getDashboardMetrics($instructorId);
            $students = $this->instructorService->getFilteredStudents($instructorId, $search, $statusFilter);
            
            // ✅ Preparação simples de dados para View
            $data = [
                'instructor' => $instructor,
                'metrics' => $metrics,
                'students' => $students,
                'search' => $search,
                'statusFilter' => $statusFilter,
                'hasError' => false,
                'errorMessage' => ''
            ];
            
            // ✅ Inclui View com dados preparados
            include __DIR__ . '/../view/instructor/painel.php';
            
        } catch (Exception $e) {
            // ✅ Tratamento de erro no Controller
            $data = [
                'hasError' => true,
                'errorMessage' => 'Erro ao carregar painel: ' . $e->getMessage(),
                'students' => [],
                'metrics' => []
            ];
            
            include __DIR__ . '/../view/instructor/painel.php';
        }
    }
    
    // ✅ CORRETO: Processamento de formulário
    public function updateStudentStatus() {
        try {
            // ✅ Validação HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
            $newStatus = trim($_POST['status'] ?? '');
            
            if (!$studentId || !$newStatus) {
                throw new Exception('Dados inválidos');
            }
            
            // ✅ Chama Service para lógica
            $result = $this->instructorService->updateStudentStatus($studentId, $newStatus);
            
            if ($result) {
                $_SESSION['success_message'] = 'Status atualizado com sucesso!';
            } else {
                $_SESSION['error_message'] = 'Erro ao atualizar status';
            }
            
            // ✅ Redirecionamento
            header('Location: perfilInstrutor.php');
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: perfilInstrutor.php');
            exit;
        }
    }
}
```

### 🔧 Dicas para Controller:
1. **Mantenha métodos pequenos e focados**
2. **Use try-catch para tratamento de erros**
3. **Valide dados de entrada sempre**
4. **Delegue lógica complexa para Services**

---

## Camada Service {#camada-service}

### 🎯 Responsabilidade Principal
**Lógica de negócio e orquestração de operações**

### ✅ O que DEVE fazer:
- Implementar regras de negócio
- Validações complexas
- Cálculos e transformações
- Orquestrar múltiplos Models
- Processamento de dados
- Aplicar filtros e ordenações

### ❌ O que NÃO DEVE fazer:
- Acessar dados HTTP diretamente ($_GET, $_POST)
- Renderizar HTML
- Gerenciar sessões
- Fazer redirects

### 📝 Exemplo Prático - Service Correto:

```php
<?php
// services/InstructorService.php - ✅ CORRETO
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';

class InstructorService {
    private $userModel;
    private $instructorModel;
    
    public function __construct() {
        $this->userModel = new Users();
        $this->instructorModel = new aluno_instrutor();
    }
    
    // ✅ CORRETO: Lógica de negócio complexa
    public function getDashboardMetrics(int $instructorId): array {
        // Orquestra múltiplos Models
        $allStudents = $this->instructorModel->getStudentsByInstructor($instructorId);
        $requests = $this->instructorModel->getTrainingRequests($instructorId);
        
        // Aplica regras de negócio
        $activeStudents = 0;
        $expiredPlans = 0;
        $today = new DateTime();
        
        foreach ($allStudents as $student) {
            $endDate = new DateTime($student['data_termino']);
            
            if ($endDate >= $today) {
                $activeStudents++;
            } else {
                $expiredPlans++;
            }
        }
        
        $pendingRequests = 0;
        foreach ($requests as $request) {
            if ($request['status'] === 'pending') {
                $pendingRequests++;
            }
        }
        
        return [
            'total_students' => count($allStudents),
            'active_students' => $activeStudents,
            'expired_plans' => $expiredPlans,
            'pending_requests' => $pendingRequests,
            'completion_rate' => $this->calculateCompletionRate($allStudents)
        ];
    }
    
    // ✅ CORRETO: Processamento e filtros complexos
    public function getFilteredStudents(int $instructorId, string $search = '', string $statusFilter = 'all'): array {
        $students = $this->instructorModel->getStudentsByInstructor($instructorId);
        
        // Aplica transformações de dados
        $processedStudents = array_map(function($student) {
            return $this->processStudentData($student);
        }, $students);
        
        // Aplica filtros de negócio
        if ($search !== '') {
            $processedStudents = $this->applySearchFilter($processedStudents, $search);
        }
        
        if ($statusFilter !== 'all') {
            $processedStudents = $this->applyStatusFilter($processedStudents, $statusFilter);
        }
        
        // Ordena por regra de negócio
        usort($processedStudents, function($a, $b) {
            // Prioriza alunos com planos próximos ao vencimento
            return $a['days_remaining'] <=> $b['days_remaining'];
        });
        
        return $processedStudents;
    }
    
    // ✅ CORRETO: Validação e regra de negócio
    public function updateStudentStatus(int $studentId, string $newStatus): bool {
        // Validação de regra de negócio
        $allowedStatuses = ['active', 'inactive', 'suspended', 'expired'];
        if (!in_array($newStatus, $allowedStatuses)) {
            throw new Exception('Status inválido');
        }
        
        $student = $this->userModel->getById($studentId);
        if (!$student) {
            throw new Exception('Aluno não encontrado');
        }
        
        // Regra de negócio: não pode reativar plano expirado sem renovação
        if ($newStatus === 'active' && $this->isPlanExpired($student)) {
            throw new Exception('Não é possível ativar aluno com plano expirado');
        }
        
        return $this->userModel->updateStatus($studentId, $newStatus);
    }
    
    // ✅ CORRETO: Método privado para lógica específica
    private function processStudentData(array $student): array {
        $endDate = new DateTime($student['data_termino']);
        $today = new DateTime();
        $interval = $today->diff($endDate);
        
        $student['days_remaining'] = $endDate >= $today ? $interval->days : -$interval->days;
        $student['status_class'] = $this->getStatusClass($student, $endDate, $today);
        $student['status_text'] = $this->getStatusText($student, $endDate, $today);
        $student['plan_name'] = $this->formatPlanName($student['plano']);
        
        return $student;
    }
    
    private function calculateCompletionRate(array $students): float {
        if (empty($students)) return 0.0;
        
        $completed = 0;
        foreach ($students as $student) {
            if ($student['training_completed'] ?? false) {
                $completed++;
            }
        }
        
        return round(($completed / count($students)) * 100, 2);
    }
    
    // Mais métodos privados para lógica específica...
}
```

### 🔧 Dicas para Service:
1. **Uma classe Service por domínio de negócio**
2. **Métodos públicos para operações principais**
3. **Métodos privados para lógica auxiliar**
4. **Sempre valide dados recebidos**

---

## Fluxo de Dados {#fluxo-de-dados}

### 🔄 Fluxo Ideal:
```
1. USER faz request HTTP
   ↓
2. CONTROLLER recebe e valida
   ↓
3. CONTROLLER chama SERVICE
   ↓
4. SERVICE aplica lógica de negócio
   ↓
5. SERVICE chama MODEL(s) para dados
   ↓
6. MODEL acessa banco de dados
   ↓
7. Dados voltam: MODEL → SERVICE → CONTROLLER
   ↓
8. CONTROLLER prepara dados para VIEW
   ↓
9. VIEW renderiza HTML para USER
```

### 📝 Exemplo de Fluxo Completo:

```php
// 1. Request: GET /instructor/panel?search=João&status=active

// 2. Controller recebe
class InstructorController {
    public function showPanel() {
        $search = $_GET['search'] ?? '';     // ✅ Coleta dados HTTP
        $status = $_GET['status'] ?? 'all';
        
        // 3. Controller chama Service
        $students = $this->instructorService->getFilteredStudents(
            $_SESSION['user_id'], 
            $search, 
            $status
        );
        
        // 8. Prepara para View
        include 'view/instructor/painel.php';
    }
}

// 4. Service aplica lógica
class InstructorService {
    public function getFilteredStudents($instructorId, $search, $status) {
        // 5. Service chama Models
        $students = $this->instructorModel->getByInstructor($instructorId);
        
        // 4. Aplica regras de negócio
        $filtered = array_filter($students, function($student) use ($search, $status) {
            $nameMatch = stripos($student['name'], $search) !== false;
            $statusMatch = $status === 'all' || $student['status'] === $status;
            return $nameMatch && $statusMatch;
        });
        
        // 7. Retorna dados processados
        return $filtered;
    }
}

// 6. Model acessa dados
class InstructorModel {
    public function getByInstructor($instructorId) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE instructor_id = ?");
        $stmt->execute([$instructorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// 9. View renderiza
foreach($students as $student) {
    echo "<div>{$student['name']}</div>";
}
```

---

## Quando Usar a Camada Service {#quando-usar-service}

### ✅ Use Service quando tiver:
- **Lógica de negócio complexa** (cálculos, validações, transformações)
- **Operações que envolvem múltiplos Models**
- **Regras de negócio que podem mudar**
- **Processamento de dados antes da apresentação**
- **Validações que vão além de formato/tipo**

### ❌ NÃO precisa de Service para:
- **CRUDs simples** (apenas listar, criar, editar, deletar)
- **Páginas estáticas**
- **Operações que só fazem uma query simples**

### 📝 Comparação - Com e Sem Service:

```php
// ❌ SEM Service - Controller com muita lógica
class StudentController {
    public function showList() {
        $userModel = new Users();
        $students = $userModel->getAll();
        
        // ❌ Lógica de negócio no Controller
        $processed = [];
        foreach($students as $student) {
            $endDate = new DateTime($student['data_termino']);
            $today = new DateTime();
            
            if($endDate >= $today) {
                $interval = $today->diff($endDate);
                $student['status'] = 'Ativo';
                $student['remaining'] = $interval->days . ' dias';
            } else {
                $student['status'] = 'Expirado';
                $student['remaining'] = 'Plano vencido';
            }
            
            $processed[] = $student;
        }
        
        include 'view/students/list.php';
    }
}

// ✅ COM Service - Separação clara
class StudentController {
    public function showList() {
        $studentService = new StudentService();
        $students = $studentService->getProcessedStudents();
        
        include 'view/students/list.php';
    }
}

class StudentService {
    public function getProcessedStudents(): array {
        $userModel = new Users();
        $students = $userModel->getAll();
        
        return array_map([$this, 'processStudentData'], $students);
    }
    
    private function processStudentData(array $student): array {
        $endDate = new DateTime($student['data_termino']);
        $today = new DateTime();
        
        if($endDate >= $today) {
            $interval = $today->diff($endDate);
            $student['status'] = 'Ativo';
            $student['remaining'] = $interval->days . ' dias';
        } else {
            $student['status'] = 'Expirado';
            $student['remaining'] = 'Plano vencido';
        }
        
        return $student;
    }
}
```

---

## Boas Práticas Gerais {#boas-práticas}

### 🏗️ Estrutura de Projeto:
```
projeto/
├── controllers/          # Coordenação HTTP
├── services/            # Lógica de negócio
├── models/              # Acesso a dados
├── view/                # Apresentação
│   ├── components/      # Componentes reutilizáveis
│   └── layouts/         # Layouts base
├── config/              # Configurações
└── docs/                # Documentação
```

### 📋 Checklist de Qualidade:

**Model:**
- [ ] Apenas acesso a dados
- [ ] Prepared statements
- [ ] Retorno consistente
- [ ] Sem lógica de negócio

**View:**
- [ ] Apenas HTML/CSS/JS
- [ ] Dados vêm do Controller
- [ ] htmlspecialchars() para segurança
- [ ] Componentes reutilizáveis

**Controller:**
- [ ] Validação de entrada
- [ ] Tratamento de erros
- [ ] Delegação para Service
- [ ] Preparação simples de dados

**Service:**
- [ ] Lógica de negócio clara
- [ ] Métodos pequenos e focados
- [ ] Validações de negócio
- [ ] Orquestração de Models

### 🧪 Testabilidade:
```php
// ✅ Código testável - Service isolado
class StudentService {
    private $userModel;
    
    public function __construct($userModel = null) {
        $this->userModel = $userModel ?: new Users();
    }
    
    public function calculateRemainingDays(string $endDate): int {
        $end = new DateTime($endDate);
        $today = new DateTime();
        return $today->diff($end)->days;
    }
}

// Teste
$mockModel = $this->createMock(Users::class);
$service = new StudentService($mockModel);
$days = $service->calculateRemainingDays('2024-12-31');
$this->assertEquals(30, $days);
```

---

## Erros Comuns {#erros-comuns}

### ❌ Model fazendo lógica de negócio:
```php
// ❌ INCORRETO
class Users {
    public function getActiveStudentsWithMetrics() {
        $students = $this->getAll();
        
        // ❌ Lógica de negócio no Model
        foreach($students as &$student) {
            $endDate = new DateTime($student['data_termino']);
            $student['is_expired'] = $endDate < new DateTime();
            $student['formatted_status'] = $student['is_expired'] ? 'Expirado' : 'Ativo';
        }
        
        return $students;
    }
}

// ✅ CORRETO
class Users {
    public function getAll(): array {
        // Apenas acesso a dados
        $stmt = $this->db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

### ❌ Controller com lógica complexa:
```php
// ❌ INCORRETO
class StudentController {
    public function dashboard() {
        $userModel = new Users();
        $students = $userModel->getAll();
        
        // ❌ Lógica complexa no Controller
        $metrics = [];
        $active = 0;
        $expired = 0;
        
        foreach($students as $student) {
            $endDate = new DateTime($student['data_termino']);
            $today = new DateTime();
            
            if($endDate >= $today) {
                $active++;
                $interval = $today->diff($endDate);
                if($interval->days <= 30) {
                    $metrics['expiring_soon'][] = $student;
                }
            } else {
                $expired++;
                $expiredDays = $today->diff($endDate)->days;
                if($expiredDays <= 7) {
                    $metrics['recently_expired'][] = $student;
                }
            }
        }
        
        // ... mais lógica ...
    }
}

// ✅ CORRETO
class StudentController {
    public function dashboard() {
        $studentService = new StudentService();
        $metrics = $studentService->getDashboardMetrics();
        
        include 'view/dashboard.php';
    }
}
```

### ❌ View acessando dados:
```php
// ❌ INCORRETO - View fazendo query
<?php
require_once '../models/Users.class.php';
$userModel = new Users();
$students = $userModel->getAll(); // ❌ View acessando Model
?>
<html>
<!-- HTML aqui -->

// ✅ CORRETO - Dados vêm do Controller
<?php
// Dados já preparados pelo Controller
foreach($students as $student) {
    echo "<div>{$student['name']}</div>";
}
?>
```

---

## Benefícios da Arquitetura {#benefícios}

### 🚀 Escalabilidade:
- **Fácil adicionar novas funcionalidades**
- **Services podem ser reutilizados**
- **Models independentes**

### 🔧 Manutenibilidade:
- **Mudanças isoladas por camada**
- **Código mais legível**
- **Debugging mais fácil**

### 🧪 Testabilidade:
- **Services testáveis independentemente**
- **Mock de dependências**
- **Testes unitários focados**

### 👥 Trabalho em Equipe:
- **Diferentes pessoas podem trabalhar em camadas diferentes**
- **Padrões claros para todos seguirem**
- **Code reviews mais efetivos**

---

## Conclusão

A arquitetura MVC + Service traz **organização, clareza e manutenibilidade** para projetos PHP. O investimento inicial em estruturação se paga rapidamente em **velocidade de desenvolvimento** e **facilidade de manutenção**.

**Lembre-se da regra de ouro:**
- **Model**: "Como acessar dados?"
- **View**: "Como mostrar?"  
- **Controller**: "O que fazer com a requisição?"
- **Service**: "Quais regras de negócio aplicar?"

Cada camada tem uma responsabilidade clara e bem definida. Seguindo esses princípios, seu código será mais **profissional, testável e escalável**.