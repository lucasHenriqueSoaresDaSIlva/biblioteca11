# 📚 Biblioteca Escolar - Sistema de Gerenciamento de Acervo

## Visão Geral

A **Biblioteca Escolar** é uma aplicação web desenvolvida em **PHP puro** (sem frameworks) seguindo rigorosamente o **padrão de arquitetura MVC (Model-View-Controller)**. O sistema substitui o controle manual de um acervo que era mantido em caderno físico por um sistema digital confiável com persistência em banco de dados MySQL.

A aplicação foi projetada com foco em **segurança**, **performance** e **usabilidade**. Toda interação com o banco de dados utiliza **PDO com prepared statements** para prevenir SQL Injection, todas as saídas HTML são escapadas com `htmlspecialchars()` para prevenir XSS, e a validação de dados ocorre tanto no cliente quanto no servidor. O frontend é responsivo, acessível e amigável aos usuários.

A arquitetura é estrita: o Model é responsável apenas pelo acesso a dados, o Controller coordena requisições e renderiza views, e as Views são responsáveis somente pela apresentação HTML. Todas as requisições passam por um Front Controller em `public/index.php` com roteamento simples baseado em parâmetro de ação.

## Estrutura de Arquivos

```
biblioteca/
├── config/
│   └── database.php          # Configuração de conexão PDO com padrão Singleton
├── controllers/
│   └── LivroController.php    # Controller com ações: index, create, store, edit, update, delete, buscar
├── models/
│   └── Livro.php             # Model com métodos: all, find, create, update, delete, search
├── views/
│   ├── layout/
│   │   ├── header.php        # Cabeçalho compartilhado de todas as páginas
│   │   └── footer.php        # Rodapé compartilhado de todas as páginas
│   └── livros/
│       ├── index.php         # Listagem de livros com busca
│       ├── create.php        # Formulário para cadastro de novo livro
│       ├── edit.php          # Formulário para edição de livro existente
│       └── show.php          # (Reservado para visualização de detalhes)
├── public/
│   ├── index.php             # Front Controller - ponto de entrada da aplicação
│   └── css/
│       └── style.css         # Estilos CSS responsivos e acessíveis
├── database/
│   └── schema.sql            # Script SQL para criação do banco e tabelas
└── README.md                 # Este arquivo
```

## Funcionalidades Principais

### ✅ Listar Livros
- Exibe todos os livros cadastrados em formato de tabela
- Mostra: título, autor, gênero, ano de publicação, quantidade e status de disponibilidade
- Status "Disponível" quando quantidade > 0, "Indisponível" quando = 0
- Acesso direto às ações de edição e remoção

### ✅ Cadastrar Livro
- Formulário completo com campos: título, autor, gênero, ano de publicação, quantidade
- Validação no lado do servidor com mensagens de erro próximas aos campos
- Feedback visual claro de sucesso após cadastro
- Redirecionamento automático para listagem

### ✅ Editar Livro
- Formulário pré-preenchido com dados atuais do livro
- Mesmas validações do cadastro
- Exibição de informações de auditoria (data de criação e última atualização)
- Redirecionamento com mensagem de sucesso

### ✅ Remover Livro
- Confirmação explícita do usuário via `confirm()` JavaScript
- Verificação no lado do servidor para segurança
- Mensagem de sucesso após remoção

### ✅ Buscar Livro
- Campo de busca único que consulta simultaneamente título e autor
- Busca case-insensitive com LIKE para parciais
- Resultado dinâmico exibido na mesma listagem
- Opção para limpar a busca

## Regras de Validação

- ✓ **Título**: Não pode estar em branco
- ✓ **Autor**: Não pode estar em branco
- ✓ **Gênero**: Não pode estar em branco
- ✓ **Ano de Publicação**: Não pode ser maior que o ano atual (validação dinâmica)
- ✓ **Quantidade**: Não pode ser negativa (≥ 0)

## Detalhes de Implementação

### Segurança
- ✅ PDO com prepared statements em todas as operações
- ✅ Validação de entrada com `filter_input()`
- ✅ Escapagem de saída com `htmlspecialchars()`
- ✅ Tratamento de exceções PDO com try/catch
- ✅ Validação CSRF implícita via método POST

### Performance
- ✅ Índices no banco de dados nos campos de busca (título, autor, ano)
- ✅ Índice full-text para busca eficiente
- ✅ Singleton para conexão de banco de dados
- ✅ Consultas otimizadas

### Acessibilidade
- ✅ HTML semântico
- ✅ Labels associadas aos inputs
- ✅ Navegação por teclado
- ✅ Contraste adequado de cores
- ✅ Alertas visíveis e focáveis

## Instalação e Configuração

