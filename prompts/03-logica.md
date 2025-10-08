
## Normalização de Dados
Prompt: Solicitei à IA uma função para normalizar nomes de alunos, garantindo que nomes como "  aNA   paUla  " fossem convertidos para "Ana Paula".
Resposta resumida: Recebi uma função que remove espaços extras e coloca cada palavra com a primeira letra maiúscula.
Adaptações: Implementei a função no middleware de alunos, garantindo que todos os nomes sejam salvos de forma padronizada.
Validações: Testei com diferentes entradas, inclusive nomes compostos e com acentuação.

## Validação de Payload
Prompt: Pedi à IA para criar validações que retornassem mensagens claras ao usuário em caso de erro.
Resposta resumida: A IA sugeriu o uso de respostas padronizadas com status HTTP e mensagens específicas para cada tipo de erro.
Adaptações: Adicionei validações para nome, idade e curso_id, além de mensagens customizadas para cada campo.
Validações: Simulei requisições inválidas para garantir que todas as mensagens fossem exibidas corretamente.

## Relatórios Dinâmicos
Prompt: Solicitei funções para gerar relatórios de total de alunos por curso e média de idade por curso.
Resposta resumida: Recebi queries SQL otimizadas usando JOIN e GROUP BY.
Adaptações: Implementei os métodos no DAO e criei endpoints específicos no controller.
Validações: Testei os relatórios com diferentes cenários, incluindo cursos sem alunos.

## Filtros Inteligentes
Prompt: Pedi à IA para implementar filtros dinâmicos na listagem de alunos, permitindo busca por nome do curso ou id do curso.
Resposta resumida: Recebi métodos separados no DAO para cada filtro e lógica condicional no controller.
Adaptações: Ajustei para aceitar múltiplos parâmetros e garantir que o filtro fosse aplicado corretamente.
Validações: Realizei buscas com diferentes parâmetros e analisei os resultados retornados.

## Tratamento de Erros e Logging
Prompt: Solicitei à IA sugestões para registrar exceções e erros críticos no sistema.
Resposta resumida: Recebi uma função de logger que grava mensagens detalhadas em arquivo.
Adaptações: Integrei o logger em pontos críticos do código, como falhas de conexão e erros de validação.
Validações: Provoquei erros propositalmente e verifiquei se os logs estavam sendo gerados corretamente.

## Validação de Formulários Frontend
Prompt: Solicitei à IA implementação de validações client-side nos formulários.
Resposta resumida: Recebi funções de validação para prevenir envio de dados inválidos.
Adaptações: Implementei feedback visual imediato e mensagens de erro personalizadas.
Validações: Testei diferentes cenários de entrada, incluindo campos vazios e valores inválidos.


## Integração com API
Prompt: Solicitei à IA melhorias no tratamento das chamadas à API.
Resposta resumida: Recebi implementação de try/catch com tratamento específico de erros.
Adaptações: Adicionei logs no console e feedback visual para erros de conexão.
Validações: Testei cenários de falha de rede e respostas de erro da API.

## Atualização Dinâmica de Tabelas
Prompt: Pedi à IA uma forma eficiente de atualizar as tabelas após operações.
Resposta resumida: Recebi funções para recarregar dados sem refresh da página.
Adaptações: Implementei animações suaves durante atualizações.
Validações: Testei múltiplas operações em sequência para garantir consistência.

