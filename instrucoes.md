nome sistema: API para inclusão de Clientes e Dependentes

requisito técnico: 
- Utilizado Laravel 5.7, necessário PHP >= 7.1.3
- mysql >= 5.7

instruções: 
- executar migrations para criação das tabelas no Mysql;

- para iniciar a utilização é necessário acessar o endpoint <<server>>/api/register enviando os seguintes campos:
  - name
  - email
  - password
  - c_password
- criado usuario é gerado um token. Esse token deve ser utilizado em todos os acessos aos endpoint que requerem autenticação;

- se não estiver logado, acessar o endpoint <<server>>/api/login enviando os seguintes campos:
  - email
  - password
- ao logar, será retornado um token. Esse token deve ser utilizado em todos os acessos aos endpoint que requerem autenticação;

Relação de EndPoints:
  Sem Autenticação
/api/login - POST - login de usuário
/api/register - POST - cadastro de usuário
/api/clientes - GET - relação de clientes
/api/clientes/{1} - GET - dados de cliente específico
/api/dependentes - GET - relação de dependentes
/api/dependentes/{1} - GET - dados de dependente específico

  Requer Autenticação
/api/details - POST - dados do usuário logado
/api/clientes - POST - grava novo cliente
/api/clientes/{1} - PUT - atualiza dados de cliente existente
/api/clientes/{1} - DELETE - exclui cliente (ação restrita ao User que criou)
/api/dependentes - POST - grava novo dependente (necessário informar cliente_id válido)
/api/dependentes/{1} - PUT - atualiza dados de dependente existente
/api/dependentes/{1} - DELETE - exclui dependente


