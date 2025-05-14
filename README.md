# Engenharia de Software 2025/01 | Universidade Federal do Tocantins 


**Curso:**
**Ciência da Computação** 

**Semestre:**
**1º semestre de 2025**

**Professor:**
**Edeílson Milhomem** 

Integrantes do Projeto 
João Vitor Hott 
Samuel Andrade 
Hallef Kayk 
Heitor Fernandes 
Maurício Monteiro 
Sophia Prado



 

# Projeto - Site de Academia Gyga Fit 
 
**Este projeto tem como objetivo a criação de um site para uma academia, desenvolvido como parte da disciplina de Engenharia de Software. O site permite que os usuários se registrem, visualizem planos de treino, acessem informações sobre os serviços da academia e realizem o login para gerenciar suas atividades. As funcionalidades foram divididas entre os membros do grupo e implementadas seguindo as melhores práticas de desenvolvimento de software.** 



## SPRINT 1 – Sistema de solicitação de treinos

**Valor:** Desenvolvimento das funcionalidades de solicitação e criação de treinos por parte do cliente e instrutor respectivamente.

__ 

| Código RF | Requisito Funcional                          | Tipo de Funcionalidade      | Responsável   | Data de Início | Data de Término | Duração (dias) | Status        | Notas                                                                 |
|-----------|----------------------------------------------|------------------------------|----------------|----------------|------------------|----------------|----------------|------------------------------------------------------------------------|
| RF-1      | Perfil do Instrutor (RF - 1) | perfil do instrutor         | Samuel/Hott         |                |                  |                | Concluído| Implementação do perfil do usuário                |
| RF-2      | Criação do Plano de Treino (RF - 2)                    | Formulário de criação| Mauricio/Heitor       |                |                  |                | Em andamento   | implementação do plano de treino para o user                               |
| RF-3      | Visualização dos Planos de Treino                 | Interface de Usuário (UI)   | Sophia/Samuel      |                |                  |                | Conclída pela metade  | Exibir dados do plano de treino da academia.                        |
| RF-4      | Criação do Formulário de Treino (RF - 4)             | Solicitação de Treino       | Hallef Kayk    |                |                  |                | Concluída     | Permitir ao aluno solicitar treino personalizado.                     |

___

### Perfil do Instrutor (RF - 1)

**Como** um instrutor, **eu quero** acessar uma interface com meu perfil contendo informações básicas e uma lista de solicitações de treino recebidas, para que eu possa acompanhar os pedidos e montar planos de treino personalizados para cada aluno.

#### Regras de Negócio

-Apenas usuários com perfil de instrutor autenticado poderão acessar essa interface.
-O perfil deve exibir informações básicas como: nome, especialidade, quantidade de alunos atendidos e disponibilidade.
-A interface deve listar todas as solicitações de treino recebidas com dados relevantes do aluno.
-A listagem de solicitações deve estar ordenada da mais recente para a mais antiga.
-O instrutor poderá marcar as solicitações como “em andamento” ou “finalizada”.

#### Tarefas

**Back-end/front-end :**

- Criar endpoint para retorno das informações básicas do instrutor autenticado.
- Criar interface para exibir perfil e lista de solicitações de treino.
- Implementar sistema de marcação de status das solicitações (em andamento/finalizada).
- Relacionar as solicitações ao instrutor responsável no banco de dados.

#### Critérios de Aceitação

- O instrutor deve conseguir acessar seu perfil após login com sucesso.
- As informações do perfil devem ser exibidas corretamente.
- A lista de solicitações deve conter os dados do aluno e estar atualizada em tempo real ou com refresh automático.
- O instrutor deve conseguir alterar o status da solicitação conforme o andamento do atendimento.
- Os dados das solicitações devem ser associados corretamente ao instrutor no banco de dados.

___

### Criação do Plano de Treino (RF - 2)

**Como** um instrutor, **eu quero** acessar as solicitações de treino enviadas pelos alunos para que eu possa criar planos de treino personalizados com base nas necessidades e objetivos de cada aluno.

