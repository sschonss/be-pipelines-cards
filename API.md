# Teste de API PipeCards

## Objetivo

O objetivo deste documento é descrever a API do PipeCards, que é uma aplicação de gerenciamento de tarefas.

## Autenticação

Para acessar a API, é necessário autenticar-se. A autenticação é feita através de um token JWT, que deve ser enviado no cabeçalho de todas as requisições.

### Criar usuário

Para criar um usuário, envie um POST para `/api/register` com o seguinte corpo:

```json
{
    "name": "Luiz",
    "email": "luiz2@luiz.com",
    "password": "12345678"
}
```

Para testa no Curl:

```bash
curl -X POST -H "Content-Type: application/json" -d '{"name": "Luiz", "email": "luiz2@luiz.com", "password": "12345678"}' http://127.0.0.1/api/register
```

O retorno sera semelhante a isso:

```json
{
    "status":"success"
}
```

### Autenticar usuário

Para autenticar um usuário, envie um POST para `/api/login` com o seguinte corpo:

```json
{
    "email": "luiz2@luiz.com",
    "password": "12345678"
}
```

Para testa no Curl:

```bash
curl -X POST -H "Content-Type: application/json" -d '{"email": "luiz2@luiz.com", "password": "12345678"}' http://127.0.0.1/api/login
```

O retorno sera semelhante a isso:

```
{
    "access_token":"<token>",
    "token_type":"bearer",
    "expires_in":3600
}
```

### Usar token

Para usar o token, envie-o no cabeçalho `Authorization` de todas as requisições:

### Buscar Seu Usuário

Para buscar o usuário autenticado, envie um GET para `/api/user` com o token no cabeçalho `Authorization`:

```bash
curl -X GET \
-H "Authorization: Bearer <token>" \
http://127.0.0.1/api/user
```

O retorno sera semelhante a isso:

```json
{
    "id": 1,
    "name": "Luiz",
    "email": "luiz2@luiz.com",
    "email_verified_at": null,
    "created_at": "2021-07-14T00:00:00.000000Z",
    "updated_at": "2021-07-14T00:00:00.000000Z"
}

```

### Logout

Para deslogar, envie um POST para `/api/logout` com o token no cabeçalho `Authorization`:

```bash
curl -X POST \
-H "Authorization: Bearer <token>" \
http://127.0.0.1/api/logout
```

O retorno sera semelhante a isso:

```json
{
    "status": "success",
    "message": "User logged out successfully"
}
```
---

## Pipelines

Pipelines são os estágios de um projeto. Por exemplo, um pipeline pode ser "To do", "Doing" e "Done".

### Criar pipeline

Para criar um pipeline, envie um POST para `/api/pipelines` com o seguinte corpo:

```json
{
    "name": "Back Log",
    "description": "Tasks in Back Log"
}
```

Para testa no Curl:

```bash
curl -X POST \
-H "Authorization: <token>" \
-H "Content-Type: application/json" \
-d '{
    "name": "Back Log",
    "description": "Tasks in Back Log"
}' \
http://127.0.0.1/api/pipelines
```

O retorno sera semelhante a isso:

```json
{
    "message": "Pipeline created successfully"
}
```

### Listar pipelines

Para listar todos os pipelines, envie um GET para `/api/pipelines` com o token no cabeçalho `Authorization`:

```bash
curl -X GET \
-H "Authorization: Bearer <token>" \
http://127.0.0.1/api/pipelines
```

O retorno sera semelhante a isso:

```json
[
    {
        "id": 1,
        "name": "Back Log",
        "description": "Tasks in Back Log",
        "pipeline_last_id": null,
        "user_id": 1
    },
    {
        "id": 2,
        "name": "Back Log 2",
        "description": "Tasks in Back Log",
        "pipeline_last_id": 1,
        "user_id": 1
    },
    {
        "id": 3,
        "name": "Back Log 3",
        "description": "Tasks in Back Log",
        "pipeline_last_id": 2,
        "user_id": 1
    }
]
```

Os campos `pipeline_last_id` e `user_id` são referências para o pipeline anterior e o usuário que criou o pipeline, respectivamente.

### Buscar pipeline específica

Para buscar um pipeline específico, envie um GET para `/api/pipelines/{id}` com o token no cabeçalho `Authorization`:

```bash
curl -X GET \
-H "Authorization: Bearer <token>" \
http://127.0.0.1/api/pipelines/1
```

O retorno sera semelhante a isso:

