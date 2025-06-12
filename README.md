# Como rodar o projeto

## 1. Instale as dependências

```sh
composer install
```

## 2. Suba os containers Docker

```sh
docker compose up -d
```

## 3. Execute as migrations do banco de dados

```sh
docker exec -it php-minibank-hyperf-us php bin/hyperf.php migrate
```

Para ver o status das migrations:
```sh
docker exec -it php-minibank-hyperf-us php bin/hyperf.php migrate:status
```

## 4. Rodar os testes

```sh
docker exec -it php-minibank-hyperf-us composer test
```

## 5. Rodar análise de código (code insights)

```sh
docker exec -it php-minibank-hyperf-us composer code:insights
```

## 6. Gerar usuários e wallets fake

```sh
docker exec -it php-minibank-hyperf-us php bin/hyperf.php fake:users
```
---

- Acesse o Swagger UI em: [http://localhost/swagger-ui.html](http://localhost/swagger-ui.html)
- O arquivo de documentação Swagger JSON será gerado em `public/`.

**Dica:**
- Sempre execute comandos do Hyperf dentro do container Docker para evitar problemas de dependências (ex: Swoole).
- Para popular o banco com dados fake, use o comando customizado ou script conforme sua necessidade.