#### Regras de Negócio

- Apenas instrutores autenticados poderão acessar e criar planos de treino.
- O plano de treino deve estar vinculado a uma solicitação previamente enviada pelo aluno.
- O plano deve conter campos obrigatórios como: divisão dos treinos (por dias da semana), exercícios indicados, séries, repetições e observações específicas.
- Após o envio do plano, o aluno correspondente deve ser notificado e terá acesso imediato ao conteúdo.
- O instrutor poderá editar o plano caso o aluno envie uma nova solicitação com alterações.
- Cada plano deve ficar registrado no histórico do aluno para futuras consultas.

#### Tarefas

**Back-end/front-end:**

- Criar endpoint para cadastro de planos de treino vinculados a uma solicitação.
- Criar interface para preenchimento do plano com os campos obrigatórios.
- Garantir que os dados sejam armazenados no banco de forma associada ao aluno e à solicitação correspondente.
- Implementar sistema de notificação ou atualização em tempo real para o aluno.

#### Critérios de Aceitação

- O instrutor deve conseguir visualizar as solicitações e criar um plano com base em cada uma delas.
- Todos os campos obrigatórios devem ser validados antes do envio.
- O plano deve ficar disponível para o aluno após sua criação.
- O sistema deve permitir edição do plano mediante nova solicitação do aluno.
- Os dados do plano devem estar corretamente relacionados à solicitação e ao aluno no banco de dados.
___

### Visualização dos Planos de Treino (RF - 3)

**Como** um aluno assinante, **eu quero** acessar uma interface onde eu possa visualizar meus planos de treino personalizados, para que eu consiga seguir corretamente os exercícios recomendados pelo instrutor.

#### Regras de Negócio

- Apenas alunos autenticados com assinatura ativa poderão acessar seus planos de treino.
- A interface deve exibir todos os planos de treino criados para o aluno, ordenados do mais recente para o mais antigo.
- Cada plano deve conter as informações completas inseridas pelo instrutor: divisão semanal, exercícios, séries, repetições e observações.
- O aluno deve conseguir visualizar planos anteriores como histórico.
- Caso o aluno envie uma nova solicitação de treino, a interface deve indicar que um novo plano está em criação ou aguardando resposta do instrutor.

#### Tarefas

**Back-end/front-end :**
 
- Criar endpoint para recuperação dos planos de treino vinculados ao aluno autenticado.
- Criar interface com visualização clara e organizada dos treinos por data e status.
- Exibir os detalhes completos de cada plano criado.
- Adicionar indicador de status para planos pendentes, em andamento ou finalizados.
- Armazenar e exibir o histórico de treinos anteriores do aluno.

#### Critérios de Aceitação

- O aluno deve conseguir acessar a interface de treinos após login e ver seus planos personalizados.
- A listagem deve exibir corretamente os planos com data de criação e status.
- Ao selecionar um plano, o aluno deve visualizar todos os detalhes preenchidos pelo instrutor.
- Planos anteriores devem permanecer acessíveis como histórico.
- A interface deve indicar se há um novo plano sendo criado ou aguardando resposta do instrutor.

___

### Criação do Formulário de Treino (RF - 4)

**como** um aluno **eu quero** preencher um formulário solicitando um treino personalizado para que o instrutor possa montar um plano de treino adequado às minhas necessidades e objetivos.

### Regras de Negócio

- Apenas alunos autenticados poderão acessar o formulário de solicitação de treino.
- O formulário deve conter campos obrigatórios como: objetivo do treino, frequência semanal, restrições físicas e específicas.
- O instrutor responsável será notificado quando um novo formulário for enviado.
- Cada aluno pode enviar um novo formulário quando desejar atualizar suas preferências.

#### Tarefas

**Back-end/front-end:**

- Criar endpoint para submissão de formulário de treino pelo aluno
- Criar interface com campos obrigatórios e formulários para o formulário
- Armazenar os dados no banco e relacionar ao aluno
- Implementar notificação ou lista de instruções para os instrutores

#### Critérios de Aceitação

