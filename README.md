#  Sistema de Gestão de Alunos e Cursos

Um sistema simples e eficiente para **gerenciamento de alunos e cursos**, desenvolvido em **PHP, MySQL e JavaScript**.

---

## 🌷 Visão Geral

Este projeto facilita o controle de alunos e cursos, oferecendo rotas de API REST para cadastro, listagem e geração de relatórios.

**Tecnologias utilizadas:**
- 🐘 PHP 8.0+
- 🗄️ MySQL 8.0+
- ⚙️ XAMPP v3.3.0+
- 🌐 JavaScript (Frontend)
- 💻 HTML & CSS (Interface)

---

##  Requisitos

Antes de iniciar, verifique se você possui:
- [XAMPP](https://www.apachefriends.org/) v3.3.0 ou superior  
- PHP 8.0+  
- MySQL 8.0+  
- Um navegador moderno (Chrome, Edge, Firefox, etc.)

---

##  Instalação

### 🧩 1. Banco de Dados

Acesse o **phpMyAdmin** em [http://localhost/phpmyadmin](http://localhost/phpmyadmin)  
e execute o seguinte script SQL:

```sql
CREATE DATABASE IF NOT EXISTS mydb;
USE mydb;

CREATE TABLE cursos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE alunos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    idade INT NOT NULL,
    curso_id INT,
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
);
```

---

### 🎀 2. Configuração do Banco

Edite o arquivo de configuração localizado em:  
`D:\xampp\htdocs\contaself-estagio-projeto\backend\API\src\DB\Database.php`

E ajuste as credenciais conforme seu ambiente:

```php
private static $host = 'localhost';
private static $dbname = 'mydb';
private static $username = 'root';
private static $password = '';
```

---

### 🌸 3. Acesso

- **Frontend:** [http://localhost/contaself-estagio-projeto/frontend/]
- **API (Backend):** [http://localhost/contaself-estagio-projeto/backend/API/]

---

## 🎨 API Reference

### 🎓 Cursos

- **GET /cursos**  
  Lista todos os cursos cadastrados.
  ```bash
  curl -X GET http://localhost/contaself-estagio-projeto/backend/API/cursos
  ```

- **POST /cursos**  
  Cadastra um novo curso.
  ```bash
  curl -X POST http://localhost/contaself-estagio-projeto/backend/API/cursos \
  -H "Content-Type: application/json" \
  -d '{
      "curso": {
          "nome": "Informática"
      }
  }'
  ```

### 👩‍🎓 Alunos

- **GET /alunos**  
  Lista todos os alunos cadastrados.
  ```bash
  curl -X GET http://localhost/contaself-estagio-projeto/backend/API/alunos
  ```

- **POST /alunos**  
  Cadastra um novo aluno vinculado a um curso existente.
  ```bash
  curl -X POST http://localhost/contaself-estagio-projeto/backend/API/alunos \
  -H "Content-Type: application/json" \
  -d '{
      "aluno": {
          "nome": "João Silva",
          "idade": 17,
          "cursos": {
              "id": 1,
              "nome": "Informática"
          }
      }
  }'
- **DELETE /alunos/{id}**  
    Remove um aluno pelo ID.
    ```bash
    curl -X DELETE http://localhost/contaself-estagio-projeto/backend/API/alunos/1
    
  ```

### 📊 Relatórios

- **Total de Alunos por Curso**
  ```bash
  curl -X GET http://localhost/contaself-estagio-projeto/backend/API/alunos/total-por-curso
  ```
- **Média de Idade por Curso**
  ```bash
  curl -X GET http://localhost/contaself-estagio-projeto/backend/API/alunos/media-idade-por-curso
  ```

---

## 📚 Documentação IA

A documentação técnica está organizada na pasta `prompts/`:

```
prompts/
├── 01-arquitetura.md  # Estrutura e decisões técnicas
├── 02-banco.md        # Modelagem de dados
├── 03-logica.md       # Regras de negócio
├── 04-interface.md    # Frontend e UX
└── 05-testes.md       # Testes e validações
```

---

## 🏗️ Estrutura Recomendada do Projeto

```
contaself-estagio-projeto/
├── backend/
│   └── API/
│       ├── src/
│       │   ├── DB/
│       │   │   └── Database.php
│       │   ├── Controllers/
│       │   ├── Models/
│       │   └── routes/
│       └── index.php
└── frontend/
    ├── index.html
    ├── js/
    └── css/
```

---

## 💡 Dica

Para testar a API rapidamente, utilize:
- **Insomnia**
- Ou o terminal com `curl` (como nos exemplos acima).

---

## 🤝 Suporte

- 🐞 Github: claramorei
- 📧 Contato: anacm123409@gmail.com
