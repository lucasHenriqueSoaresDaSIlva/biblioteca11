<?php
/**
 * Front Controller - Ponto de entrada da aplicação
 * 
 * Responsável por:
 * - Receber todas as requisições HTTP
 * - Rotear requisições para o Controller apropriado
 * - Inicializar componentes necessários
 * - Tratar erros e exceções
 * 
 * Roteamento simples baseado em parâmetro 'action' da query string.
 * Padrão: index.php?action=nomeDAcao
 * 
 * @author Desenvolvedor PHP
 * @version 1.0
 */

// Define o diretório raiz da aplicação
define('APP_ROOT', dirname(dirname(__FILE__)));

// Inclui a configuração do banco de dados
require_once APP_ROOT . '/config/database.php';

// Inclui o Controller
require_once APP_ROOT . '/controllers/LivroController.php';

try {
    // Obtém a ação solicitada, com padrão 'index'
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? 'index';

    // Lista de ações permitidas
    $acoesPermitidas = ['index', 'create', 'store', 'edit', 'update', 'delete', 'buscar'];

    // Valida se a ação é permitida
    if (!in_array($action, $acoesPermitidas, true)) {
        // Se a ação não for permitida, redireciona para index
        header('Location: index.php?action=index');
        exit;
    }

    // Cria instância do Controller
    $controller = new LivroController();

    // Chama o método correspondente à ação
    // O método é chamado dinamicamente conforme a ação
    $controller->$action();
} catch (Exception $e) {
    // Tratamento genérico de exceções
    error_log('Erro na aplicação: ' . $e->getMessage());

    // Redireciona para a página inicial com mensagem de erro
    $_SESSION['flash'] = [
        'mensagem' => 'Ocorreu um erro ao processar sua requisição. Tente novamente.',
        'tipo' => 'erro',
    ];
    header('Location: index.php?action=index');
    exit;
}