- O aluno deve conseguir acessar e preencher o formulário após login.
- O formulário deve validar os campos obrigatórios antes do envio.
- O instrutor deve conseguir visualizar os dados enviados para criação do treino.
- Os dados devem ser armazenados corretamente no banco, associados ao aluno.
- O aluno deve conseguir reenviar um novo formulário caso deseje.

___


## SPRINT 2 – Sistema de Cadastro e Autenticação de Usuário 

**Valor:**  Desenvolvimento das funcionalidades de autenticação e perfis dos diferentes tipos de usuários (Aluno, Instrutor e Gerente). 

| Código RF | Requisito Funcional                          | Tipo de Funcionalidade      | Responsável   | Data de Início | Data de Término | Duração (dias) | Status        | Notas                                                                 |
|-----------|----------------------------------------------|------------------------------|----------------|----------------|------------------|----------------|----------------|------------------------------------------------------------------------|
| RF-5      | Sistema de gerente  | tela do administração      |   Heitor e Sophia        |                |                  |                | Em andamento   | Implementar com campos obrigatórios e tipo de usuário.                |
| RF-6      | Sistema de criação de treinos                   | Funcionalidade de implementação de treino dada a solicitação | Samuel e Heitor     |                |                  |                | Em andamento   |                              |
| RF-7      | Tela Inicial da academia                | Interface de Usuário (UI)   | João Hott      |                |                  |                | Conluída  | Exibir dados de usuários e perfil da academia.                        |                   |
| RF-8      | tela de acesso ao painel administrativo | Manipular a tela de login       | haleef-kayke/Samuel |                |                  |                | Planejando      | Permitir que a tela de login seja manipulada caso seja gerente                  |
___
| RF-9      | Roteamento de páginas| Manipular páginas a partir do index.php       | mauricio |                |                  |                | Planejando      | Configurações de niveis de acesso as paginas                  |
___
| RF-10      | Visualização dos Planos de Treino                 | Interface de Usuário (UI)   | Hott/Samuel      |                |                  |                | Em andamento  | Exibir dados do plano de treino da academia.  

### Sistema de gerente(RF - 5)
**como** um usário gerente **eu quero** cadastrar novos clientes(assinantes do plano da academia) para enfim os clientes terem acesso ao aplicativo.
**também quero** cadastrar instrutores(funcionários da academia com acesso ao sistema), para efim os mesmos terem acesso ao sistema e acessar os privilégios de instrutor.

#### Regra de Negócio
- O sistema deve ter três usuários: Adm(gerente), instrutor(funcionário), cliente(assinante da academia).
- Usuários serão cadastrados pelo gerente da academia.
- Usuários terão diferentes níveis de privilégio dentro do sistema, sendo o adm o mais alto e o cliente o mais baixo.
- O gerente deve criar um perfil para os usuários ao cadastrar.

#### Tarefas:
**backend/FrontEnd:**
- Implementar cadastro de usuários com o banco de dados(samuel)
- criar interface para o processo de cadastro(samuel)

#### Critérios de aceitação:
- Os usuários deverão receber confirmação de cadastro.
___

### Sistema de criação de planos de treino (RF - 6)
**como** um usuário instrutor **eu quero** criar um plano de treino para meu aluno, caso ele tenha enviado o formulário de solicitação de treio
para que eu possa fazer um plano de treino com base nas informações do formulário.

#### Regra de Negócio
- O sistema deve instruir o instrutor como ele deve fazer o plano de treino a partir das especificações dos alunos.
- A criação do plano de treino só deverá estar habilitada caso o aluno faça sua solicitação de treino.
- O plano de treino deve ser enviado para o aluno acessar em sua tela de alun.

#### Tarefas:
**backend/FrontEnd:**

- Implementar metodo post dinamicamente para o back-end poder gravar essas informações.
- Gerar variaveis temporárias de armazenamento dos dados.
- Implementar um front-end dinâmico para inserção e criação dos grupos de exercícios.

#### Critérios de aceitação:
- O usuário deve receber uma notificação dentro do sistema.
- A tela do aluno deve ser dinamicamente alterada a partir do treino estabelecido pelo instrutor.

