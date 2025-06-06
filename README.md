# TakeHomeAssignment
--- INTRODUÇÃO ---
Este projeto é um teste para o processo seletivo do Ebanx, com o intuito de desenvolver uma API que processe duas requests: /balance e /event.

--- RODANDO O PROJETO ---
Para rodar o projeto corretamente, deve-se primeiro verificar os dados de conexão com o banco de dados MySQL na classe DatabaseService.php, adaptando-os para a sua base local. Feito isso, deve-se rodar o script runMigrationsScript.php, uma vez que as migrations devem ser rodadas manualmente. No Windows, utilize o comando 'php ".\utils\runMigrationsScript.php"' da pasta base do projeto, ou equivalente para outros sistemas operacionais. Feito isso, o projeto estará pronto para ser utilizado.

--- ESTRUTURA DE PROJETO ---
O Projeto foi realizado utilizando uma estrutura MVC, tal qual não há camada de View pois não há frontend na API. Foi utilizada estrutura MVC, pois promove a escalabilidade do projeto, assim como facilita o entendimento de cada uma das classes.

--- CAMADA DE MODELO ---
Nessa camada é realizada a declaração dos objetos, como Account, que possui um ID único (string), número de conta e saldo. Os modelos não possuem lógica ou regras de negócios, somente as declarações, getters e setters.

--- CAMADA DE CONTROLADOR ---
Essa camada é composta pelos controladores e serviços. Os controladores possuem as regras de negócio referentes à lógica dos objetos descritos no modelo, como o AccountController, que contém as regras de negócio do objeto Account. Os serviços contém regras de negócios de classes estáticas, tais quais os métodos são chamados mas não é instanciado um objeto, como a classe de requisições RestService e a classe de manipulação de banco de dados DatabaseService.

--- UTILITÁRIOS ---
A pasta utils é referente aos utilitários do sistema, como o autoload das classes do sistema e as migrations de banco de dados. A migration Initial Load contém o SQL de criação da tabela 'account' do banco de dados MySQL utilizado pelo DatabaseService. As migrations são rodadas manualmente através do script runMigrationsScript.php.

--- ENDPOINTS ---
Os endpoints esperados são:
/reset (POST): Apaga todos os registros da tabela account do banco de dados;
/event (POST): Cria uma solicitação de evento de depósito, saque ou transferência de valores de uma conta;
/balance (GET): Retorna o saldo de uma conta.

Quando uma solicitação é enviada com endpoint não esperado, retorna-se código 400 - Bad Request.

--- GITHUB ---
O projeto encontra-se público em https://github.com/Drecov/TakeHomeAssignment.