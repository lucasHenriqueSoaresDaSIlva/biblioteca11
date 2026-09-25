-- ========================================
-- Banco de Dados: biblioteca
-- ========================================
-- Script de criação do banco de dados e tabelas
-- para o sistema de gerenciamento de acervo
-- da biblioteca escolar.
-- ========================================

-- Criação do banco de dados (se não existir)
CREATE DATABASE IF NOT EXISTS biblioteca
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

-- Usa o banco de dados
USE biblioteca;

-- ========================================
-- Tabela: livros
-- ========================================
-- Armazena informações sobre os livros do acervo.
-- 
-- Campos:
-- - id: Identificador único do livro (chave primária)
-- - titulo: Título do livro (obrigatório)
-- - autor: Nome do autor (obrigatório)
-- - genero: Gênero literário (obrigatório)
-- - ano_publicacao: Ano de publicação do livro (obrigatório)
-- - quantidade: Número de exemplares disponíveis (padrão: 0)
-- - created_at: Data e hora de criação do registro
-- - updated_at: Data e hora da última atualização
-- ========================================

DROP TABLE IF EXISTS livros;

CREATE TABLE livros (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'ID único do livro',
    
    titulo VARCHAR(255) NOT NULL COMMENT 'Título do livro',
    
    autor VARCHAR(255) NOT NULL COMMENT 'Autor do livro',
    
    genero VARCHAR(100) NOT NULL COMMENT 'Gênero literário',
    
    ano_publicacao INT NOT NULL COMMENT 'Ano de publicação do livro',
    
    quantidade INT NOT NULL DEFAULT 0 COMMENT 'Quantidade de exemplares disponíveis',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data/hora de criação do registro',
    
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
               ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data/hora da última atualização',
    
    -- Índices para melhorar performance
    INDEX idx_titulo (titulo),
    INDEX idx_autor (autor),
    INDEX idx_ano_publicacao (ano_publicacao),
    
    -- Pesquisa por múltiplos campos
    FULLTEXT INDEX ft_titulo_autor (titulo, autor)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de livros do acervo da biblioteca escolar';

-- ========================================
-- Dados de exemplo (opcional)
-- ========================================
-- Descomente as linhas abaixo para popular
-- o banco com dados de teste.
-- ========================================

INSERT INTO livros (titulo, autor, genero, ano_publicacao, quantidade) VALUES
('O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 'Ficção Infantil', 1943, 5),
('Harry Potter e a Pedra Filosofal', 'J.K. Rowling', 'Fantasia', 1997, 3),
('O Senhor dos Anéis', 'J.R.R. Tolkien', 'Fantasia Épica', 1954, 2),
('1984', 'George Orwell', 'Ficção Científica', 1949, 4),
('O Hobbit', 'J.R.R. Tolkien', 'Fantasia', 1937, 2),
('Cem Anos de Solidão', 'Gabriel García Márquez', 'Realismo Mágico', 1967, 3),
('A Revolução dos Bichos', 'George Orwell', 'Fábula', 1945, 6),
('O Código Da Vinci', 'Dan Brown', 'Mistério/Thriller', 2003, 1),
('Flores para Algernon', 'Daniel Keyes', 'Ficção Científica', 1966, 2),
('O Cortiço', 'Aluísio Azevedo', 'Romance', 1890, 1);