___
### Tela inicial da academia (RF - 7)
**como** um visitante ou usuário da academia
**eu quero** poder ter acesso as informações institucionais da academia.

#### Regras de Negócio
- Deve conter informações institucionais da acemia de forma clara e organizada.
- Deverá ter acesso a tela de login casa um usuário já cadastrado na academia ter acesso ao sistema.

#### Tarefas:
**Backend/Frontend (João Hott):**


-Criar html e css para criação de uma ladding page.
-Criar interface funcional para acessar a tela de login.


#### Critérios de Aceitação:

- Deve conter informações institucionais, como horários de funcionamento, instrutores, localidade, e uma apresentação da academia.
- Deve também conter um acesso a tela de login.
- Deverá ser pública para usuários visitantes poderem conhecer mais sobre a academia.
___

### Tela de acesso ao painel administrativo (RF - 8)
**como** um gerente administrativo da academia
**eu quero** poder ter acesso ao painel administrativo.

#### Regras de Negócio
- Deve fazer uma verificação dupla e mais segura com senha criptografada.
- Deverá reconhecer o cpf do gerente e direcionar para o painel administrativo.

#### Tarefas:
**Backend/Frontend (João Hott):**


-Criar html e css para o painel de verificação com senha.
-Criar interface para a verificação de gerente da academia.


#### Critérios de Aceitação:

- Deve exigir senha do gerente.
- Deve fazer a verificação da senha do sistema.
- Deve redirecionar para a tela de painel administrativo caso a verificação esteja correta.
- Deverá ser uma tela que intermediará entre tela de login e painel administrativo.
___

### Organização da lógica de acesso (RF - 9)
**como** desenvolvedor do sistema  
**eu quero** criar e implementar o arquivo index.php  
**para que** ele centralize a lógica de verificação de sessão e redirecionamento de usuários com base no tipo de acesso.

#### Regras de Negócio
- O `index.php` deverá funcionar como ponto de entrada principal do sistema.
- Deve verificar se há uma sessão ativa e identificar o tipo de usuário (aluno, instrutor ou visitante).
- Usuários não autenticados devem ser redirecionados para a tela de login.
- Usuários autenticados devem ser encaminhados automaticamente para sua respectiva interface.
- Deve ser possível expandir o sistema com rotas simples via GET.

#### Tarefas:
**Backend (Mauricio):**

- Criar o `index.php` como entrada única do sistema.
- Implementar verificação de sessão e tipo de usuário.
- Redirecionar para as telas apropriadas (aluno, instrutor, erro, login).
- Criar um sistema de rotas simples para organização das páginas.
- Integrar as páginas internas com proteção contra acesso direto.
- Adicionar opção de logout via `index.php`.

#### Critérios de Aceitação:

- O sistema deve ser iniciado pelo `index.php` e proteger o acesso a páginas internas.
- Deve redirecionar corretamente alunos e instrutores com base na sessão.
- Visitantes devem ser redirecionados automaticamente para a tela de login.
- As páginas não devem ser acessíveis diretamente sem login.
- Deve existir um sistema básico de rotas e logout no `index.php`.
___

### Visualização dos Planos de Treino (RF - 10)

**Como** um aluno assinante, **eu quero** acessar uma interface onde eu possa visualizar meus planos de treino personalizados, para que eu consiga seguir corretamente os exercícios recomendados pelo instrutor.

#### Regras de Negócio

- Apenas alunos autenticados com assinatura ativa poderão acessar seus planos de treino.
- A interface deve exibir todos os planos de treino criados para o aluno, ordenados do mais recente para o mais antigo.
- Cada plano deve conter as informações completas inseridas pelo instrutor: divisão semanal, exercícios, séries, repetições e observações.
- O aluno deve conseguir visualizar planos anteriores como histórico.
- Caso o aluno envie uma nova solicitação de treino, a interface deve indicar que um novo plano está em criação ou aguardando resposta do instrutor.

#### Tarefas

