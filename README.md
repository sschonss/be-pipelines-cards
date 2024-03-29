# PipeCards

Uma aplicação para gerenciar cartões de tarefas.

## Requisitos

- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

Uma recomendação é utilizar o [PHPCTL](https://github.com/opencodeco/phpctl) para gerenciar o ambiente de desenvolvimento.

O PHPCTL é uma ferramenta que usa Docker para manter o ambiente de desenvolvimento PHP. Ele é uma alternativa muito mais leve e moderna para o desenvolvimento PHP do que o XAMPP, WAMP, MAMP, etc.

## Instalação

1. Clone o repositório

```bash
git clone <url-do-repositorio>
```

2. Instale as dependências

```bash
phpctl composer install

# ou

composer install
```

3. Copie o arquivo `.env.example` para `.env`

```bash
cp .env.example .env
```

4. Gere a chave da aplicação

```bash
phpctl artisan key:generate

# ou

php artisan key:generate
```

5. Gere a chave do JWT

```bash
phpctl artisan jwt:secret

# ou

php artisan jwt:secret
```

6. Execute as migrações

```bash
phpctl artisan migrate

# ou

php artisan migrate
```

7. Inicie o servidor

```bash
./vendor/bin/sail up
```

## Docker

Esse projeto utiliza as seguintes ferramentas junto com o Docker:

- [Laravel Sail](https://laravel.com/docs/8.x/sail)
- [PostgreSQL](https://www.postgresql.org/)
- [Redis](https://redis.io/)
- [Nginx](https://www.nginx.com/)

## Documentação da API (Postman)

[PipeCards API](https://documenter.getpostman.com/view/12399484/2sA358cQxW)

## Documentação da API (Markdown)

[API.md](API.md)

## Autenticação

A autenticação é feita com JWT. Para fazer login, você precisa enviar um POST para `/api/login` com o email e senha do usuário.

Sabemos que existem varios Starter Kits para autenticação, mas preferi fazer a autenticação dessa maneira para ser mais simples.

Poderiamos usar o Sanctum, Passport, etc, mas para esse projeto, o JWT é suficiente.

## Testes

Para rodar os testes, execute o comando:

```bash
./vendor/bin/sail test
```

Esses testes são executados com o PHPUnit e são baseados em testes muito simples, apenas para garantir que a aplicação está funcionando corretamente.

## CI

Esse projeto utiliza o GitHub Actions para CI. O arquivo de configuração está em `.github/workflows/ci.yml`.

Esse arquivo é responsável por rodar os testes e buildar a aplicação, garantindo que o código está funcionando corretamente.

## Balanceamento de carga

Como estamos usando Nginx, podemos facilmente adicionar balanceamento de carga.

Na pasta `docker` temos o arquivo `nginx.conf` que é o arquivo de configuração do Nginx, e nele já temos um exemplo de balanceamento de carga.

## Redis

O Redis pode ser utilizado para cache, filas, etc. Nesse projeto, ele é utilizado para armazenar a sessão do usuário.

Lembre-se de configurar o arquivo `.env` para utilizar o Redis.

Usamos o Redis pois precisamos a configuração de sessão do usuário padrão do Laravel é arquivos e como estamos usando Load Balancing, precisamos de uma maneira de compartilhar a sessão entre os servidores.

## Alternativas para Rodar o Projeto

Existem outras maneiras de rodar o projeto, como por exemplo:

- Usar o Composer para rodar o Laravel: [Laravel](https://laravel.com/docs/8.x/installation)
- Usar Docker sem o Sail, como nesse exemplo que eu fiz: [PHP Docker](https://github.com/sschonss/tutorial-docker-laravel-mariadb)
