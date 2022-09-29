# Backend Challenge 20220626

>  This is a challenge by [Coodesh](https://coodesh.com/)

Este projeto implementa uma API REST para o [Open Food Facts](https://world.openfoodfacts.org/)
utilizando PHP, sem nenhum framework.

## Execução

Para levantar a infraestrutura do sistema, utilize o Docker Compose:

```sh
docker-compose up
```

Antes de utilizar o sistema, é necessário popular seu BD. Isso pode ser feito
acessando-se a rota `/cron` (veja mais sobre o disparo dos endpoints abaixo).

## Rotas

Cada rota deve ser chamada com base na URL http://localhost/index.php. Por
exemplo, para acessar a listagem de todos os produtos, deve-se seguir para
http://localhost/index.php/products.

As rotas existentes são:

  * [/](http://localhost/index.php/): retorna uma mensagem de apresentação
    do sistema.
  * [/products](http://localhost/index.php/products): acessa um lista paginada
    de todos os produtos disponíveis no BD.
  * [/products/123](http://localhost/index.php/products/123): confere as 
    informações do produto com código 123.
  * [/cron](http://localhost/index.php/cron): dispara script que raspa
    os dados do Open Food Facts.

## Ressalvas quanto à imagem utilizada

A [imagem](https://hub.docker.com/_/php) que utilizei para o PHP/Apache é
bem limitada quanto a recursos diversos. Como não tive tempo de procurar
uma imagem mais completa, acabei trabalhando com esta mesma, o que me 
restringiu em dois pontos.

Em primeiro lugar, instalei o cron no container, e consegui colocar o
daemon para funcionar. Ainda assim, ele ignorava os arquivos crontab
por completo, não disparando o script de raspagem do PHP. Acabei deixando
essa parte de lado, mas configurar uma tarefa para ser feita no cron
é algo muito simples. Neste caso, bastaria adicionar a seguinte linha
ao arquivo:

```
0 0 * * * php -v /var/www/html/cron.php
```

E o script `cron.php` seria disparada todos os dias, à meia-noite.

A segunda restrição que não tive tempo de superar foi a configuração
das rotas sem o trecho `index.php`. Ou seja, em vez de:

http://localhost/index.php/products

o que eu queria era:

http://localhost/products

E para isso eu precisaria editar o arquivo `.htaccess`, informando:

```
RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]
```

Infelizmente, o Apache não estava aceitando nenhuma diretiva neste arquivo,
e os logs não estão sendo coletados. Sem essa possibilidade, só me
restou manter o `index.php` nas URLs.

Por entender que estas minúcias não são muito importantes para o teste,
optei por ignorá-las.

