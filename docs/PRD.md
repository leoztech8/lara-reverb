# PRD - Sistema de Contadores em Tempo Real

## 1. Visão Geral

Sistema de demonstração para Laravel Reverb que permite criar e gerenciar contadores com atualizações em tempo real usando WebSockets. O sistema será usado como teste público online para demonstrar as capacidades do Laravel Reverb.

## 2. Objetivos

- Demonstrar capacidades do Laravel Reverb para comunicação em tempo real
- Fornecer interface intuitiva e moderna para interação com contadores
- Permitir que múltiplos usuários vejam atualizações simultaneamente
- Criar experiência visual sofisticada e elegante

## 3. Requisitos Funcionais

### 3.1 Tela Principal

#### 3.1.1 Exibição de Cards
- Cada card representa um contador com:
  - Nome do contador (título)
  - Valor atual do contador (número grande e visível)
  - Botão para incrementar o contador
  - Timestamp de última atualização (opcional)
  - Visual moderno e responsivo

#### 3.1.2 Ordenação
- Cards devem ser automaticamente ordenados por:
  - Prioridade 1: Maior número de contagem (descendente)
  - Prioridade 2: Nome alfabético (caso de empate)
- Reordenação deve ocorrer em tempo real sem reload da página
- Animação suave durante reordenação

#### 3.1.3 Atualização em Tempo Real
- Quando qualquer usuário incrementa um contador:
  - Todos os usuários conectados veem a atualização instantaneamente
  - Animação visual indica a mudança (ex: pulse, glow)
  - Reordenação automática se necessário
- Feedback visual para ações do próprio usuário vs. outros usuários

### 3.2 Criação de Novos Contadores

#### 3.2.1 Formulário de Criação
- Modal ou seção dedicada para criar novo contador
- Campos obrigatórios:
  - Nome do contador (string, único, máx 50 caracteres)
- Validação em tempo real
- Mensagens de erro claras e elegantes

#### 3.2.2 Comportamento
- Novo contador inicia com contagem 0
- Após criação, card aparece na posição correta (ordenado)
- Broadcast para todos os usuários conectados
- Feedback de sucesso após criação

### 3.3 Incremento de Contador

#### 3.3.1 Ação de Incremento
- Botão visível e acessível em cada card
- Incremento de +1 por clique
- Sem limite máximo de contagem
- Debounce/throttle para prevenir spam (opcional)

#### 3.3.2 Feedback Visual
- Loading state durante processamento
- Animação no número ao incrementar
- Indicador visual de quem incrementou (opcional)

## 4. Requisitos Não-Funcionais

### 4.1 Performance
- Latência < 500ms para atualizações em tempo real
- Suporte para mínimo 50 usuários simultâneos
- Otimização de queries (eager loading, caching)
- Sem N+1 queries

### 4.2 Escalabilidade
- Estrutura preparada para adicionar features futuras
- Código modular e reutilizável
- Seguir padrões Laravel e convenções do projeto

### 4.3 Segurança
- Validação server-side para todos os inputs
- Rate limiting para prevenir abuso
- Sanitização de dados
- CSRF protection ativo

### 4.4 Usabilidade
- Interface extremamente intuitiva
- Responsivo: mobile, tablet, desktop
- Acessibilidade (WCAG 2.1 AA)
- Suporte a modo claro/escuro (se projeto usar)
- Feedback imediato para todas as ações

## 5. Design e UX

### 5.1 Estilo Visual
- **Paleta**: Cores modernas e sofisticadas usando Tailwind CSS v4
- **Tipografia**: Hierarquia clara, fonte legível
- **Espaçamento**: Generoso, uso de gap utilities
- **Sombras e profundidade**: Subtle shadows para criar hierarquia
- **Animações**: Suaves e purposeful (não exageradas)

### 5.2 Layout
- Grid responsivo de cards
- Header com título e botão "Novo Contador"
- Cards com:
  - Destaque para o número (grande, bold)
  - Nome do contador como título secundário
  - Botão de incremento proeminente
  - Indicador de status/atividade

### 5.3 Interações
- Hover states em todos os elementos clicáveis
- Loading states para ações assíncronas
- Transições suaves entre estados
- Feedback tátil (animações, cores)

### 5.4 Estados
- **Empty state**: Quando não há contadores
  - Mensagem convidativa
  - CTA para criar primeiro contador
- **Loading state**: Durante carregamento inicial
- **Error state**: Tratamento elegante de erros

## 6. Stack Tecnológica

### 6.1 Backend
- Laravel 12 (framework)
- Laravel Reverb (WebSockets)
- Laravel Fortify (autenticação - se necessário)
- MySQL/PostgreSQL (banco de dados)

### 6.2 Frontend
- Livewire 3 + Volt (interatividade)
- Flux UI Free (componentes)
- Tailwind CSS v4 (estilização)
- Alpine.js (incluído com Livewire)

### 6.3 Testes
- Pest v4 (testing framework)
- Feature tests para todas as funcionalidades
- Browser tests para fluxos críticos
- Cobertura mínima: 80%

## 7. Estrutura de Dados

### 7.1 Model: Counter
```
- id: bigint (PK)
- name: string (unique, max:50)
- count: integer (default: 0)
- created_at: timestamp
- updated_at: timestamp
```

### 7.2 Relacionamentos
- Sem relacionamentos por enquanto
- Estrutura preparada para adicionar user_id futuramente

## 8. Eventos e Broadcasting

