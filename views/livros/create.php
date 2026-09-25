<?php
/**
 * View: Criar Livro
 * 
 * Exibe o formulário para cadastrar um novo livro.
 * Valida os dados no lado do servidor e exibe erros próximos aos campos.
 * 
 * Variáveis disponibilizadas pelo Controller:
 * - $livro: null (para novo livro)
 * - $erros: Array com mensagens de erro por campo
 * - $modo: 'criar' ou 'editar'
 */

// Inclui o header do layout
require_once __DIR__ . '/../layout/header.php';
?>

<section class="secao-formulario">
    <div class="titulo-secao">
        <h2>Cadastrar Novo Livro</h2>
        <p class="subtitulo">Preencha as informações do livro para adicioná-lo ao acervo</p>
    </div>

    <form method="POST" action="index.php?action=store" class="formulario-livro" novalidate>
        <!-- Campo: Título -->
        <div class="grupo-formulario <?php echo isset($erros['titulo']) ? 'com-erro' : ''; ?>">
            <label for="titulo" class="label">Título do Livro *</label>
            <input 
                type="text" 
                id="titulo" 
                name="titulo" 
                class="input"
                placeholder="Ex: O Pequeno Príncipe"
                maxlength="255"
                required
                value="<?php echo isset($livro) && $livro !== null ? htmlspecialchars($livro->titulo) : ''; ?>"
            >
            <?php if (isset($erros['titulo'])): ?>
                <span class="mensagem-erro">⚠️ <?php echo htmlspecialchars($erros['titulo']); ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Autor -->
        <div class="grupo-formulario <?php echo isset($erros['autor']) ? 'com-erro' : ''; ?>">
            <label for="autor" class="label">Autor *</label>
            <input 
                type="text" 
                id="autor" 
                name="autor" 
                class="input"
                placeholder="Ex: Antoine de Saint-Exupéry"
                maxlength="255"
                required
                value="<?php echo isset($livro) && $livro !== null ? htmlspecialchars($livro->autor) : ''; ?>"
            >
            <?php if (isset($erros['autor'])): ?>
                <span class="mensagem-erro">⚠️ <?php echo htmlspecialchars($erros['autor']); ?></span>
            <?php endif; ?>
        </div>

        <!-- Campo: Gênero -->
        <div class="grupo-formulario <?php echo isset($erros['genero']) ? 'com-erro' : ''; ?>">
            <label for="genero" class="label">Gênero *</label>
            <input 
                type="text" 
                id="genero" 
                name="genero" 
                class="input"
                placeholder="Ex: Ficção Infantil"
                maxlength="100"
                required
                value="<?php echo isset($livro) && $livro !== null ? htmlspecialchars($livro->genero) : ''; ?>"
            >
            <?php if (isset($erros['genero'])): ?>
                <span class="mensagem-erro">⚠️ <?php echo htmlspecialchars($erros['genero']); ?></span>
            <?php endif; ?>
        </div>

        <div class="linha-campos">
            <!-- Campo: Ano de Publicação -->
            <div class="grupo-formulario <?php echo isset($erros['ano_publicacao']) ? 'com-erro' : ''; ?>">
                <label for="ano_publicacao" class="label">Ano de Publicação *</label>
                <input 
                    type="number" 
                    id="ano_publicacao" 
                    name="ano_publicacao" 
                    class="input"
                    placeholder="<?php echo date('Y'); ?>"
                    min="1000"
                    max="<?php echo date('Y'); ?>"
                    required
                    value="<?php echo isset($livro) && $livro !== null ? htmlspecialchars($livro->ano_publicacao) : ''; ?>"
                >
                <?php if (isset($erros['ano_publicacao'])): ?>
                    <span class="mensagem-erro">⚠️ <?php echo htmlspecialchars($erros['ano_publicacao']); ?></span>
                <?php endif; ?>
            </div>

            <!-- Campo: Quantidade -->
            <div class="grupo-formulario <?php echo isset($erros['quantidade']) ? 'com-erro' : ''; ?>">
                <label for="quantidade" class="label">Quantidade de Exemplares *</label>
                <input 
                    type="number" 
                    id="quantidade" 
                    name="quantidade" 
                    class="input"
                    placeholder="0"
                    min="0"
                    required
                    value="<?php echo isset($livro) && $livro !== null ? htmlspecialchars($livro->quantidade) : '0'; ?>"
                >
                <?php if (isset($erros['quantidade'])): ?>
                    <span class="mensagem-erro">⚠️ <?php echo htmlspecialchars($erros['quantidade']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Botões de ação -->
        <div class="grupo-botoes">
            <button type="submit" class="btn btn-primario btn-salvar">✓ Cadastrar Livro</button>
            <a href="index.php?action=index" class="btn btn-secundario">← Cancelar</a>
        </div>

        <p class="nota">* Campos obrigatórios</p>
    </form>
</section>

<?php
// Inclui o footer do layout
require_once __DIR__ . '/../layout/footer.php';
?>