### Pré-requisitos
- PHP 7.4+ com suporte a PDO para MySQL
- MySQL 5.7+ (ou MariaDB equivalente)
- Servidor web (Apache com mod_rewrite, Nginx, etc.)
- Ambiente XAMPP, LAMP, ou equivalente

### Passo 1: Preparar o Ambiente

1. **Clonar ou copiar os arquivos** para o diretório web:
   ```bash
   # Para XAMPP (Windows)
   C:\xampp\htdocs\biblioteca\

   # Para LAMP (Linux)
   /var/www/html/biblioteca/

   # Para macOS
   /Library/WebServer/Documents/biblioteca/
   ```

2. **Verificar permissões** das pastas (especialmente se usando Linux):
   ```bash
   chmod -R 755 /caminho/para/biblioteca
   chmod -R 755 /caminho/para/biblioteca/public
   ```

### Passo 2: Criar o Banco de Dados

1. **Abrir o phpMyAdmin** (via XAMPP):
   - Acesse: `http://localhost/phpmyadmin`
   - Faça login com credenciais padrão (usuário: `root`, sem senha)

2. **Executar o script SQL**:
   - Clique em "SQL" no menu superior
   - Copie todo o conteúdo do arquivo `database/schema.sql`
   - Cole no campo de SQL e clique em "Executar"
   - O banco `biblioteca` será criado com a tabela `livros` e dados de exemplo

**Alternativa via linha de comando:**
```bash
mysql -u root -p < database/schema.sql
```

### Passo 3: Configurar Credenciais do Banco de Dados

Edite o arquivo `config/database.php` e atualize as credenciais se necessário:

```php
$host = 'localhost';      // Endereço do servidor MySQL
$dbname = 'biblioteca';   // Nome do banco de dados
$username = 'root';       // Usuário MySQL
$password = '';           // Senha MySQL (padrão: vazio no XAMPP)
$port = 3306;             // Porta MySQL padrão
```

### Passo 4: Acessar a Aplicação

1. **Inicie o servidor**:
   - XAMPP: Clique no botão "Start" ao lado de "Apache"
   - LAMP: Use `sudo service apache2 start` ou equivalente
   - Nginx: Configure um virtual host apontando para `public/`

2. **Acesse a aplicação** no navegador:
   - `http://localhost/biblioteca/public/`
   - Será exibida a listagem de livros com os dados de exemplo

## Uso do Sistema

### 🏠 Página Inicial (Listagem)
- **URL**: `http://localhost/biblioteca/public/index.php?action=index`
- Exibe todos os livros do acervo
- Buscar por título ou autor usando o campo de busca
- Editar ou remover livros com os botões de ação

### ➕ Cadastrar Novo Livro
- **URL**: `http://localhost/biblioteca/public/index.php?action=create`
- Preencha o formulário com os dados do livro
- Clique em "Cadastrar Livro"
- Será redirecionado para a listagem com mensagem de sucesso

### ✏️ Editar Livro Existente
- Na listagem, clique no botão "Editar" da linha do livro
- **URL**: `http://localhost/biblioteca/public/index.php?action=edit&id=1`
- Formulário vem pré-preenchido com dados atuais
- Modifique os dados desejados
- Clique em "Atualizar Livro"

### 🗑️ Remover Livro
- Na listagem, clique no botão "Remover" da linha do livro
- Confirme a exclusão no diálogo que será exibido
- O livro será removido e será exibida mensagem de sucesso

### 🔍 Buscar Livro
- Use o campo "Buscar por título ou autor..." na listagem
- Digite parte do título ou nome do autor
- Clique em "Buscar" ou pressione Enter
- Resultados são exibidos na mesma página
- Clique em "Limpar Busca" para retornar à listagem completa

## Estrutura do Código

### Padrão MVC

#### Model (`models/Livro.php`)
Responsável por:
- Acesso aos dados da tabela `livros`
- Métodos CRUD: `all()`, `find()`, `create()`, `update()`, `delete()`
- Busca: `search(termo)`
- Hidratação de objetos: `hydrateFromObject()`
- Status de disponibilidade: `getStatus()`

#### Controller (`controllers/LivroController.php`)
Responsável por:
- Receber requisições HTTP
- Validar dados de entrada
- Chamar métodos do Model
- Renderizar Views com dados apropriados
- Gerenciar mensagens flash
- Ações: `index()`, `create()`, `store()`, `edit()`, `update()`, `delete()`, `buscar()`

#### Views (`views/livros/` e `views/layout/`)
Responsável por:
- Apresentação HTML dos dados
- Formulários com layout consistente
- Exibição de mensagens de erro/sucesso
- Sem nenhuma lógica de negócio

#### Front Controller (`public/index.php`)
Responsável por:
- Receber todas as requisições
- Rotear para o Controller apropriado
- Inicializar componentes
- Tratar exceções globais

### Fluxo de Requisição

