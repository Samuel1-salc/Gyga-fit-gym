<?php
use PHPUnit\Framework\TestCase;

// Caminho correto para incluir Usuarios.class.php
require_once __DIR__ . '/../models/Usuarios.class.php';

class UserModelTest extends TestCase
{
    public function testCadastroUsuarioSucesso()
    {
        $usuarioModel = new Users(); // Corrigido para o nome correto da classe
        $dados = [
            'username' => 'testeunitario',
            'email' => 'testeunitario@example.com',
            'senha' => 'senhaSegura123',
            'typeUser' => 'aluno'
        ];
        $resultado = $usuarioModel->cadastrar($dados);
        print "[DEBUG] Resultado do cadastro de sucesso: ";
        var_dump($resultado);
        $this->assertTrue($resultado);
    }

    public function testCadastroUsuarioErro()
    {
        $usuarioModel = new Users(); // Corrigido para o nome correto da classe
        $dados = [
            'username' => '',
            'email' => 'emailinvalido',
            'senha' => '123',
            'typeUser' => 'tipoinexistente'
        ];
        $resultado = $usuarioModel->cadastrar($dados);
        print "[DEBUG] Resultado do cadastro com erro: ";
        var_dump($resultado);
        $this->assertFalse($resultado);
    }
}