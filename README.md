# PSI_PWS_PL_B

Autor: Ana margarida Ferreira Cação
- nº estudante: 2201159
- Git: https://github.com/Jvendas/PSI_PWS_PL_B.git

Presente projeto tem como objetivo e implementar uma plataforma de gestão de um aeroporto, cuja denominação é FlightTravelAir.

O Script para criar a base de dados está na pasta weblogicmvc/database\bdflighttravelair.sql

Abaixo estão indicadas todas as funcionalidades implementadas no projeto.

 ##  Credenciais
 
 - Perfil: administrador
- Username: admin
- Password: 123
<br></br>
 - Perfil: operador de checkin
 - Username: checkin
 - Password: 123
<br></br>
-  Perfil: gestor de voo
 - Username: gestor
 - Password: 123
<br></br>
-  Perfil: passageiro
 - Username: passageiro
 - Password: 123

## Zonas Públicas 
- Registo
- Login (após o login o utiliador é redirecionado para a zona privada do seu perfil)
- Logout
- Editar Perfil

## Zonas Reservadas 
O projeto é contituído por quatro zonas distintas: 
- Passageiro
- Operador de checkin
- Gestor de voo
- Zona Administrador

### Passageiro
- Consegue consultar os voos introduzindo a origem o destino, assim como as datas de partida e regresso.
Tendo isto em conta, é possivel consultar e comprar passagens de só de ida e ida/volta.
Se o resultado da consulta conter vários voos de ida ou de volta, é possível selecionar o pretenido.
- Consegue visualizar o seu histórico de passagens compradas, com ou sem checkin

### Operador de checkin
- Realiza o checkin a passagens compradas por um passageiro;
-- Para o checkiné necessário introduzir o id da passagem e o nº de telefone do passageiro. O nº de telefone é usado para associar um passageiro a uma passagem vendida (como se fosse o NIF);
- Consegue visualizar o histórico de todas as passagens vendidas (com e sem checkin)
### Gestor de voo
- Consegue fazer as operações CRUD dos voos;
- Consegue fazer as operações CRUD das escalas;
- Consegue fazer as operações  CRUD dos aviões;


### Administrador
 - Consegue criar contas de utilizadores para o gestor de voo e operador de checkin;
 - Consegue editar as contas dos perfis acima mencionados;
 - Consegue apagar utilizadores (nao incluí passageiros); 
 - Consegue fazer as operações CRUD aos aeroportos;


## Outros
- Foram implementadas mensagems de sucesso,aviso e erro para as operações que envolvem CRUD assim como outras mensagens: ex consultas inválidas, ou erros de autenticação

## Erros conhecidos
- Não é possível consultar voos com duas ou mais escalas: ex: LIS - DUBAI - CHINA
- Não foi implementado a simulação de pagamento (quando o utilizador clica em comprar passagem ela é "automaticamente" comprada)
 -Não foi implementado o download do bilhete detalhado(após compra)





