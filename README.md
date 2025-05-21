
# 📘 Projeto: Sistema de Gestão da Biblioteca Comunitária - CNI

**Versão Atual:** `v1.0 - Produção com E-mail`

---

## 📁 Estrutura de Pastas

### 1. `public_html/` *(acesso público na Hostinger)*
Contém toda a parte acessível ao navegador:

```
public_html/
├── index.php                # Página inicial (painel do usuário)
├── login/                   # Tela de login, registro, esqueci senha
├── usuario/                 # Área do usuário logado
├── assets/                  # CSS, JS e imagens públicas
├── .htaccess                # URLs amigáveis, cache e segurança
```

### 2. `app_backend/` *(fora do public_html, seguro)*
Responsável pela lógica, conexão com banco e funcionalidades sensíveis:

```
app_backend/
├── config/
│   ├── config.php           # Conexões, constantes e segurança
│   ├── env.php              # Leitura das variáveis .env
├── .env                     # Variáveis de ambiente (produção)
├── .env.example             # Exemplo para configurar localmente
├── controllers/             # Lógica de controle de funcionalidades
├── includes/                # Includes reutilizáveis como header, footer, session
├── admin/                   # Painel administrativo (protegido por IP)
│   └── .htaccess            # Restrição de acesso por IP
├── uploads/                 # Armazenamento de imagens, capas, PDFs
├── mail/
│   ├── PHPMailer/           # Biblioteca PHPMailer
│   ├── handlers/            # Scripts de envio de e-mail
│   │   └── recuperar_senha.php
├── sgbccni.sql              # Backup do banco de dados
├── tokens_recuperacao.sql  # Script para criação da tabela de recuperação de senha
```

---

## 🔐 Segurança

- A pasta `admin/` está protegida por `.htaccess` com IP fixo.
- Toda configuração sensível está fora da `public_html/`.
- Conexões e credenciais estão centralizadas no `.env`.

---

## 📧 Funcionalidades com E-mail

### 1. Recuperação de senha
- Via `recuperar_senha.php` em `mail/handlers`
- Link único com token (expira em 1 hora)

### 2. PHPMailer integrado
- SMTP Hostinger com segurança TLS
- Configurado via `.env`

---

## ⚙️ Banco de Dados

### Tabela adicional:
```sql
CREATE TABLE tokens_recuperacao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  expira_em DATETIME NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
```

### Arquivos SQL:
- `sgbccni.sql` → Banco completo
- `tokens_recuperacao.sql` → Tabela de recuperação de senha

---

## 🚀 Deploy Hostinger

**Passos para subir:**
1. Envie a pasta `public_html/` para a raiz do site (diretório público).
2. Envie a pasta `app_backend/` fora da `public_html`.
3. Configure o `.env` com:
```env
DB_NAME=...
DB_USER=...
DB_PASS=...
EMAIL_SUPORTE=seuemail@seudominio.com
```
4. Importe os arquivos SQL no phpMyAdmin.
5. Teste login, recuperação de senha e funcionalidades.

---

## 📌 Observações finais

- Código modular e pronto para expansão.
- Planejado para ambiente profissional com segurança, desempenho e manutenibilidade.
- Atualizações futuras podem incluir: QR Code, favoritos, tags inteligentes, histórico de leitura, etc.
