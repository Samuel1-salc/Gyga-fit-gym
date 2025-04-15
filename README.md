# Escopo do Projeto Gyga Fit 

 

Universidade: 
Universidade Federal do Tocantins 

Curso:
Ciência da Computação 

Disciplina:
Engenharia de Software 

Semestre:
1º semestre de 2025 

Professor:
Edeílson Milhomem 

Integrantes do Projeto 
João Vitor Hott 
Samuel Andrade 
Hallef Kayk 
Heitor Fernandes 
Maurício Monteiro 
Sophia 

Descrição 

 

# Projeto - Site de Academia Gyga Fit 
 
**Este projeto tem como objetivo a criação de um site para uma academia, desenvolvido como parte da disciplina de Engenharia de Software. O site permite que os usuários se registrem, visualizem planos de treino, acessem informações sobre os serviços da academia e realizem o login para gerenciar suas atividades. As funcionalidades foram divididas entre os membros do grupo e implementadas seguindo as melhores práticas de desenvolvimento de software.** 


## SPRINT 1 – Sistema de Cadastro e Autenticação de Usuário 

**Descrição:**  Desenvolvimento das funcionalidades de autenticação e perfis dos diferentes tipos de usuários (Aluno, Instrutor e Gerente). 

| Código RF | Requisito Funcional                          | Tipo de Funcionalidade      | Responsável   | Data de Início | Data de Término | Duração (dias) | Status        | Notas                                                                 |
|-----------|----------------------------------------------|------------------------------|----------------|----------------|------------------|----------------|----------------|------------------------------------------------------------------------|
| RF-1      | Cadastro de novo usuário (Aluno, Instrutor, ADM) | Cadastro de Usuário          Samuel         |                |                  |                | Em andamento   | Implementar com campos obrigatórios e tipo de usuário.                |
| RF-2      | Recuperação de senha/conta                    | Funcionalidade de Recuperação | Mauricio       |                |                  |                | Em andamento   | Envio de email com link de redefinição.                               |
| RF-4      | Visualizar Perfis do sistema                 | Interface de Usuário (UI)   | João Hott      |                |                  |                | Em andamento   | Exibir dados de usuários e perfil da academia.                        |
| RF-5      | Criação do Formulário de Treino              | Solicitação de Treino       | Hallef Kayk    |                |                  |                | Planejado      | Permitir ao aluno solicitar treino personalizado.                     |
___

### Sistema de cadastro de usuários(RF - 1)
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

### Recuperação de senha (RF - 2)
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

### Visualizar Perfis no Sistema (RF - 3)
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

### Criação do Formulário de Treino (RF - 4)
**como** um aluno **eu quero** preencher um formulário solicitando um treino personalizado
para que o instrutor possa montar um plano de treino adequado às minhas necessidades e objetivos.

#### Regras de Negócio
- Apenas alunos autenticados poderão acessar o formulário de solicitação de treino.
- O formulário deve conter campos obrigatórios como: objetivo do treino, frequência semanal, restrições físicas e preferências.
- O instrutor responsável será notificado quando um novo formulário for enviado.
- Cada aluno pode enviar um novo formulário quando desejar atualizar suas preferências.

#### Tarefas
**Backend/Frontend (Hallef Kayk):**
- Criar endpoint para submissão de formulário de treino pelo aluno
- Criar interface com campos obrigatórios e opcionais para o formulário
- Armazenar os dados no banco e relacionar ao aluno
- Implementar notificação ou listagem de solicitações para os instrutores

**Critérios de Aceitação**
- O aluno deve conseguir acessar e preencher o formulário após login.
- O formulário deve validar campos obrigatórios antes do envio.
- O instrutor deve conseguir visualizar os dados enviados para criação do treino.
- Os dados devem ser armazenados corretamente no banco, associados ao aluno.
- O aluno deve conseguir reenviar um novo formulário caso deseje.
___




