
## Normalização de Dados
Prompt: Solicitei à IA uma função para normalizar nomes de alunos, garantindo que nomes como "  aNA   paUla  " fossem convertidos para "Ana Paula".<br>
Resposta resumida: Recebi uma função que remove espaços extras e coloca cada palavra com a primeira letra maiúscula.<br>
Adaptações: Implementei a função no middleware de alunos, garantindo que todos os nomes sejam salvos de forma padronizada.<br>
Validações: Testei com diferentes entradas, inclusive nomes compostos e com acentuação.<br>
<br>
## Validação de Payload
Prompt: Pedi à IA para criar validações que retornassem mensagens claras ao usuário em caso de erro.<br>
Resposta resumida: A IA sugeriu o uso de respostas padronizadas com status HTTP e mensagens específicas para cada tipo de erro.<br>
Adaptações: Adicionei validações para nome, idade e curso_id, além de mensagens customizadas para cada campo.<br>
Validações: Simulei requisições inválidas para garantir que todas as mensagens fossem exibidas corretamente.<br>
<br>
## Relatórios Dinâmicos
Prompt: Solicitei funções para gerar relatórios de total de alunos por curso e média de idade por curso.<br>
Resposta resumida: Recebi queries SQL otimizadas usando JOIN e GROUP BY.<br>
Adaptações: Implementei os métodos no DAO e criei endpoints específicos no controller.<br>
Validações: Testei os relatórios com diferentes cenários, incluindo cursos sem alunos.<br>
<br>
## Filtros Inteligentes
Prompt: Pedi à IA para implementar filtros dinâmicos na listagem de alunos, permitindo busca por nome do curso ou id do curso.<br>
Resposta resumida: Recebi métodos separados no DAO para cada filtro e lógica condicional no controller.<br>
Adaptações: Ajustei para aceitar múltiplos parâmetros e garantir que o filtro fosse aplicado corretamente.<br>
Validações: Realizei buscas com diferentes parâmetros e analisei os resultados retornados.<br>
<br>
## Tratamento de Erros e Logging
Prompt: Solicitei à IA sugestões para registrar exceções e erros críticos no sistema.<br>
Resposta resumida: Recebi uma função de logger que grava mensagens detalhadas em arquivo.<br>
Adaptações: Integrei o logger em pontos críticos do código, como falhas de conexão e erros de validação.<br>
Validações: Provoquei erros propositalmente e verifiquei se os logs estavam sendo gerados corretamente.<br>
<br>
## Validação de Formulários Frontend
Prompt: Solicitei à IA implementação de validações client-side nos formulários.<br>
Resposta resumida: Recebi funções de validação para prevenir envio de dados inválidos.<br>
Adaptações: Implementei feedback visual imediato e mensagens de erro personalizadas.<br>
Validações: Testei diferentes cenários de entrada, incluindo campos vazios e valores inválidos.<br>


## Integração com API
Prompt: Solicitei à IA melhorias no tratamento das chamadas à API.<br>
Resposta resumida: Recebi implementação de try/catch com tratamento específico de erros.<br>
Adaptações: Adicionei logs no console e feedback visual para erros de conexão.<br>
Validações: Testei cenários de falha de rede e respostas de erro da API.<br>

## Atualização Dinâmica de Tabelas<br>
Prompt: Pedi à IA uma forma eficiente de atualizar as tabelas após operações.<br>
Resposta resumida: Recebi funções para recarregar dados sem refresh da página.<br>
Adaptações: Implementei animações suaves durante atualizações.<br>
Validações: Testei múltiplas operações em sequência para garantir consistência.<br>

