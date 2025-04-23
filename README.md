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
| RF-1      | Perfil do Instrutor (RF - 1) | perfil do instrutor         | Samuel/Hott         |                |                  |                | Em andamento   | Implementação do perfil do usuário                |
| RF-2      | Criação do Plano de Treino (RF - 2)                    | Formulário de criação| Mauricio/Heitor       |                |                  |                | Em andamento   | implementação do plano de treino para o user                               |
| RF-4      | Visualização dos Planos de Treino (RF - 3)                 | Interface de Usuário (UI)   | Sophia/Samuel      |                |                  |                | Em andamento   | Exibir dados deo plano de treino da academia.                        |
| RF-5      | Criação do Formulário de Treino (RF - 4)             | Solicitação de Treino       | Hallef Kayk    |                |                  |                | Em andamento      | Permitir ao aluno solicitar treino personalizado.                     |

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
| RF-1      | Cadastro de novo usuário (Aluno, Instrutor, ADM) | Cadastro de Usuário       |   Samuel e Sophia        |                |                  |                | Em andamento   | Implementar com campos obrigatórios e tipo de usuário.                |
| RF-2      | Recuperação de senha/conta                    | Funcionalidade de Recuperação | Mauricio       |                |                  |                | Em andamento   | Envio de email com link de redefinição.                               |
| RF-4      | Visualizar Perfis do sistema                 | Interface de Usuário (UI)   | João Hott      |                |                  |                | Em andamento   | Exibir dados de usuários e perfil da academia.                        |
| RF-5      | Criação do Formulário de Treino              | Solicitação de Treino       | Hallef Kayk    |                |                  |                | Planejado      | Permitir ao aluno solicitar treino personalizado.                     |
___

### Sistema de cadastro de usuários(RF - 5)
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

### Recuperação de senha (RF - 6)
**como** um usuário cadastrado **eu quero** recuperar o acesso à minha conta caso eu esqueça minha senha,
para que eu possa redefinir minha senha com segurança e continuar utilizando o sistema.

#### Regra de Negócio
- O sistema deve permitir a recuperação de senha por meio do email cadastrado.
- O link de recuperação deve ter tempo de expiração e ser enviado para o email correspondente.
- A redefinição só será possível após validação do token de recuperação enviado por email.

#### Tarefas:
**backend/FrontEnd:**

- Implementar endpoint para solicitação de recuperação de senha (Mauricio)
- Gerar token temporário e enviar email com link de redefinição (Mauricio)
- Criar página de redefinição de senha com validação de token (Mauricio)

#### Critérios de aceitação:
- O usuário deve receber um email com um link único de recuperação.
- O link deve expirar após um determinado tempo (ex: 1 hora).
- O usuário deve conseguir redefinir sua senha com sucesso ao acessar o link.
___

### Visualizar Perfis no Sistema (RF - 7)
**como** um aluno, instrutor ou visitante do sistema
**eu quero** visualizar os perfis correspondentes
para que eu possa acompanhar informações relevantes à minha função ou à academia.

#### Regras de Negócio
**Perfil do Aluno:**
- Deve exibir informações pessoais: nome, email, plano atual e histórico de treinos.
- Deve ser acessível apenas pelo próprio aluno, após login.

**Perfil do Instrutor:**
- Deve exibir informações pessoais do instrutor.
- Deve mostrar a lista de alunos vinculados e treinos criados.
- Acesso restrito ao próprio instrutor logado.

**Perfil da Academia:**
- Deve exibir informações institucionais: nome da academia, endereço, horário de funcionamento, planos disponíveis e contatos.
- Pode ser acessado por qualquer tipo de usuário, inclusive visitantes sem login.

#### Tarefas:
**Backend/Frontend (João Hott):**

-Criar endpoint para retorno dos dados de perfil do aluno.
-Criar endpoint para retorno dos dados de perfil do instrutor.
-Criar endpoint para retorno das informações institucionais da academia.
-Criar interface para exibição do perfil do aluno.
-Criar interface para exibição do perfil do instrutor.
-Criar interface pública para exibição do perfil da academia.
-Aplicar filtros de exibição com base no tipo de usuário.

#### Critérios de Aceitação:
**Aluno:**
- Deve visualizar corretamente: nome, email, plano atual e histórico de treinos.

**Instrutor:**
- Deve visualizar corretamente: seus dados e a lista de alunos vinculados.

**Academia:**
- Deve exibir corretamente: nome, endereço, horário de funcionamento, planos e contatos.
- O conteúdo exibido deve respeitar o nível de permissão do usuário.
- O acesso aos perfis pessoais (aluno e instrutor) deve exigir autenticação.
- A visualização do perfil da academia deve estar disponível publicamente.
___