**Back-end/front-end :**
 
- Criar recuperação dos planos de treino vinculados ao aluno autenticado.
- Criar interface com visualização clara e organizada dos treinos por data e status.
- Exibir os detalhes completos de cada plano criado.
- Adicionar indicador de status para planos pendentes, em andamento ou finalizados.
- Armazenar e exibir o histórico de treinos anteriores do aluno.

#### Critérios de Aceitação

- O aluno deve conseguir acessar a interface de treinos após login e ver seus planos personalizados.
- A listagem deve exibir corretamente os planos com data de criação e status.
- Ao selecionar um plano, o aluno deve visualizar todos os detalhes preenchidos pelo instrutor.
- Planos anteriores devem permanecer acessíveis como histórico.
- A interface deve indicar se há um novo plano sendo criado ou aguardando resposta do instrutor.

___

## SPRINT 3 - 

**Valor:** Desenvolvimento das funcionalidades de Tela de Menu, Back-End da tela de gerente, Redirecinamento de telas, refatorar login caso gerente e inserção de imagens no sistema.

___ 

| Código RF | Requisito Funcional                              | Tipo de Funcionalidade                                      | Responsável                   | Data de Início | Data de Término | Duração (dias) | Status       |
|-----------|--------------------------------------------------|-------------------------------------------------------------|-------------------------------|----------------|------------------|----------------|--------------|
| RF-11      | Menu da Academia com login dos alunos            | Tela de login e redirecionamento para o painel do aluno     | Samuel                        | 21/05           |                  |                | Em andamento |
| RF-12      | Menu de Aluno e Instrutor, editar perfil e foto  | Interface de usuário para edição de perfil                  | Hott                          | 21/05           |                  |                | Em andamento |
| RF-13      | Backend da página de gerente                     | Backend administrativo                                       | Sophia                        | 18/05           |                  |                | Em andamento |
| RF-14      | Redirecionamento de telas funcional              | Lógica de redirecionamento após login                       | Mauricio                      | 18/05           |                  |                | Em andamento |
| RF-15      | Refatorar tela de login se for gerente           | Tela de login com diferenciação de perfis                   | Hallef                        | 18/05           |                  |                | Em andamento |
| RF-16      | Inserção de imagens nos exercícios via URL       | Cadastro de exercício com imagem por URL no banco de dados | Heitor                        | 21/05           |                  |                | Em andamento |

___


### Menu da Academia com login dos alunos (RF - 11)

**Como** um aluno, **eu quero** acessar o sistema com meu login, para visualizar meu perfil, treinos e demais funcionalidades disponíveis no aplicativo.

#### Regras de Negócio

- Apenas usuários cadastrados como aluno poderão acessar essa área.
- O login deve verificar credenciais armazenadas no banco de dados.
- O sistema deve redirecionar o aluno para sua interface personalizada após o login.

#### Tarefas

**Back-end/front-end :**

- Criar endpoint de autenticação de alunos.
- Criar tela de login específica para alunos.
- Implementar redirecionamento para o painel do aluno.

#### Critérios de Aceitação

- O aluno deve conseguir realizar login com credenciais válidas.
- Após login, o sistema deve apresentar as funcionalidades exclusivas do perfil de aluno.
- Em caso de erro, mensagens apropriadas devem ser exibidas.

---

### Menu de Aluno e Instrutor, editar perfil e adição de foto (RF - 12)

**Como** aluno ou instrutor, **eu quero** editar meu perfil e adicionar uma foto, para manter minhas informações pessoais atualizadas.

#### Regras de Negócio

- A edição de perfil só pode ser feita pelo próprio usuário autenticado.
- O sistema deve permitir o envio de uma imagem para o perfil.
- Algumas informações sensíveis (como tipo de usuário) não devem ser editáveis.

#### Tarefas

**Back-end/front-end :**

- Criar endpoint para atualização de perfil.
- Criar interface para edição de dados e upload de imagem.
- Validar formatos de imagem e dados atualizados.

#### Critérios de Aceitação

