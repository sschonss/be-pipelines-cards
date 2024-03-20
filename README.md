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
