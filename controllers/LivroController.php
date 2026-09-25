<?php
/**
 * Controller Livro
 * 
 * Responsável por coordenar as requisições do usuário com o Model e renderizar as Views.
 * Implementa as ações: index, create, store, edit, update, delete e search.
 * 
 * Responsabilidades:
 * - Receber requisições HTTP
 * - Validar dados de entrada
 * - Invocar métodos do Model
 * - Renderizar Views com dados apropriados
 * - Gerenciar redirecionamentos e mensagens flash
 * 
 * @author Desenvolvedor PHP
 * @version 1.0
 */

class LivroController
{
    /**
     * Instância do Model Livro
     * @var Livro
     */
    private Livro $livro;

    /**
     * Construtor
     * 
     * Inicializa o model e inicia a sessão para gerenciar mensagens flash
     */
    public function __construct()
    {
        require_once __DIR__ . '/../models/Livro.php';
        $this->livro = new Livro();

        // Inicia sessão para gerenciar mensagens flash
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Ação Index: exibe a listagem de todos os livros
     * 
     * Renderiza a view com todos os livros cadastrados, exibindo
     * título, autor, gênero, ano de publicação, quantidade e status.
     * Também exibe mensagens flash se existirem.
     */
    public function index(): void
    {
        $livros = $this->livro->all();
        $mensagem = $this->obterMensagemFlash();

        require_once __DIR__ . '/../views/livros/index.php';
    }

    /**
     * Ação Create: exibe o formulário para criar um novo livro
     * 
     * Renderiza o formulário vazio para que o usuário possa
     * cadastrar um novo livro.
     */
    public function create(): void
    {
        $livro = null;
        $erros = [];
        $modo = 'criar';

        require_once __DIR__ . '/../views/livros/create.php';
    }

    /**
     * Ação Store: processa o envio do formulário de criação
     * 
     * Valida os dados enviados, cria um novo livro no banco de dados
     * e redireciona com mensagem de sucesso ou exibe o formulário com erros.
     */
    public function store(): void
    {
        // Verifica se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=create');
            exit;
        }

        // Coleta e filtra dados do formulário
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $autor = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_STRING);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $ano_publicacao = filter_input(INPUT_POST, 'ano_publicacao', FILTER_VALIDATE_INT);
        $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

        // Valida os dados
        $erros = $this->validarLivro($titulo, $autor, $genero, $ano_publicacao, $quantidade);

        if (!empty($erros)) {
            // Se há erros, volta ao formulário exibindo-os
            $livro = null;
            $modo = 'criar';
            require_once __DIR__ . '/../views/livros/create.php';
            return;
        }

        // Prepara o objeto Livro para persistência
        $this->livro->titulo = $titulo;
        $this->livro->autor = $autor;
        $this->livro->genero = $genero;
        $this->livro->ano_publicacao = $ano_publicacao;
        $this->livro->quantidade = $quantidade;

