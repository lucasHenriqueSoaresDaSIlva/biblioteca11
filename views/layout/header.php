<?php
/**
 * Header - Layout padrão
 * 
 * Componente de cabeçalho compartilhado por todas as páginas.
 * Inclui a declaração HTML, meta tags, CSS e abertura do body.
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Escolar - Gerenciador de Acervo</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="cabecalho">
        <div class="container">
            <h1 class="logo">📚 Biblioteca Escolar</h1>
            <nav class="navegacao">
                <ul class="menu">
                    <li><a href="index.php?action=index" class="link-menu">Listar Livros</a></li>
                    <li><a href="index.php?action=create" class="link-menu btn-criar">+ Novo Livro</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="conteudo-principal">
        <div class="container">
