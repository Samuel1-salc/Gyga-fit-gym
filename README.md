**Projeto - Site de Academia Gyga Fit**

Este projeto tem como objetivo a criação de um site para uma academia, desenvolvido como parte da disciplina de Engenharia de Software. O site permite que os usuários se registrem, visualizem planos de treino, acessem informações sobre os serviços da academia e realizem o login para gerenciar suas atividades. As funcionalidades foram divididas entre os membros do grupo e implementadas seguindo as melhores práticas de desenvolvimento de software.

- Funcionalidades

As principais funcionalidades do site são:

Cadastro de usuários: Os usuários podem criar uma conta para acessar o sistema.

Login: Permite que os usuários se autentiquem para acessar funcionalidades personalizadas.

Formulário de treino: Os usuários podem fazer o requerimento de um plano de treino.

Monitoramento: Após realizar um treino, o usuário pode especificar seu desempenho na pagina de monitoramento.

- Tecnologias Utilizadas
O projeto foi desenvolvido com as seguintes tecnologias:

Frontend:

HTML

CSS

JavaScript

Backend:

Php 

Banco de dados xampp

A estrutura de pastas do projeto é a seguinte:
/Gyga-Fit-Gym
  
  ├── /settings.json 
      
      ├── /.vscode  
  ├── /config               
      
      ├── /database.class.php     
  ├── /controllers
     
      ├── /processarCadastro.php         
      ├── /processarLogin.php       
      ├── /processarTelaPrincipal.php          
  
  ├── /models            
     
      ├── /User.class.php          
  ├── /view
     
      ├── /img
          ├── /bytes.png
          ├── /logo.png
      ├── /style
          ├── /Login-Cadastro.css
          ├── /Tela-Principal.css
      ├── /paginaFormulário.php
      ├── /paginaMonitoramento.php
      ├── /telaCadastro.php
      ├── /telaLogin.php
      ├── /telaPrincipal.php
  ├── /index.php
  
  └── README.md          

- Contribuições:

João Hott: Desenvolvimento das páginas da tela principal

Samuel Andrade:Desenvolvimento das páginas de cadastro e login

Hallef Kayk:Desenvolvimento do Banco de Dados

Heitor Fernandes:Desenvolvimento da tela de monitoramento

Maurício Monteiro:Desenvolvimento da tela de formulário