### 8.1 Eventos
- `CounterCreated`: Disparado quando novo contador é criado
- `CounterIncremented`: Disparado quando contador é incrementado

### 8.2 Channels
- Public channel: `counters`
  - Broadcast de todas as atualizações
  - Sem autenticação necessária (para teste público)

### 8.3 Payload
```json
{
  "id": 1,
  "name": "Teste A",
  "count": 42,
  "updated_at": "2025-11-12T10:30:00Z"
}
```

## 9. Rotas e Endpoints

### 9.1 Views
- `GET /` - Página principal (Volt component)

### 9.2 API/Actions (Livewire)
- `createCounter(name)` - Criar novo contador
- `incrementCounter(id)` - Incrementar contador

## 10. Validações

### 10.1 Criação de Contador
- **name**: required, string, max:50, unique:counters,name
- Mensagens personalizadas em português

### 10.2 Incremento
- **id**: required, exists:counters,id
- Validação de rate limiting (max 10 por minuto por IP)

## 11. Componentes

### 11.1 Componentes Livewire Volt
- `pages/welcome.blade.php` - Página principal (functional ou class-based)

### 11.2 Componentes Flux UI
- `flux:button` - Botões de ação
- `flux:modal` - Modal de criação
- `flux:input` - Input de nome
- `flux:card` - Container dos contadores (se disponível)
- `flux:badge` - Badge do contador

## 12. Features Futuras (Considerações)

### 12.1 Fase 2 (Possível)
- Autenticação de usuários
- Histórico de incrementos
- Soft delete de contadores
- Comentários em contadores
- Reactions/emojis

### 12.2 Fase 3 (Possível)
- Categorias de contadores
- Contador privados vs. públicos
- Dashboard de analytics
- Exportação de dados
- API pública

## 13. Critérios de Sucesso

### 13.1 Técnicos
- ✅ Todos os testes passando
- ✅ Code coverage > 80%
- ✅ Laravel Pint sem erros
- ✅ Sem N+1 queries
- ✅ Lighthouse performance score > 90

### 13.2 Funcionais
- ✅ Atualizações em tempo real funcionando
- ✅ Ordenação automática funcionando
- ✅ Criação de contadores sem erros
- ✅ Incremento instantâneo e sincronizado

### 13.3 UX
- ✅ Interface intuitiva (teste com usuários)
- ✅ Responsivo em todos os breakpoints
- ✅ Animações suaves e performáticas
- ✅ Feedback claro para todas as ações

## 14. Cronograma Sugerido

### Sprint 1: Fundação (Estimativa: 4-6h)
1. Setup do banco de dados (migration, model, factory, seeder)
2. Implementação do Livewire Volt component principal
3. CRUD básico de contadores (sem tempo real)
4. Testes unitários e feature tests

### Sprint 2: Tempo Real (Estimativa: 3-4h)
1. Configuração do Laravel Reverb
2. Implementação de eventos e broadcasting
3. Integração com Livewire
4. Testes de broadcasting

### Sprint 3: UI/UX (Estimativa: 3-4h)
1. Implementação do design com Tailwind + Flux UI
2. Animações e transições
3. Estados de loading e erro
4. Responsividade

### Sprint 4: Polish (Estimativa: 2-3h)
1. Validações completas
2. Rate limiting
3. Otimizações de performance
4. Browser tests
5. Documentação

## 15. Riscos e Mitigações

| Risco | Probabilidade | Impacto | Mitigação |
|-------|---------------|---------|-----------|
| Reverb não funcionar em produção | Baixa | Alto | Testar em ambiente staging primeiro |
| Performance com muitos usuários | Média | Médio | Implementar rate limiting e caching |
| Spam de incrementos | Alta | Baixo | Throttling e rate limiting |
| Conflitos de ordenação | Baixa | Baixo | Usar updated_at como critério secundário |

## 16. Métricas de Monitoramento

### 16.1 Após Deploy
- Número de contadores criados
- Número de incrementos por hora
- Latência média de atualizações
- Número de usuários simultâneos
- Taxa de erro de WebSocket

## 17. Dependências

### 17.1 Configuração Necessária
- Laravel Reverb instalado e configurado
- Pusher JS instalado (se usar driver Pusher)
- Redis configurado (recomendado para queue)
- Supervisor para workers (produção)

### 17.2 Serviços Externos
- Nenhum necessário para MVP
- Pusher como fallback (opcional)

## 18. Notas de Implementação

### 18.1 Seguir Convenções
- Verificar convenções existentes no projeto
- Usar `php artisan make:*` para todos os arquivos
- Rodar `vendor/bin/pint --dirty` antes de commit
- Escrever testes primeiro (TDD recomendado)

### 18.2 Documentação
- Comentar código complexo com PHPDoc
- Manter README atualizado
- Documentar configuração do Reverb

### 18.3 Git
- Commits atômicos e descritivos
- Branch: `claude/online-test-system-011CV34xztPWhKR1ZyGpgdcs`
- Não fazer push para outras branches

## 19. Glossário

- **Card**: Componente visual que representa um contador
- **Contador**: Entidade que armazena um nome e um número que pode ser incrementado
- **Broadcasting**: Processo de enviar atualizações em tempo real via WebSockets
- **Reverb**: Servidor WebSocket nativo do Laravel
- **Volt**: API funcional/class-based para Livewire
- **Flux UI**: Biblioteca de componentes UI para Livewire

---

**Última atualização**: 2025-11-12
**Versão**: 1.0
**Status**: Aprovação Pendente
