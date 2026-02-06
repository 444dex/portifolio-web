# Portfólio Web

> ⚠️ **IMPORTANTE:** Este é um **projeto acadêmico** desenvolvido como trabalho de Desenvolvimento Web no Ensino Médio. O conteúdo aqui apresentado é **meramente demonstrativo** e serve apenas para avaliar habilidades técnicas de criação de sites. **Não deve ser considerado como portfólio profissional real**.

---

## Sobre o Projeto

Este repositório contém um **site portfólio fictício** criado como atividade avaliativa da disciplina de **Desenvolvimento Web** do Ensino Médio. 

### Objetivo Pedagógico

Demonstrar conhecimentos adquiridos em:
- Estruturação de páginas HTML
- Estilização com CSS
- Programação server-side com PHP
- Integração com banco de dados MySQL
- Formulários e validação de dados
- Design responsivo básico

### Avisos Importantes
- **Não profissional**: Este site **NÃO** representa um portfólio profissional real
- **Fins avaliativos**: Foi desenvolvido exclusivamente para avaliação escolar
- **Aprendizado**: O foco está na técnica de desenvolvimento, não no conteúdo

---

## Tecnologias Utilizadas

| Tecnologia | Uso no Projeto |
|------------|----------------|
| **HTML5** | Estrutura das páginas |
| **CSS3** | Estilização e layout responsivo |
| **PHP** | Backend e lógica server-side |
| **MySQL** | Banco de dados para formulário de contato |
| **JavaScript** | Interatividade básica |

---

## Estrutura do Projeto

```
portifolio-web/
├── index.html              # Página principal
├── admin.php               # Painel administrativo (demonstrativo)
├── save_contact.php        # Processa formulário de contato
├── view_contacts.php       # Visualiza mensagens recebidas
├── check_db.php            # Verifica conexão com banco
├── config.php              # Configurações do banco de dados
├── portfolio_db.sql        # Script SQL do banco
│
├── css/
│   └── style.css           # Estilos da página
│
├── imagens/
│   └── ...                 # Imagens do site
│
└── README.md
```

---

## Como Executar

### Pré-requisitos
- XAMPP, WAMP ou LAMP instalado
- Navegador web moderno

### Passo 1: Clone o Repositório
```bash
git clone https://github.com/444dex/portifolio-web.git
cd portifolio-web
```

### Passo 2: Configure o Servidor Local
```bash
# Copie os arquivos para o diretório do servidor
# XAMPP (Windows):
cp -r portifolio-web C:\xampp\htdocs\

# XAMPP (Linux/Mac):
cp -r portifolio-web /opt/lampp/htdocs/
```

### Passo 3: Configure o Banco de Dados
```bash
# Acesse o phpMyAdmin (http://localhost/phpmyadmin)
# Crie um banco chamado 'portfolio_db'
# Importe o arquivo portfolio_db.sql
```

Ou via terminal:
```bash
mysql -u root -p
CREATE DATABASE portfolio_db;
USE portfolio_db;
SOURCE portfolio_db.sql;
```

### Passo 4: Configure as Credenciais
Edite o arquivo `config.php`:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Sua senha do MySQL
define('DB_NAME', 'portfolio_db');
?>
```

### Passo 5: Acesse o Site
```
http://localhost/portifolio-web/
```

---

## Funcionalidades Desenvolvidas

### Páginas Implementadas
- [x] Página inicial (index.html)
- [x] Seção "Sobre"
- [x] Seção "Projetos"
- [x] Formulário de contato funcional
- [x] Painel admin para visualizar mensagens

### Recursos Técnicos
- [x] Layout responsivo básico
- [x] Formulário com validação PHP
- [x] Integração PHP + MySQL
- [x] Sistema de mensagens de contato
- [x] Painel administrativo simples

---

## Conceitos de Programação Aplicados

Este trabalho escolar demonstra conhecimento em:

### HTML
- Estrutura semântica de páginas
- Formulários (`<form>`, `<input>`, `<textarea>`)
- Links de navegação
- Organização de conteúdo

### CSS
- Estilização de elementos
- Flexbox/Grid básico
- Media queries (responsividade)
- Cores, fontes, espaçamentos

### PHP
- Processamento de formulários (`$_POST`)
- Conexão com banco de dados (mysqli)
- Validação de dados server-side
- Separação de arquivos (config, funções)

### MySQL
- Criação de tabelas
- Inserção de dados (INSERT)
- Consulta de dados (SELECT)
- Estruturação de banco de dados

---

## Critérios de Avaliação Atendidos

- Site funcional com múltiplas páginas
- Uso correto de HTML semântico
- Estilização com CSS
- Formulário funcional com PHP
- Integração com banco de dados
- Código organizado e comentado
- Layout responsivo básico

---

## Notas de Desenvolvimento

### Decisões Técnicas
- Optei por PHP puro ao invés de frameworks para demonstrar conhecimento fundamental
- Banco MySQL simples com uma tabela de contatos
- CSS vanilla sem pré-processadores
- JavaScript mínimo, focando no backend

### Aprendizados
- Integração frontend/backend
- Ciclo completo de formulário (HTML → PHP → MySQL)
- Organização de código em arquivos separados
- Boas práticas de segurança básica (validação)

---

## Possíveis Melhorias (Futuras)

Se eu fosse continuar desenvolvendo este projeto:

- [ ] Adicionar sistema de login/autenticação
- [ ] Implementar proteção CSRF
- [ ] Usar prepared statements (PDO) para evitar SQL Injection
- [ ] Adicionar paginação de mensagens
- [ ] Sistema de busca/filtro no admin
- [ ] Upload de imagens
- [ ] Validação JavaScript no frontend
- [ ] Animações CSS mais elaboradas

---

## Referências de Estudo

Recursos utilizados para desenvolver este trabalho:
- [MDN Web Docs](https://developer.mozilla.org/) - HTML, CSS, JavaScript
- [PHP.net](https://www.php.net/manual/pt_BR/) - Documentação oficial PHP
- [W3Schools](https://www.w3schools.com/) - Tutoriais e exemplos
- Material didático fornecido em aula

---

## Informações Acadêmicas

- **Disciplina:** Desenvolvimento Web
- **Nível:** Ensino Médio
- **Objetivo:** Trabalho avaliativo de programação web
- **Autor:** Miguel Erick Assunção Kuipers
- **GitHub:** [@444dex](https://github.com/444dex)

---

## Licença

Este projeto é de uso educacional. O código pode ser utilizado como referência para estudos.

---

<p align="center">
  <strong>Projeto desenvolvido como atividade escolar de Desenvolvimento Web</strong>
</p>

<p align="center">
  <sub>O conteúdo deste site é meramente demonstrativo e não reflete um portfólio profissional real</sub>

</p>

