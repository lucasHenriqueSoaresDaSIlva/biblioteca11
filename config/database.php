<?php
/**
 * Configuração de conexão com banco de dados MySQL
 * 
 * Implementa o padrão Singleton para garantir uma única instância de PDO
 * durante toda a execução da aplicação. Utiliza PDO para abstração de banco de dados
 * e prepared statements para segurança contra SQL Injection.
 * 
 * @author Desenvolvedor PHP
 * @version 1.0
 */

class Database
{
    /**
     * Instância singleton da conexão PDO
     * @var PDO|null
     */
    private static ?PDO $instance = null;

    /**
     * Construtor privado para prevenir instanciação direta
     */
    private function __construct()
    {
    }

    /**
     * Retorna a instância única de conexão com o banco de dados
     * 
     * Utiliza o padrão Singleton para garantir que apenas uma conexão
     * PDO seja criada durante toda a execução da aplicação.
     * 
     * @return PDO Instância de conexão com o banco de dados
     * @throws Exception Caso haja erro na conexão com o banco de dados
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                // Configurações de conexão
                $host = 'localhost';
                $dbname = 'biblioteca';
                $username = 'root';
                $password = '';
                $port = 3306;

                // String de conexão DSN (Data Source Name)
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

                // Criação da instância PDO com tratamento de erros
                self::$instance = new PDO(
                    $dsn,
                    $username,
                    $password,
                    [
                        // Define o modo de erro para exceções PDO
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        // Retorna os registros como objetos stdClass
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        // Emula prepared statements para melhor compatibilidade
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                // Registro de erro sem expor detalhes de banco de dados ao usuário
                error_log('Erro de conexão com banco de dados: ' . $e->getMessage());
                throw new Exception('Erro ao conectar com o banco de dados. Tente novamente mais tarde.');
            }
        }

        return self::$instance;
    }

    /**
     * Evita clonagem da instância singleton
     */
    private function __clone()
    {
    }

    /**
     * Evita desserialização da instância singleton
     */
    private function __wakeup()
    {
    }
}
