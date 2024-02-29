CREATE DATABASE cabeleleila_leila;

USE cabeleleila_leila;

CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    nomeCompleto VARCHAR(55) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivelAcesso int(2) DEFAULT 0
);

INSERT INTO cliente(cpf, nomeCompleto, telefone, email, senha, nivelAcesso)  /*Adicionando ADM (PADRAO) */
VALUES ('000.000.000','ADMINISTRADOR','(00) 00000-0000','adm@gmail.com', 'admin123', 1);

CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomeServico VARCHAR(25) NOT NULL,
    descricaoServico VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    imagem VARCHAR(255) DEFAULT '../View/Midia/produto.png'
);

INSERT INTO servicos(nomeServico, descricaoServico, valor)
VALUES ('Corte Masculino', 'Corte de cabelo masculino com lavagem e secagem inclusas.', 50.00),
       ('Corte Feminino', 'Corte de cabelo feminino com lavagem e secagem inclusas.', 80.00),
       ('Coloração', 'Aplicação de coloração no cabelo.', 120.00),
       ('Escova Progressiva', 'Tratamento capilar para alisamento e redução de volume dos fios.', 200.00),
       ('Manicure e Pedicure', 'Cuidados com as unhas das mãos e dos pés, incluindo corte, lixamento, hidratação e esmaltação.', 60.00),
       ('Manicure e Pedicure', 'Cuidados com as unhas das mãos e dos pés, incluindo corte, lixamento, hidratação e esmaltação.', 80.00),
       ('Design de Sobrancelhas', 'Modelagem das sobrancelhas para realçar o olhar.', 40.00),
       ('Maquiagem para Eventos', 'Aplicação de maquiagem profissional para eventos especiais, como casamentos, formaturas e festas.', 150.00),
       ('Tratamento Capilar', 'Tratamento intensivo para revitalização e hidratação dos fios, incluindo lavagem, aplicação de máscara capilar e massagem no couro cabeludo.', 120.00);



CREATE TABLE agendamento(
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    dia DATE NOT NULL,
    horario TIME NOT NULL,
    servico_id INT NOT NULL,
    status VARCHAR(25) DEFAULT 'Aberto',
    FOREIGN KEY (cliente_id) REFERENCES cliente(id),
    FOREIGN KEY (servico_id) REFERENCES servicos(id)
);


CREATE TABLE pedidos_Alteracao(
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    agendamento_id INT,
    alteracao VARCHAR(100) NOT NULL,
    motivo VARCHAR(150) NOT NULL,
    status VARCHAR(25) DEFAULT 'Em Processo',
    FOREIGN KEY (cliente_id) REFERENCES cliente(id),
    FOREIGN KEY (agendamento_id) REFERENCES agendamento(id)
);
