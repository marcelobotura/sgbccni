
# Sistema de Gestão da Biblioteca Comunitária - CNI

Sistema completo de cadastro, gerenciamento e visualização de livros físicos e digitais com integração à API Google Books e geração de QR Codes.

## ✨ Funcionalidades Principais
- 📚 Cadastro e edição de livros
- 🔍 Busca por ISBN (Google Books + OpenLibrary)
- 📁 Upload de capas e geração de QR Code
- 🧠 Sistema de tags (autor, categoria, editora)
- 👤 Login de usuário e administrador
- 🗃️ Histórico, favoritos, sugestões e perfil do usuário
- 📈 Dashboard administrativo com estatísticas

## 🛠️ Tecnologias
- PHP 8.x
- MySQL
- Bootstrap 5
- JavaScript + Select2
- Composer + Endroid/QR-Code

## 📁 Estrutura do Projeto
```
/backend       # Lógica de negócios e controllers  
/frontend      # Telas visuais e assets (CSS/JS)  
/public_html   # Entrada pública do sistema  
/uploads       # Capas, perfis, QR Codes  
/vendor        # Bibliotecas via Composer
```

## 📱 Responsividade
Compatível com:
- 📱 Celulares
- 💻 Navegadores Web
- 📺 TVs e dispositivos grandes

## 🔐 Segurança
- Login com hash seguro (`password_hash`)
- Proteção contra SQL Injection com `prepare()`
- Sessões protegidas e separadas por tipo

## 📦 Instalação
1. Clone o repositório
2. Configure o `.env` e `config.php`
3. Rode `composer install`
4. Acesse `http://localhost/sgbccni/public_html/`

---

© 2025 Biblioteca CNI · Desenvolvido por Marcelo Botura Souza
