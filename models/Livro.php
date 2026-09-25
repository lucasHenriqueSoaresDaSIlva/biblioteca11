<?php
/**
 * Model Livro
 * 
 * Responsável por toda a interação com a tabela 'livros' no banco de dados.
 * Implementa operações CRUD (Create, Read, Update, Delete) e busca,
 * utilizando PDO com prepared statements para segurança.
 * 
 * Responsabilidades:
 * - Acesso aos dados da tabela livros
 * - Validação de regras de persistência
 * - Conversão entre dados do banco e objetos da aplicação
 * 
 * @author Desenvolvedor PHP
 * @version 1.0
 */

class Livro
{
    /**
     * Instância de conexão com banco de dados PDO
     * @var PDO
     */
    private PDO $db;

    /**
     * ID do livro
     * @var int|null
     */
    public ?int $id = null;

    /**
     * Título do livro
     * @var string
     */
    public string $titulo = '';

    /**
     * Autor do livro
     * @var string
     */
    public string $autor = '';

    /**
     * Gênero do livro
     * @var string
     */
    public string $genero = '';

    /**
     * Ano de publicação do livro
     * @var int
     */
    public int $ano_publicacao = 0;

    /**
     * Quantidade de exemplares disponíveis
     * @var int
     */
    public int $quantidade = 0;

    /**
     * Data de criação do registro
     * @var string|null
     */
    public ?string $created_at = null;

    /**
     * Data da última atualização do registro
     * @var string|null
     */
    public ?string $updated_at = null;

    /**
     * Construtor
     * 
     * Inicializa a conexão com o banco de dados
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php';
        $this->db = Database::getInstance();
    }

    /**
     * Retorna todos os livros cadastrados
     * 
     * @return array Array de objetos Livro ordenados por título
     */
    public function all(): array
    {
        try {
            $sql = 'SELECT * FROM livros ORDER BY titulo ASC';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $livros = [];
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $livros[] = $this->hydrateFromObject($row);
            }

            return $livros;
        } catch (PDOException $e) {
            error_log('Erro ao buscar livros: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um livro pelo ID
     * 
     * @param int $id ID do livro a buscar
     * @return Livro|null Objeto Livro se encontrado, null caso contrário
     */
    public function find(int $id): ?Livro
    {
        try {
            $sql = 'SELECT * FROM livros WHERE id = :id LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                return $this->hydrateFromObject($row);
            }

            return null;
        } catch (PDOException $e) {
            error_log('Erro ao buscar livro por ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cria um novo livro no banco de dados
     * 
     * @return bool True se o livro foi criado com sucesso, false caso contrário
     */
    public function create(): bool
    {
        try {
            $sql = 'INSERT INTO livros (titulo, autor, genero, ano_publicacao, quantidade, created_at, updated_at)
                    VALUES (:titulo, :autor, :genero, :ano_publicacao, :quantidade, NOW(), NOW())';

            $stmt = $this->db->prepare($sql);
            
            // Bindagem segura de parâmetros
            $stmt->bindParam(':titulo', $this->titulo, PDO::PARAM_STR);
            $stmt->bindParam(':autor', $this->autor, PDO::PARAM_STR);
            $stmt->bindParam(':genero', $this->genero, PDO::PARAM_STR);
            $stmt->bindParam(':ano_publicacao', $this->ano_publicacao, PDO::PARAM_INT);
            $stmt->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao criar livro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um livro existente no banco de dados
     * 
     * @return bool True se o livro foi atualizado com sucesso, false caso contrário
     */
    public function update(): bool
    {
        try {
            $sql = 'UPDATE livros 
                    SET titulo = :titulo, 
                        autor = :autor, 
                        genero = :genero, 
                        ano_publicacao = :ano_publicacao, 
                        quantidade = :quantidade,
                        updated_at = NOW()
                    WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            // Bindagem segura de parâmetros
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $this->titulo, PDO::PARAM_STR);
            $stmt->bindParam(':autor', $this->autor, PDO::PARAM_STR);
            $stmt->bindParam(':genero', $this->genero, PDO::PARAM_STR);
            $stmt->bindParam(':ano_publicacao', $this->ano_publicacao, PDO::PARAM_INT);
            $stmt->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar livro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta um livro do banco de dados
     * 
     * @param int $id ID do livro a deletar
     * @return bool True se o livro foi deletado com sucesso, false caso contrário
     */
    public function delete(int $id): bool
    {
        try {
            $sql = 'DELETE FROM livros WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao deletar livro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca livros por título ou autor
     * 
     * Realiza busca que consulta simultaneamente os campos título e autor.
     * A busca é case-insensitive e usa LIKE para busca parcial.
     * 
     * @param string $termo Termo de busca
     * @return array Array de objetos Livro encontrados
     */
    public function search(string $termo): array
    {
        try {
            // Remove espaços em branco desnecessários
            $termo = trim($termo);

            if (empty($termo)) {
                return $this->all();
            }

            $sql = 'SELECT * FROM livros 
                    WHERE LOWER(titulo) LIKE LOWER(:termo) 
                    OR LOWER(autor) LIKE LOWER(:termo)
                    ORDER BY titulo ASC';

            $stmt = $this->db->prepare($sql);
            
            // Adiciona wildcards para busca parcial de forma segura
            $termoBusca = '%' . $termo . '%';
            $stmt->bindParam(':termo', $termoBusca, PDO::PARAM_STR);
            $stmt->execute();

            $livros = [];
            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $livros[] = $this->hydrateFromObject($row);
            }

            return $livros;
        } catch (PDOException $e) {
            error_log('Erro ao buscar livros: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Carrega os dados de um objeto stdClass em propriedades da classe
     * 
     * Converte dados retornados do banco de dados em instância da classe Livro.
     * 
     * @param object $row Objeto com dados retornados pelo banco de dados
     * @return Livro Instância de Livro com dados preenchidos
     */
    private function hydrateFromObject(object $row): Livro
    {
        $livro = new Livro();
        $livro->id = (int)$row->id;
        $livro->titulo = $row->titulo;
        $livro->autor = $row->autor;
        $livro->genero = $row->genero;
        $livro->ano_publicacao = (int)$row->ano_publicacao;
        $livro->quantidade = (int)$row->quantidade;
        $livro->created_at = $row->created_at;
        $livro->updated_at = $row->updated_at;

        return $livro;
    }

    /**
     * Retorna o status de disponibilidade do livro
     * 
     * @return string "Disponível" se quantidade > 0, "Indisponível" caso contrário
     */
    public function getStatus(): string
    {
        return $this->quantidade > 0 ? 'Disponível' : 'Indisponível';
    }
}