```json
{
    "id": 1,
    "name": "Back Log",
    "description": "Tasks in Back Log",
    "pipeline_last_id": null,
    "user_id": 1
}
```

### Atualizar pipeline

Para atualizar um pipeline, envie um PUT para `/api/pipelines/{id}` com o seguinte corpo:

```json
{
    "name": "Back Log4",
    "description": "Tasks in Back Log"
}
```

Para testa no Curl:

```bash
curl -X PUT
-H "Authorization: <token>" \
-H "Content-Type: application/json" \
-d '{
    "name": "Back Log4",
    "description": "Tasks in Back Log"
}' \
http://127.0.0.1/api/pipelines/1
```

O retorno sera semelhante a isso:

```json
{
    "message": "Pipeline updated successfully"
}
```

### Deletar pipeline

Para deletar um pipeline, envie um DELETE para `/api/pipelines/{id}` com o token no cabeçalho `Authorization`:

```bash
curl -X DELETE \
-H "Authorization: <token>" \
http://127.0.0.1/api/pipelines/1
```

O retorno sera semelhante a isso:

```json
{
    "message": "Pipeline deleted successfully"
}
```

---

## Cards

Cards são as tarefas de um projeto. O card inicializa em um pipeline e pode ser movido para outros pipelines, mas sempre evoluindo.

O card necessita de um pipeline para ser criado.

### Criar card

Para criar um card, envie um POST para `/api/cards` com o seguinte corpo:

```json
{
    "name": "Task 1",
    "description": "Task 1 description"
}
```

Para testa no Curl:

```bash
curl -X POST \
-H "Authorization: <token>" \
-H "Content-Type: application/json" \
-d '{"name": "Task 1", "description": "Task 1 description"}' \
http://127.0.0.1/api/cards
```

O retorno sera semelhante a isso:

```json
{
    "message": "Card created successfully"
}
```

### Listar cards

Para listar todos os cards, envie um GET para `/api/cards` com o token no cabeçalho `Authorization`:

```bash
curl -X GET \
-H "Authorization: Bearer <token>" \
http://127.0.0.1/api/cards
```

O retorno sera semelhante a isso:

```json
[
    {
        "id": 1,
        "name": "Task 1",
        "description": "Task 1 description",
        "pipeline_id": 1,
        "user_id": 1
    },
    {
        "id": 2,
        "name": "Task 2",
        "description": "Task 2 description",
        "pipeline_id": 1,
        "user_id": 1
    },
    {
        "id": 3,
        "name": "Task 3",
        "description": "Task 3 description",
        "pipeline_id": 1,
        "user_id": 1
    }
]
```

Os campos `pipeline_id` e `user_id` são referências para o pipeline que o card pertence e o usuário que criou o card, respectivamente.

### Buscar card específico

Para buscar um card específico, envie um GET para `/api/cards/{id}` com o token no cabeçalho `Authorization`:

```bash
curl -X GET \
-H "Authorization: Bearer <token>" \
http://127.0.0.1/api/cards/1
```

O retorno sera semelhante a isso:

```json
{
    "id": 1,
    "name": "Task 1",
    "description": "Task 1 description",
    "pipeline_id": 1,
    "user_id": 1
}
```

### Atualizar card

Para atualizar um card, envie um PUT para `/api/cards/{id}` com o seguinte corpo:

```json
{
    "name": "Task 1",
    "description": "Task 1 description"
}
```

Para testa no Curl:

```bash
curl -X PUT
-H "Authorization: <token>" \
-H "Content-Type: application/json" \
-d '{"name": "Task 1", "description": "Task 1 description"}' \
http://127.0.0.1/api/cards/1
```

O retorno sera semelhante a isso:

```json
{
    "message": "Card updated successfully"
}
```

### Mover card para próximo pipeline

Para mover um card para o próximo pipeline, envie um PUT para `/api/cards/{id}/next`:

Para testa no Curl:

```bash
curl -X PUT
-H "Authorization : <token>" \
http://127.0.0.1/api/cards/1/next
```

O retorno sera semelhante a isso:

```json
{
    "message": "Card moved to next pipeline successfully"
}
```

Caso o card esteja no último pipeline, o retorno será:

```json
{
    "message": "Card finished",
    "stage": null
}
```

### Deletar card

Para deletar um card, envie um DELETE para `/api/cards/{id}` com o token no cabeçalho `Authorization`:

```bash
curl -X DELETE \
-H "Authorization: <token>" \
http://127.0.0.1/api/cards/1
```

O retorno sera semelhante a isso:

```json
{
    "message": "Card deleted successfully"
}
```
