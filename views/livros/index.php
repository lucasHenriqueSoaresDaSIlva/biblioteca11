<?php
/**
 * View: Listagem de Livros
 * 
 * Exibe todos os livros cadastrados em formato de tabela.
 * Permite busca por título ou autor, edição, exclusão e visualização de detalhes.
 * 
 * Variáveis disponibilizadas pelo Controller:
 * - $livros: Array de objetos Livro
 * - $mensagem: Array com 'mensagem' e 'tipo' ou null
 */

// Inclui o header do layout
require_once __DIR__ . '/../layout/header.php';
?>

<section class="secao-principal">
    <div class="titulo-secao">
        <h2>Acervo da Biblioteca</h2>
        <p class="subtitulo">Consulte, edite ou remova livros do acervo</p>
    </div>

    <?php
    // Exibe mensagem flash se existir
    if ($mensagem !== null):
        $classe = 'alerta-' . htmlspecialchars($mensagem['tipo']);
    ?>
        <div class="alerta <?php echo $classe; ?>">
            <button class="fechar-alerta" onclick="this.parentElement.style.display='none';">&times;</button>
            <?php echo htmlspecialchars($mensagem['mensagem']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulário de busca -->
    <div class="caixa-busca">
        <form method="GET" action="index.php" class="formulario-busca">
            <input type="hidden" name="action" value="buscar">
            <div class="campo-busca">
                <input 
                    type="text" 
                    name="termo" 
                    placeholder="Buscar por título ou autor..." 
                    class="input-busca"
                    value="<?php echo isset($_GET['termo']) ? htmlspecialchars($_GET['termo']) : ''; ?>"
                >
                <button type="submit" class="btn btn-primario">🔍 Buscar</button>
                <a href="index.php?action=index" class="btn btn-secundario">Limpar Busca</a>
            </div>
        </form>
    </div>

    <!-- Tabela de livros -->
    <?php if (empty($livros)): ?>
        <div class="mensagem-vazia">
            <p>Nenhum livro encontrado no acervo.</p>
            <a href="index.php?action=create" class="btn btn-primario">Cadastrar Primeiro Livro</a>
        </div>
    <?php else: ?>
        <div class="tabela-responsiva">
            <table class="tabela">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Gênero</th>
                        <th>Ano</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livros as $livro): ?>
                        <tr class="linha-livro">
                            <td class="titulo-livro"><?php echo htmlspecialchars($livro->titulo); ?></td>
                            <td><?php echo htmlspecialchars($livro->autor); ?></td>
                            <td><?php echo htmlspecialchars($livro->genero); ?></td>
                            <td class="ano"><?php echo htmlspecialchars($livro->ano_publicacao); ?></td>
                            <td class="quantidade"><?php echo htmlspecialchars($livro->quantidade); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $livro->getStatus() === 'Disponível' ? 'sucesso' : 'aviso'; ?>">
                                    <?php echo htmlspecialchars($livro->getStatus()); ?>
                                </span>
                            </td>
                            <td class="acoes">
                                <a href="index.php?action=edit&id=<?php echo $livro->id; ?>" class="btn btn-pequeno btn-editar">Editar</a>
                                <form 
                                    method="POST" 
                                    action="index.php?action=delete" 
                                    class="formulario-delete"
                                    onsubmit="return confirm('Tem certeza que deseja remover este livro?');"
                                >
                                    <input type="hidden" name="id" value="<?php echo $livro->id; ?>">
                                    <button type="submit" class="btn btn-pequeno btn-deletar">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="resumo">
            <p><strong><?php echo count($livros); ?> livro(s)</strong> encontrado(s) no acervo.</p>
        </div>
    <?php endif; ?>
</section>

<?php
// Inclui o footer do layout
require_once __DIR__ . '/../layout/footer.php';
?>
