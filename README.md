# 🧪 API REST de Usuários – Projeto de Estudo

Uma API RESTful simples desenvolvida em **PHP** com o **Slim Framework**. Este projeto foi criado como uma forma de praticar os conceitos de desenvolvimento de APIs, incluindo a criação de endpoints, manipulação de requisições HTTP e estruturação de um projeto do zero.

---

## 🚀 Começando

Siga estas instruções para obter uma cópia do projeto em execução na sua máquina local para desenvolvimento e testes.

### Pré-requisitos

O que você precisa ter instalado para rodar o projeto:

* **PHP 7.4** ou superior
* **Composer**

### Instalação

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/seu-repo.git](https://github.com/seu-usuario/seu-repo.git)
    cd seu-repo
    ```

2.  **Instale as dependências com o Composer:**
    ```bash
    composer install
    ```

3.  **Inicie o servidor de desenvolvimento do PHP:**
    ```bash
    php -S localhost:8000 -t public
    ```

Pronto! A API estará disponível em `http://localhost:8000`.

---

## 📌 Endpoints da API

A seguir estão todos os endpoints disponíveis na aplicação.

| Método | Endpoint        | Descrição                     | Corpo da Requisição (Exemplo)                                           |
| :----- | :-------------- | :---------------------------- | :---------------------------------------------------------------------- |
| `POST` | `/users`        | Cria um novo usuário.         | `{ "username": "seu_nome", "email": "email@exemplo.com", "password": "sua_senha" }` |
| `GET`  | `/users`        | Lista todos os usuários.      | (N/A)                                                                   |
| `GET`  | `/users/{id}`   | Busca um único usuário pelo ID. | (N/A)                                                                   |
| `PUT`  | `/users/{id}`   | Atualiza os dados de um usuário. | `{ "username": "novo_nome" }`                                          |
| `DELETE`| `/users/{id}`   | Deleta um usuário.            | (N/A)                                                                   |

---

## 📝 Observações Importantes

* **Persistência de Dados:** Os dados são armazenados em um array na memória. Isso significa que **todos os registros serão perdidos** quando o servidor for reiniciado.
* **Segurança:** As senhas são criptografadas usando a função `password_hash()` do PHP, seguindo as práticas básicas de segurança.
* **Formato das Respostas:** Todas as respostas da API são retornadas no formato **J