- O usuário deve conseguir editar suas informações com sucesso.
- A foto deve ser exibida corretamente após upload.
- Informações não editáveis devem estar bloqueadas.

---

### Backend da página de gerente (RF - 13)

**Como** gerente da academia, **eu quero** acessar uma página administrativa funcional com backend completo, para gerenciar usuários e supervisionar as atividades do sistema.

#### Regras de Negócio

- Apenas usuários com perfil de gerente autenticado podem acessar essa área.
- A página deve permitir visualizar, editar ou excluir usuários do sistema.
- O backend deve fornecer dados relevantes em tempo real como número de clientes, instrutores e estatísticas.

#### Tarefas

**Back-end/front-end :**

- Criar API exclusiva para gerenciamento de usuários e permissões.
- Desenvolver a lógica de autenticação e autorização para o gerente.
- Criar interface para exibição e manipulação dos dados administrativos.

#### Critérios de Aceitação

- O gerente deve conseguir acessar sua página com sucesso após login.
- Os dados administrativos devem ser atualizados e corretos.
- A interface deve permitir ações de gerenciamento como editar ou excluir usuários.

---

### Redirecionamento de telas funcional (RF - 14)

**Como** usuário autenticado, **eu quero** ser redirecionado automaticamente para a interface correspondente ao meu perfil, para que eu acesse apenas os recursos destinados ao meu tipo de usuário.

#### Regras de Negócio

- O sistema deve verificar o tipo de usuário após autenticação.
- Cada tipo de usuário será redirecionado para uma interface distinta:
  - Cliente → painel do aluno
  - Instrutor → painel de instrutor
  - Gerente → painel administrativo
- O redirecionamento deve ocorrer de forma transparente e segura.

#### Tarefas

**Back-end/front-end :**

- Implementar lógica de redirecionamento com base no tipo de usuário autenticado.
- Garantir que o usuário não acesse áreas que não lhe pertencem.
- Exibir mensagem ou tela de erro caso o tipo de usuário não seja reconhecido.

#### Critérios de Aceitação

- O sistema deve identificar corretamente o tipo de usuário.
- O redirecionamento deve ocorrer de forma automática e correta após o login.
- O usuário não deve conseguir acessar outras áreas via URL manual.

---

### Refatorar tela de login se for gerente (RF - 15)

**Como** gerente, **eu quero** que a tela de login seja adaptada ao meu perfil, para garantir uma experiência personalizada e voltada à administração do sistema.

#### Regras de Negócio

- A tela de login deve identificar se o usuário é um gerente.
- Gerentes devem ter visual e funcionalidades diferentes ao logar.
- Recursos administrativos não devem ser visíveis para outros tipos de usuário.

#### Tarefas

**Back-end/front-end :**

- Refatorar tela de login para detecção do tipo de usuário.
- Criar elementos visuais distintos para o login do gerente.
- Garantir que o redirecionamento pós-login seja exclusivo.

#### Critérios de Aceitação

- O sistema deve exibir a interface administrativa após login de gerente.
- A diferenciação visual deve ser clara.
- O login de outros usuários não deve acessar funcionalidades de gerente.

---

### Inserção de imagens nos exercícios usando URL no banco de dados (RF - 16)

**Como** instrutor, **eu quero** adicionar imagens ilustrativas aos exercícios usando URLs, para que os alunos possam visualizar corretamente a execução dos movimentos.

#### Regras de Negócio

- Cada exercício pode conter uma imagem representada por uma URL.
- O sistema deve validar a URL antes de salvar.
- A imagem deve ser exibida automaticamente na visualização do exercício pelo aluno.

#### Tarefas

**Back-end/front-end :**

- Criar campo de URL de imagem na tabela de exercícios no banco de dados.
- Implementar validação de URL no cadastro/edição de exercícios.
- Exibir imagem nos painéis do instrutor e do aluno.

#### Critérios de Aceitação

- A imagem deve aparecer corretamente nos exercícios listados.
- URLs inválidas não devem ser salvas.
- A visualização deve ser otimizada para diferentes dispositivos.


