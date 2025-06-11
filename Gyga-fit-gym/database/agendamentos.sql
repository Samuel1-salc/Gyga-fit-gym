CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_aluno INT NOT NULL,
    id_instrutor INT NOT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    status ENUM('agendado', 'cancelado', 'concluido') DEFAULT 'agendado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_aluno) REFERENCES alunos(id),
    FOREIGN KEY (id_instrutor) REFERENCES instrutores(id)
);