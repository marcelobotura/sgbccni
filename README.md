# Sistema de Gestão da Biblioteca Comunitária - CNI

## Projeto: sgbccni

Este é um sistema completo de gerenciamento para uma biblioteca comunitária. Ele oferece controle de usuários, empréstimos, cadastro de livros, temas claro/escuro, painel administrativo e muito mais.

---

## 📁 Estrutura de Pastas

sgbccni/
├── public_html/ # Pasta pública acessível via navegador
│ ├── index.php # Página inicial protegida (usuário logado)
│ ├── login/
│ │ ├── index.php # Página de login
│ │ ├── register.php # Página de cadastro
│ │ └── logout.php # Logout de sessão
│ ├── assets/
│ │ ├── css/ # Estilos base e tema dark
│ │ ├── js/ # Scripts como toggle de senha e CEP
│ └── uploads/
│ ├── capas/ # Capas de livros (upload futuro)
│ └── perfis/ # Imagens de perfil dos usuários
│
├── admin/ # Painel administrativo
│ ├── index.php # Painel principal do admin
│ └── pages/ # Módulos de gerenciamento
│
├── config/
│ ├── config.php # Conexão com banco e sessão
│ └── constantes.php # Constantes globais (nome do sistema, URL base)
│
├── controllers/
│ └── auth.php # Processa login, cadastro e logout
│
├── includes/
│ ├── header.php # Cabeçalho padrão (com Bootstrap e temas)
│ ├── footer.php # Rodapé padrão com scripts e ativador de tema
│ └── menu.php # Menu superior com botão de alternância de tema
│
├── uploads/ # Diretório raiz para arquivos enviados
│
├── logs/ # Logs de erros PHP
│
└── sgbccni.sql # Dump do banco de dados MySQL (opcional)


---

## 🔧 Requisitos

- PHP 7.4+
- MySQL/MariaDB
- Servidor local (XAMPP, Laragon ou semelhante)

---

## 🚀 Como rodar o projeto

1. Importe o arquivo `sgbccni.sql` no seu banco de dados.
2. Ajuste as credenciais no arquivo:  
   `config/config.php`
3. Acesse via navegador:  
   `http://localhost/sgbccni/public_html/`

---

## 🌗 Tema claro/escuro

- Alternância de tema no botão "🌙 Tema"
- Preferência salva com `localStorage`
- Estilo leve e moderno com Bootstrap 5 e Bootstrap Icons

---

Se quiser, posso salvar esse novo `README.md` no seu projeto agora.

Deseja que eu atualize o arquivo real? ​:contentReference[oaicite:0]{index=0}​