```
Requisição HTTP
      ↓
Front Controller (public/index.php)
      ↓
LivroController (coordena a requisição)
      ↓
Livro Model (acessa dados)
      ↓
View (renderiza HTML)
      ↓
Resposta HTTP
```

## Exemplos de Uso

### Criar um novo livro
```php
$livro = new Livro();
$livro->titulo = "Meu Livro";
$livro->autor = "Meu Autor";
$livro->genero = "Ficção";
$livro->ano_publicacao = 2024;
$livro->quantidade = 5;
$livro->create();  // Retorna true/false
```

### Buscar um livro pelo ID
```php
$livro = new Livro();
$livroEncontrado = $livro->find(1);  // Retorna objeto Livro ou null
```

### Listar todos os livros
```php
$livro = new Livro();
$livros = $livro->all();  // Retorna array de objetos Livro
foreach ($livros as $l) {
    echo $l->titulo . " - " . $l->getStatus();
}
```

### Buscar livros
```php
$livro = new Livro();
$resultados = $livro->search("Harry");  // Busca por título ou autor
```

## Tratamento de Erros

### Validação de Entrada
- Realizada no lado do servidor antes de persistir dados
- Mensagens de erro exibidas próximas ao campo problemático
- Sem uso de alertas genéricos do JavaScript

### Tratamento de Exceções
- Todas as operações de banco de dados estão em try/catch
- PDOException capturada e logged
- Usuário vê mensagens amigáveis, não detalhes técnicos

### Mensagens Flash
- Sistema de mensagens que persiste entre requisições via sessão
- Exibido uma única vez e removido da sessão
- Tipos: sucesso, erro, aviso, info

## Melhorias Futuras Recomendadas

### 🔐 Autenticação e Autorização
- Login de usuários (bibliotecário, aluno, admin)
- Controle de permissões por papel
- Auditoria de quem criou/modificou cada livro
- Histórico de alterações

### 📖 Paginação
- Implementar paginação na listagem de livros
- Suportar diferentes números de registros por página
- Links para navegação entre páginas

### 🔔 Empréstimos e Devoluções
- Sistema de empréstimos de livros por alunos
- Registrar data de empréstimo e devolução esperada
- Alertas para atrasos
- Histórico de empréstimos por livro

### 📊 Relatórios e Estatísticas
- Relatório de livros disponíveis vs. indisponíveis
- Estatísticas de gêneros mais populares
- Livros mais emprestados
- Exportar dados para Excel/PDF

### 🏷️ Categorias e Tags
- Organizar livros por categorias
- Adicionar tags para melhor classificação
- Filtrar por categoria/tags

### 📝 Avaliações e Comentários
- Alunos podem avaliar livros
- Sistema de estrelas (1-5)
- Comentários sobre livros

### 🔍 Busca Avançada
- Filtros por múltiplos campos
- Busca por intervalo de anos
- Busca por quantidade mínima de exemplares

### 📧 Notificações
- Email quando livro retorna ao acervo
- Notificação de atrasos em devoluções
- Avisos de novas aquisições

### 🗄️ Backup Automático
- Agendamento de backups do banco de dados
- Restauração de dados
- Versionamento de dados

### 📱 API REST
- Desenvolver API REST para integração com apps mobile
- Endpoints para operações CRUD
- Autenticação via tokens JWT

### 🎨 Tema Escuro
- Alternância entre tema claro e escuro
- Preferência salva no local storage
- Melhor experiência noturna

## Documentação Técnica

### Dependências
- **PHP**: 7.4+
- **MySQL**: 5.7+ / MariaDB 10.3+
- **PDO**: Extensão PHP para MySQL

### Conformidade
- **PSR-12**: Padrão de código PHP
- **HTML5**: Semântico e válido
- **CSS3**: Responsivo e moderno
- **WCAG 2.1**: Acessibilidade básica

### Segurança
- ✅ Proteção contra SQL Injection (prepared statements)
- ✅ Proteção contra XSS (htmlspecialchars)
- ✅ Validação de entrada
- ✅ Tratamento de exceções
- ⚠️ HTTPS recomendado em produção
- ⚠️ Rate limiting não implementado (recomendado para produção)

## Suporte

Para dúvidas ou problemas:

1. **Verifique os logs do PHP** em `php_errors.log`
2. **Verifique o console de erros do navegador** (F12 → Console)
3. **Verifique permissões** das pastas e arquivos
4. **Verifique credenciais do banco de dados** em `config/database.php`
5. **Verifique se o banco foi criado** via phpMyAdmin

## Licença

Este projeto é fornecido como-é para fins educacionais. Sinta-se livre para usar, modificar e distribuir conforme necessário.

---

**Desenvolvido com ❤️ em PHP puro**

*Última atualização: Setembro de 2026*
#   b i b l i o t e c a 1 1  
 