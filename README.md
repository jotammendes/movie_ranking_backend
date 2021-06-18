## Movie Ranking (Backend)

Desenvolvimento de uma API intermediária baseado em Laravel utilizando outras API's a fim de obter uma listagem de filmes em ordem decrescente de ranking.

# O Desafio

O desafio passado era de auxiliar um dono de cinema a filtrar os filmes mais desejados pelos seus clientes, para que ele pudesse comprar a licença para passar esses filmes em seu cinema com mais segurança de que será rentável.

A sugestão inicial durante a reunião foi de procurar em API's de reviews de filmes , pelo menos duas possíveis fontes, para ter uma maior confiabilidade nos dados e apresentá-los em ordem decrescente de nota. Outra sugestão feita na hora vou de gerar um formulário de pesquisa para que os clientes pudessem votar ou até enviar quais os filmes que eles mais desejam assistir.

# Resumo de Desenvolvimento

Na parte de backend foi criado uma API com o Laravel com as seguintes rotas:

-   listar filmes salvos no banco;
-   pegar filme salvo no banco pelo id;
-   verificar gêneros e lista de filmes de API externa e salvar no banco caso não exista, e atualizar caso já exista;