        // Tenta salvar no banco de dados
        if ($this->livro->create()) {
            $this->definirMensagemFlash('Livro cadastrado com sucesso!', 'sucesso');
            header('Location: index.php?action=index');
            exit;
        } else {
            $this->definirMensagemFlash('Erro ao cadastrar livro. Tente novamente.', 'erro');
            $livro = null;
            $modo = 'criar';
            require_once __DIR__ . '/../views/livros/create.php';
        }
    }

    /**
     * Ação Edit: exibe o formulário para editar um livro existente
     * 
     * Recupera um livro pelo ID e exibe o formulário pré-preenchido
     * com os dados atuais do livro.
     */
    public function edit(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id === false || $id === null) {
            header('Location: index.php?action=index');
            exit;
        }

        $livro = $this->livro->find($id);

        if ($livro === null) {
            $this->definirMensagemFlash('Livro não encontrado.', 'erro');
            header('Location: index.php?action=index');
            exit;
        }

        $erros = [];
        $modo = 'editar';

        require_once __DIR__ . '/../views/livros/edit.php';
    }

    /**
     * Ação Update: processa o envio do formulário de edição
     * 
     * Valida os dados enviados, atualiza o livro no banco de dados
     * e redireciona com mensagem de sucesso ou exibe o formulário com erros.
     */
    public function update(): void
    {
        // Verifica se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=index');
            exit;
        }

        // Coleta e filtra dados do formulário
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $autor = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_STRING);
        $genero = filter_input(INPUT_POST, 'genero', FILTER_SANITIZE_STRING);
        $ano_publicacao = filter_input(INPUT_POST, 'ano_publicacao', FILTER_VALIDATE_INT);
        $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

        // Verifica se o ID é válido
        if ($id === false || $id === null) {
            header('Location: index.php?action=index');
            exit;
        }

        // Busca o livro no banco de dados
        $livro = $this->livro->find($id);
        if ($livro === null) {
            $this->definirMensagemFlash('Livro não encontrado.', 'erro');
            header('Location: index.php?action=index');
            exit;
        }

        // Valida os dados
        $erros = $this->validarLivro($titulo, $autor, $genero, $ano_publicacao, $quantidade);

        if (!empty($erros)) {
            // Se há erros, volta ao formulário exibindo-os
            $modo = 'editar';
            require_once __DIR__ . '/../views/livros/edit.php';
            return;
        }

        // Prepara o objeto Livro para persistência
        $this->livro->id = $id;
        $this->livro->titulo = $titulo;
        $this->livro->autor = $autor;
        $this->livro->genero = $genero;
        $this->livro->ano_publicacao = $ano_publicacao;
        $this->livro->quantidade = $quantidade;

        // Tenta atualizar no banco de dados
        if ($this->livro->update()) {
            $this->definirMensagemFlash('Livro atualizado com sucesso!', 'sucesso');
            header('Location: index.php?action=index');
            exit;
        } else {
            $this->definirMensagemFlash('Erro ao atualizar livro. Tente novamente.', 'erro');
            $modo = 'editar';
            require_once __DIR__ . '/../views/livros/edit.php';
        }
    }

    /**
     * Ação Delete: processa a exclusão de um livro
     * 
     * Verifica se é uma requisição POST e se o livro existe,
     * depois deleta do banco de dados e redireciona com mensagem de sucesso.
     */
    public function delete(): void
    {
        // Verifica se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=index');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id === false || $id === null) {
            header('Location: index.php?action=index');
            exit;
        }

        // Verifica se o livro existe antes de deletar
        $livro = $this->livro->find($id);
        if ($livro === null) {
            $this->definirMensagemFlash('Livro não encontrado.', 'erro');
            header('Location: index.php?action=index');
            exit;
        }

        // Tenta deletar do banco de dados
        if ($this->livro->delete($id)) {
            $this->definirMensagemFlash('Livro removido com sucesso!', 'sucesso');
            header('Location: index.php?action=index');
            exit;
        } else {
            $this->definirMensagemFlash('Erro ao remover livro. Tente novamente.', 'erro');
            header('Location: index.php?action=index');
            exit;
        }
    }

    /**
     * Ação Search: busca livros por título ou autor
     * 
     * Renderiza a listagem de livros filtrados conforme o termo de busca
     * fornecido pelo usuário.
     */
    public function buscar(): void
    {
        $termo = filter_input(INPUT_GET, 'termo', FILTER_SANITIZE_STRING);
        $livros = $this->livro->search($termo ?? '');

        require_once __DIR__ . '/../views/livros/index.php';
    }

    /**
     * Valida os dados de um livro
     * 
     * Realiza validação no lado do servidor conforme as regras de negócio:
     * - Título não pode estar vazio
     * - Autor não pode estar vazio
     * - Ano de publicação não pode ser maior que o ano atual
     * - Quantidade não pode ser negativa
     * 
     * @param string|null $titulo Título do livro
     * @param string|null $autor Autor do livro
     * @param string|null $genero Gênero do livro
     * @param int|null $ano_publicacao Ano de publicação
     * @param int|null $quantidade Quantidade de exemplares
     * @return array Array com mensagens de erro para cada campo inválido
     */
    private function validarLivro(
        ?string $titulo,
        ?string $autor,
        ?string $genero,
        ?int $ano_publicacao,
        ?int $quantidade
    ): array {
        $erros = [];

        // Validação de título
        if (empty($titulo)) {
            $erros['titulo'] = 'O título é obrigatório.';
        }

        // Validação de autor
        if (empty($autor)) {
            $erros['autor'] = 'O autor é obrigatório.';
        }

        // Validação de gênero
        if (empty($genero)) {
            $erros['genero'] = 'O gênero é obrigatório.';
        }

        // Validação de ano de publicação
        if ($ano_publicacao === null) {
            $erros['ano_publicacao'] = 'O ano de publicação é obrigatório.';
        } elseif ($ano_publicacao > (int)date('Y')) {
            $erros['ano_publicacao'] = 'O ano de publicação não pode ser maior que o ano atual.';
        }

        // Validação de quantidade
        if ($quantidade === null) {
            $erros['quantidade'] = 'A quantidade é obrigatória.';
        } elseif ($quantidade < 0) {
            $erros['quantidade'] = 'A quantidade não pode ser negativa.';
        }

        return $erros;
    }

    /**
     * Define uma mensagem flash na sessão
     * 
     * Mensagens flash são exibidas uma única vez e depois removidas da sessão.
     * 
     * @param string $mensagem Conteúdo da mensagem
     * @param string $tipo Tipo de mensagem: 'sucesso', 'erro', 'aviso', 'info'
     */
    private function definirMensagemFlash(string $mensagem, string $tipo = 'info'): void
    {
        $_SESSION['flash'] = [
            'mensagem' => $mensagem,
            'tipo' => $tipo,
        ];
    }

    /**
     * Obtém e remove uma mensagem flash da sessão
     * 
     * @return array|null Array com 'mensagem' e 'tipo' ou null se não houver
     */
    private function obterMensagemFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }

        return null;
    }
}
