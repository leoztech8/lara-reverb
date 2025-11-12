# Tarefas - Sistema de Contadores em Tempo Real

**Baseado em**: `/docs/PRD.md`
**Branch**: `claude/online-test-system-011CV34xztPWhKR1ZyGpgdcs`
**Data**: 2025-11-12

---

## Índice de Fases

- [Fase 0: Setup e Preparação](#fase-0-setup-e-preparação)
- [Fase 1: Backend - Banco de Dados](#fase-1-backend---banco-de-dados)
- [Fase 2: Backend - Models e Business Logic](#fase-2-backend---models-e-business-logic)
- [Fase 3: Backend - Events e Broadcasting](#fase-3-backend---events-e-broadcasting)
- [Fase 4: Frontend - Componente Base](#fase-4-frontend---componente-base)
- [Fase 5: Frontend - UI/UX Avançado](#fase-5-frontend---uiux-avançado)
- [Fase 6: Testes Automatizados](#fase-6-testes-automatizados)
- [Fase 7: Otimização e Polish](#fase-7-otimização-e-polish)
- [Fase 8: Documentação e Deploy](#fase-8-documentação-e-deploy)

---

## Legenda

- **Complexidade**: 🟢 Baixa | 🟡 Média | 🔴 Alta
- **Estimativa**: Tempo estimado em minutos
- **Depende de**: Tarefas que devem ser concluídas antes
- **✅**: Tarefa concluída

---

## Fase 0: Setup e Preparação

### 0.1 Verificar Instalação do Laravel Reverb
**Complexidade**: 🟢 Baixa
**Estimativa**: 5 min
**Depende de**: -

**Descrição**: Verificar se o Laravel Reverb está instalado no projeto. Se não estiver, instalar via Composer.

**Comandos**:
```bash
composer show laravel/reverb
# Se não instalado: composer require laravel/reverb
```

**Critérios de conclusão**:
- [ ] Laravel Reverb instalado
- [ ] Versão compatível confirmada

---

### 0.2 Configurar Laravel Reverb
**Complexidade**: 🟡 Média
**Estimativa**: 15 min
**Depende de**: 0.1

**Descrição**: Configurar o Laravel Reverb no arquivo `.env` e publicar configurações se necessário.

**Comandos**:
```bash
php artisan reverb:install
php artisan config:clear
```

**Critérios de conclusão**:
- [ ] Arquivo `.env` configurado com credenciais do Reverb
- [ ] Configuração de broadcasting definida
- [ ] Driver de broadcast configurado (reverb ou pusher)

---

### 0.3 Verificar Estrutura do Projeto
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: -

**Descrição**: Verificar estrutura existente do Laravel 12, Livewire 3, Volt, Flux UI e Tailwind v4.

**Verificações**:
- [ ] Livewire 3 instalado e configurado
- [ ] Volt instalado
- [ ] Flux UI Free instalado
- [ ] Tailwind CSS v4 configurado
- [ ] Alpine.js disponível (vem com Livewire)

---

### 0.4 Verificar Convenções do Projeto
**Complexidade**: 🟢 Baixa
**Estimativa**: 15 min
**Depende de**: -

**Descrição**: Examinar componentes Volt existentes para determinar se o projeto usa functional ou class-based approach.

**Ações**:
- [ ] Verificar arquivos em `resources/views/pages/` ou `resources/views/livewire/`
- [ ] Identificar padrão: functional (`state()`, `computed()`) ou class-based
- [ ] Verificar uso de Flux UI em componentes existentes
- [ ] Identificar padrões de validação (array vs string based)

---

## Fase 1: Backend - Banco de Dados

### 1.1 Criar Migration para Tabela `counters`
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: -

**Descrição**: Criar migration para a tabela `counters` com todos os campos necessários.

**Comandos**:
```bash
php artisan make:migration create_counters_table
```

**Estrutura da tabela**:
```php
- id: bigint (PK, auto-increment)
- name: string (unique, max 50)
- count: integer (default 0, unsigned)
- created_at: timestamp
- updated_at: timestamp
```

**Critérios de conclusão**:
- [ ] Migration criada em `database/migrations/`
- [ ] Campos definidos corretamente com tipos e constraints
- [ ] Index em `name` para performance de unicidade
- [ ] Index em `count` para ordenação rápida

---

### 1.2 Criar Model `Counter`
**Complexidade**: 🟢 Baixa
**Estimativa**: 15 min
**Depende de**: 1.1

**Descrição**: Criar o model `Counter` com casts, fillable, e estrutura preparada para eventos.

**Comandos**:
```bash
php artisan make:model Counter
```

**Configurações do Model**:
- [ ] `$fillable = ['name', 'count']`
- [ ] Casts definidos (count como integer)
- [ ] PHPDoc com type hints
- [ ] Preparado para disparar eventos

**Critérios de conclusão**:
- [ ] Model criado em `app/Models/Counter.php`
- [ ] Documentação PHPDoc completa
- [ ] Seguindo convenções do projeto

---

### 1.3 Criar Factory para `Counter`
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 1.2

**Descrição**: Criar factory para gerar dados fake de contadores para testes.

**Comandos**:
```bash
php artisan make:factory CounterFactory
```

**Configurações**:
- [ ] Nome único usando `fake()->unique()->word()`
- [ ] Count aleatório entre 0 e 1000
- [ ] States úteis (ex: `withHighCount()`, `withZeroCount()`)

**Critérios de conclusão**:
- [ ] Factory criada em `database/factories/CounterFactory.php`
- [ ] Dados realistas gerados
- [ ] States customizados criados

---

### 1.4 Criar Seeder para `Counter`
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 1.3

**Descrição**: Criar seeder para popular banco com dados iniciais de desenvolvimento.

**Comandos**:
```bash
php artisan make:seeder CounterSeeder
```

**Configurações**:
- [ ] Criar 10-15 contadores com contagens variadas
- [ ] Alguns com count alto, outros baixo
- [ ] Adicionar ao `DatabaseSeeder`

**Critérios de conclusão**:
- [ ] Seeder criado em `database/seeders/CounterSeeder.php`
- [ ] Chamado no `DatabaseSeeder.php`
- [ ] Testado com `php artisan db:seed`

---

### 1.5 Executar Migrations
**Complexidade**: 🟢 Baixa
**Estimativa**: 5 min
**Depende de**: 1.1

**Descrição**: Executar migrations e popular banco com dados iniciais.

**Comandos**:
```bash
php artisan migrate:fresh --seed
```

**Critérios de conclusão**:
- [ ] Tabela `counters` criada no banco
- [ ] Seeds executados com sucesso
- [ ] Dados visíveis no banco via Tinker ou DB tool

---

## Fase 2: Backend - Models e Business Logic

### 2.1 Criar Form Request para Validação de Criação
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 1.2

**Descrição**: Criar Form Request para validar criação de novos contadores.

**Comandos**:
```bash
php artisan make:request StoreCounterRequest
```

**Regras de Validação**:
```php
- name: required, string, max:50, unique:counters,name
```

**Configurações**:
- [ ] Regras definidas no método `rules()`
- [ ] Mensagens customizadas em português no método `messages()`
- [ ] Autorização no método `authorize()` (return true para público)
- [ ] Verificar convenção do projeto (array vs string based rules)

**Critérios de conclusão**:
- [ ] Request criado em `app/Http/Requests/StoreCounterRequest.php`
- [ ] Validações completas e testadas
- [ ] Mensagens em português

---

### 2.2 Criar Action/Service para Criar Counter
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 2.1

**Descrição**: Criar uma classe de ação para encapsular lógica de criação de contador.

**Comandos**:
```bash
php artisan make:class Actions/CreateCounter
```

**Responsabilidades**:
- [ ] Receber nome validado
- [ ] Criar counter com count = 0
- [ ] Disparar evento `CounterCreated`
- [ ] Retornar counter criado

**Critérios de conclusão**:
- [ ] Classe criada em `app/Actions/CreateCounter.php`
- [ ] Método `execute()` ou `handle()` implementado
- [ ] Type hints e return types definidos
- [ ] PHPDoc completo

---

### 2.3 Criar Action/Service para Incrementar Counter
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 1.2

**Descrição**: Criar uma classe de ação para encapsular lógica de incremento de contador.

**Comandos**:
```bash
php artisan make:class Actions/IncrementCounter
```

**Responsabilidades**:
- [ ] Receber ID do counter
- [ ] Validar existência
- [ ] Incrementar counter em 1
- [ ] Atualizar `updated_at`
- [ ] Disparar evento `CounterIncremented`
- [ ] Retornar counter atualizado

**Critérios de conclusão**:
- [ ] Classe criada em `app/Actions/IncrementCounter.php`
- [ ] Lógica de incremento atômica (usar `increment()` do Eloquent)
- [ ] Type hints e return types definidos
- [ ] PHPDoc completo

---

### 2.4 Adicionar Scopes no Model Counter
**Complexidade**: 🟢 Baixa
**Estimativa**: 15 min
**Depende de**: 1.2

**Descrição**: Adicionar query scopes úteis no model `Counter` para ordenação e filtragem.

**Scopes a criar**:
```php
- scopeOrdered($query): ordena por count DESC, name ASC
- scopeWithHighCount($query, $threshold = 100): filtra counts altos
```

**Critérios de conclusão**:
- [ ] Scopes adicionados ao model `Counter`
- [ ] Documentação PHPDoc para cada scope
- [ ] Testado via Tinker

---

## Fase 3: Backend - Events e Broadcasting

### 3.1 Criar Event `CounterCreated`
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 1.2

**Descrição**: Criar evento que será disparado quando um novo contador for criado.

**Comandos**:
```bash
php artisan make:event CounterCreated
```

**Configurações**:
- [ ] Implementar interface `ShouldBroadcast`
- [ ] Definir propriedade pública `$counter`
- [ ] Definir método `broadcastOn()` retornando `new Channel('counters')`
- [ ] Definir método `broadcastWith()` retornando array com dados do counter
- [ ] Definir `broadcastAs()` como `'counter.created'`

**Payload de broadcast**:
```php
[
    'id' => $counter->id,
    'name' => $counter->name,
    'count' => $counter->count,
    'updated_at' => $counter->updated_at->toISOString(),
]
```

**Critérios de conclusão**:
- [ ] Event criado em `app/Events/CounterCreated.php`
- [ ] Broadcasting configurado corretamente
- [ ] Payload otimizado (apenas dados necessários)

---

### 3.2 Criar Event `CounterIncremented`
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 1.2

**Descrição**: Criar evento que será disparado quando um contador for incrementado.

**Comandos**:
```bash
php artisan make:event CounterIncremented
```

**Configurações**:
- [ ] Implementar interface `ShouldBroadcast`
- [ ] Definir propriedade pública `$counter`
- [ ] Definir método `broadcastOn()` retornando `new Channel('counters')`
- [ ] Definir método `broadcastWith()` retornando array com dados atualizados
- [ ] Definir `broadcastAs()` como `'counter.incremented'`

**Payload de broadcast**:
```php
[
    'id' => $counter->id,
    'name' => $counter->name,
    'count' => $counter->count,
    'updated_at' => $counter->updated_at->toISOString(),
]
```

**Critérios de conclusão**:
- [ ] Event criado em `app/Events/CounterIncremented.php`
- [ ] Broadcasting configurado corretamente
- [ ] Payload otimizado

---

### 3.3 Integrar Events nas Actions
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 2.2, 2.3, 3.1, 3.2

**Descrição**: Adicionar dispatch de eventos nas actions criadas anteriormente.

**Modificações**:
- [ ] `CreateCounter`: adicionar `event(new CounterCreated($counter))`
- [ ] `IncrementCounter`: adicionar `event(new CounterIncremented($counter))`

**Critérios de conclusão**:
- [ ] Eventos disparados corretamente
- [ ] Testado via logs ou Tinker

---

### 3.4 Configurar Broadcasting Routes
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 3.1, 3.2

**Descrição**: Verificar e configurar rotas de broadcasting no Laravel.

**Verificações**:
- [ ] Arquivo `routes/channels.php` existe
- [ ] Channel público `counters` definido (sem autenticação)
- [ ] Broadcasting routes carregadas em `bootstrap/app.php`

**Configuração do Channel**:
```php
Broadcast::channel('counters', function () {
    return true; // Canal público
});
```

**Critérios de conclusão**:
- [ ] Canal `counters` configurado e público
- [ ] Rotas de broadcasting ativas

---

### 3.5 Testar Broadcasting Manualmente
**Complexidade**: 🟡 Média
**Estimativa**: 15 min
**Depende de**: 3.1, 3.2, 3.3, 3.4

**Descrição**: Iniciar servidor Reverb e testar se eventos estão sendo broadcast corretamente.

**Comandos**:
```bash
php artisan reverb:start
# Em outro terminal:
php artisan tinker
# Counter::create(['name' => 'Test']);
```

**Verificações**:
- [ ] Servidor Reverb inicia sem erros
- [ ] Logs mostram eventos sendo broadcast
- [ ] Payload correto sendo enviado

**Critérios de conclusão**:
- [ ] Broadcasting funcionando via Reverb
- [ ] Eventos visíveis nos logs do Reverb

---

## Fase 4: Frontend - Componente Base

### 4.1 Criar Componente Volt Principal
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 0.4, 1.2, 2.2, 2.3

**Descrição**: Criar componente Livewire Volt para a página principal. Seguir convenção do projeto (functional ou class-based).

**Comandos**:
```bash
php artisan make:volt pages/welcome --test --pest
```

**Estrutura do Componente** (ajustar conforme convenção):

**Se Functional**:
```php
use function Livewire\Volt\{state, computed, on};

state(['showModal' => false, 'newCounterName' => '']);

$counters = computed(fn() => Counter::ordered()->get());

$createCounter = function() {
    // Lógica de criação
};

$incrementCounter = function(int $id) {
    // Lógica de incremento
};
```

**Se Class-based**:
```php
new class extends Component {
    public bool $showModal = false;
    public string $newCounterName = '';

    public function getCountersProperty() { ... }
    public function createCounter() { ... }
    public function incrementCounter(int $id) { ... }
}
```

**Funcionalidades**:
- [ ] Carregar lista de contadores ordenados
- [ ] Método `createCounter()` para criar novos
- [ ] Método `incrementCounter(int $id)` para incrementar
- [ ] Estado para controlar modal
- [ ] Validação inline do nome

**Critérios de conclusão**:
- [ ] Componente criado em `resources/views/pages/welcome.blade.php`
- [ ] Lógica funcional implementada
- [ ] Integração com Actions criadas
- [ ] Seguindo convenções do projeto

---

### 4.2 Criar HTML/Blade Base do Componente
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 4.1

**Descrição**: Criar estrutura HTML básica do componente usando Flux UI components.

**Estrutura**:
```blade
@volt
<?php
// Lógica PHP aqui
?>

<div>
    {{-- Header --}}
    <div class="header">
        <flux:heading>Contadores em Tempo Real</flux:heading>
        <flux:button wire:click="$set('showModal', true)">
            Novo Contador
        </flux:button>
    </div>

    {{-- Grid de Cards --}}
    <div class="grid">
        @forelse($this->counters as $counter)
            <div wire:key="counter-{{ $counter->id }}">
                {{-- Card do contador --}}
            </div>
        @empty
            {{-- Empty state --}}
        @endforelse
    </div>

    {{-- Modal de criação --}}
    <flux:modal wire:model="showModal">
        {{-- Formulário --}}
    </flux:modal>
</div>
@endvolt
```

**Componentes Flux UI a usar**:
- [ ] `flux:heading` - Título principal
- [ ] `flux:button` - Botões de ação
- [ ] `flux:modal` - Modal de criação
- [ ] `flux:input` - Input do nome
- [ ] `flux:badge` ou card customizado - Container dos contadores

**Critérios de conclusão**:
- [ ] HTML estruturado e semântico
- [ ] Flux UI components utilizados
- [ ] `wire:key` em loops
- [ ] Empty state implementado

---

### 4.3 Implementar Validação no Componente
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 4.1, 2.1

**Descrição**: Adicionar validação no componente Volt para criação de contador.

**Validação**:
```php
$rules = [
    'newCounterName' => 'required|string|max:50|unique:counters,name'
];

$messages = [
    'newCounterName.required' => 'O nome é obrigatório.',
    'newCounterName.max' => 'O nome não pode ter mais de 50 caracteres.',
    'newCounterName.unique' => 'Já existe um contador com este nome.',
];
```

**Implementações**:
- [ ] Adicionar validação no método `createCounter()`
- [ ] Usar `$this->validate()` ou Form Request
- [ ] Exibir erros com `@error` no Blade
- [ ] Limpar formulário após sucesso
- [ ] Fechar modal após criação

**Critérios de conclusão**:
- [ ] Validação funcionando
- [ ] Mensagens de erro em português
- [ ] UX suave (formulário limpa, modal fecha)

---

### 4.4 Integrar Echo/Reverb no Frontend
**Complexidade**: 🔴 Alta
**Estimativa**: 45 min
**Depende de**: 3.5, 4.1

**Descrição**: Configurar Laravel Echo no frontend para escutar eventos do Reverb em tempo real.

**Instalação** (se necessário):
```bash
npm install --save-dev laravel-echo pusher-js
```

**Configuração** (em `resources/js/app.js` ou similar):
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});
```

**Integração no Componente Volt**:
```php
on([
    'echo:counters,.counter.created' => function($event) {
        // Refresh counters
    },
    'echo:counters,.counter.incremented' => function($event) {
        // Refresh counters
    }
]);
```

**Verificações**:
- [ ] Echo configurado corretamente
- [ ] Conexão com Reverb estabelecida
- [ ] Listeners de eventos registrados
- [ ] Componente atualiza quando eventos chegam

**Critérios de conclusão**:
- [ ] Echo conectado ao Reverb
- [ ] Eventos recebidos em tempo real
- [ ] UI atualiza automaticamente
- [ ] Múltiplas abas sincronizam

---

### 4.5 Implementar Refresh Automático via Echo
**Complexidade**: 🟡 Média
**Estimativa**: 25 min
**Depende de**: 4.4

**Descrição**: Implementar lógica para atualizar a lista de contadores quando eventos de broadcasting chegarem.

**Abordagens possíveis**:

**Opção 1: Re-render completo**
```php
on([
    'echo:counters,.counter.created' => '$refresh',
    'echo:counters,.counter.incremented' => '$refresh',
]);
```

**Opção 2: Atualização seletiva (melhor performance)**
```php
on([
    'echo:counters,.counter.created' => function($event) {
        // Adicionar novo counter ao array
    },
    'echo:counters,.counter.incremented' => function($event) {
        // Atualizar counter específico
    }
]);
```

**Implementações**:
- [ ] Escolher abordagem (recomendar Opção 2)
- [ ] Atualizar lista de contadores
- [ ] Manter ordenação correta
- [ ] Evitar flickering

**Critérios de conclusão**:
- [ ] Atualizações em tempo real funcionando
- [ ] Performance boa (sem lag)
- [ ] Ordenação mantida após updates

---

### 4.6 Definir Rota Principal
**Complexidade**: 🟢 Baixa
**Estimativa**: 5 min
**Depende de**: 4.1

**Descrição**: Configurar rota principal para exibir o componente Volt.

**Arquivo**: `routes/web.php`

```php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.welcome')->name('home');
```

**Critérios de conclusão**:
- [ ] Rota `/` configurada
- [ ] Componente Volt renderiza corretamente
- [ ] Acessível via navegador

---

## Fase 5: Frontend - UI/UX Avançado

### 5.1 Estilizar Header com Tailwind
**Complexidade**: 🟢 Baixa
**Estimativa**: 20 min
**Depende de**: 4.2

**Descrição**: Aplicar estilos Tailwind CSS v4 ao header da página.

**Estilos a aplicar**:
- [ ] Layout flex com justify-between
- [ ] Padding e margin generosos
- [ ] Título grande e bold
- [ ] Botão com hover states
- [ ] Responsividade (mobile: stack vertical, desktop: horizontal)

**Classes Tailwind sugeridas**:
```blade
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-6">
    <flux:heading size="xl" class="text-3xl font-bold">
        Contadores em Tempo Real
    </flux:heading>
    <flux:button variant="primary" size="lg">
        Novo Contador
    </flux:button>
</div>
```

**Critérios de conclusão**:
- [ ] Header estilizado e moderno
- [ ] Responsivo em mobile e desktop
- [ ] Seguindo design system do Tailwind v4

---

### 5.2 Estilizar Cards de Contadores
**Complexidade**: 🟡 Média
**Estimativa**: 40 min
**Depende de**: 4.2

**Descrição**: Criar design sofisticado e elegante para os cards de contadores.

**Design do Card**:
```blade
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow">
    {{-- Número grande --}}
    <div class="text-6xl font-bold text-center mb-4 text-blue-600">
        {{ $counter->count }}
    </div>

    {{-- Nome --}}
    <div class="text-lg text-center text-gray-700 dark:text-gray-300 mb-4">
        {{ $counter->name }}
    </div>

    {{-- Botão de incremento --}}
    <flux:button
        wire:click="incrementCounter({{ $counter->id }})"
        variant="primary"
        class="w-full"
        wire:loading.attr="disabled"
    >
        <span wire:loading.remove wire:target="incrementCounter({{ $counter->id }})">
            + Incrementar
        </span>
        <span wire:loading wire:target="incrementCounter({{ $counter->id }})">
            Incrementando...
        </span>
    </flux:button>

    {{-- Timestamp (opcional) --}}
    <div class="text-xs text-gray-500 text-center mt-2">
        {{ $counter->updated_at->diffForHumans() }}
    </div>
</div>
```

**Estilos**:
- [ ] Sombras sutis com hover effect
- [ ] Número grande e destacado (text-6xl)
- [ ] Espaçamento generoso
- [ ] Bordas arredondadas (rounded-2xl)
- [ ] Loading state no botão
- [ ] Suporte a dark mode

**Critérios de conclusão**:
- [ ] Cards visualmente atraentes
- [ ] Hierarquia visual clara
- [ ] Feedback visual em hover e loading
- [ ] Dark mode suportado (se projeto usar)

---

### 5.3 Implementar Grid Responsivo
**Complexidade**: 🟡 Média
**Estimativa**: 25 min
**Depende de**: 5.2

**Descrição**: Criar grid responsivo que adapta número de colunas baseado no tamanho da tela.

**Grid Layout**:
```blade
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
    @foreach($this->counters as $counter)
        <div wire:key="counter-{{ $counter->id }}">
            {{-- Card aqui --}}
        </div>
    @endforeach
</div>
```

**Breakpoints**:
- Mobile (< 640px): 1 coluna
- Tablet (640px - 1024px): 2 colunas
- Desktop (1024px - 1280px): 3 colunas
- Large Desktop (> 1280px): 4 colunas

**Critérios de conclusão**:
- [ ] Grid responsivo funcionando
- [ ] Gap uniforme entre cards
- [ ] Boa distribuição em todos os breakpoints

---

### 5.4 Estilizar Modal de Criação
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 4.2, 4.3

**Descrição**: Estilizar modal de criação com Flux UI e Tailwind.

**Design do Modal**:
```blade
<flux:modal wire:model="showModal" class="max-w-md">
    <flux:heading size="lg" class="mb-4">Criar Novo Contador</flux:heading>

    <form wire:submit="createCounter" class="space-y-4">
        <flux:field>
            <flux:label>Nome do Contador</flux:label>
            <flux:input
                wire:model.live="newCounterName"
                placeholder="Digite o nome..."
                maxlength="50"
                autofocus
            />
            @error('newCounterName')
                <flux:error>{{ $message }}</flux:error>
            @enderror
        </flux:field>

        <div class="flex gap-3 justify-end">
            <flux:button
                type="button"
                variant="ghost"
                wire:click="$set('showModal', false)"
            >
                Cancelar
            </flux:button>
            <flux:button
                type="submit"
                variant="primary"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Criar</span>
                <span wire:loading>Criando...</span>
            </flux:button>
        </div>
    </form>
</flux:modal>
```

**Estilos**:
- [ ] Modal centralizado
- [ ] Espaçamento generoso
- [ ] Botões alinhados à direita
- [ ] Loading states
- [ ] Erros destacados em vermelho

**Critérios de conclusão**:
- [ ] Modal estilizado e elegante
- [ ] Formulário intuitivo
- [ ] Feedback visual claro

---

### 5.5 Implementar Empty State
**Complexidade**: 🟢 Baixa
**Estimativa**: 20 min
**Depende de**: 4.2

**Descrição**: Criar estado vazio elegante quando não há contadores.

**Design**:
```blade
@forelse($this->counters as $counter)
    {{-- Cards aqui --}}
@empty
    <div class="flex flex-col items-center justify-center py-20 px-6">
        <div class="text-gray-400 mb-4">
            <svg class="w-24 h-24" ...>{{-- Ícone --}}</svg>
        </div>
        <flux:heading size="lg" class="mb-2 text-gray-600">
            Nenhum contador ainda
        </flux:heading>
        <p class="text-gray-500 mb-6 text-center">
            Crie seu primeiro contador para começar a testar o Reverb!
        </p>
        <flux:button
            variant="primary"
            size="lg"
            wire:click="$set('showModal', true)"
        >
            Criar Primeiro Contador
        </flux:button>
    </div>
@endforelse
```

**Elementos**:
- [ ] Ícone ilustrativo (SVG)
- [ ] Mensagem convidativa
- [ ] CTA destacado
- [ ] Centralizado vertical e horizontalmente

**Critérios de conclusão**:
- [ ] Empty state implementado
- [ ] Design amigável e convidativo
- [ ] CTA funcional

---

### 5.6 Adicionar Animações de Entrada/Saída
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 5.2, 5.3

**Descrição**: Adicionar animações suaves usando Tailwind e Alpine.js para transições de cards.

**Técnicas**:

**Fade in ao carregar**:
```blade
<div
    x-data="{ show: false }"
    x-init="$nextTick(() => show = true)"
    x-show="show"
    x-transition
    class="..."
>
```

**Pulse ao incrementar**:
```blade
<div
    x-data="{ pulse: false }"
    @counter-incremented.window="if ($event.detail.id === {{ $counter->id }}) { pulse = true; setTimeout(() => pulse = false, 1000) }"
    :class="{ 'animate-pulse': pulse }"
>
```

**Implementações**:
- [ ] Fade in inicial dos cards
- [ ] Pulse/glow ao incrementar
- [ ] Reordenação suave (flip animation)
- [ ] Modal com transição fade

**Critérios de conclusão**:
- [ ] Animações implementadas
- [ ] Transições suaves (não bruscas)
- [ ] Performance mantida (60fps)

---

### 5.7 Adicionar Loading States Globais
**Complexidade**: 🟢 Baixa
**Estimativa**: 15 min
**Depende de**: 4.1

**Descrição**: Adicionar indicadores de loading durante ações Livewire.

**Implementações**:
```blade
{{-- Loading overlay --}}
<div wire:loading class="fixed inset-0 bg-black/20 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 shadow-xl">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>
</div>

{{-- Loading nos botões --}}
<flux:button wire:loading.attr="disabled">
    <span wire:loading.remove>Texto Normal</span>
    <span wire:loading>Carregando...</span>
</flux:button>
```

**Critérios de conclusão**:
- [ ] Loading states em todos os botões
- [ ] Indicador global (opcional)
- [ ] Botões desabilitados durante loading

---

### 5.8 Implementar Feedback Visual para Ações
**Complexidade**: 🟡 Média
**Estimativa**: 25 min
**Depende de**: 4.1, 5.2

**Descrição**: Adicionar feedback visual (toasts, notificações) para ações bem-sucedidas ou com erro.

**Opções**:

**Opção 1: Usar Flux UI notifications (se disponível)**
```php
$this->notify('Contador criado com sucesso!');
```

**Opção 2: Custom toast com Alpine.js**
```blade
<div
    x-data="{ show: false, message: '' }"
    @notify.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition
    class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
    x-text="message"
></div>
```

**Implementações**:
- [ ] Toast ao criar contador
- [ ] Feedback visual ao incrementar
- [ ] Mensagens de erro elegantes

**Critérios de conclusão**:
- [ ] Feedback implementado para todas as ações
- [ ] Mensagens claras e em português
- [ ] Auto-dismiss após 3s

---

### 5.9 Adicionar Indicador de Conexão Reverb
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 4.4

**Descrição**: Mostrar status da conexão WebSocket (conectado/desconectado).

**Design**:
```blade
<div
    x-data="{ connected: false }"
    @connect.window="connected = true"
    @disconnect.window="connected = false"
    class="fixed bottom-4 left-4 z-50"
>
    <div
        :class="connected ? 'bg-green-500' : 'bg-red-500'"
        class="flex items-center gap-2 text-white px-4 py-2 rounded-full shadow-lg text-sm"
    >
        <div
            :class="connected ? 'animate-pulse' : ''"
            class="w-2 h-2 bg-white rounded-full"
        ></div>
        <span x-text="connected ? 'Conectado' : 'Desconectado'"></span>
    </div>
</div>
```

**Listeners Echo**:
```javascript
window.Echo.connector.pusher.connection.bind('connected', () => {
    window.dispatchEvent(new CustomEvent('connect'));
});

window.Echo.connector.pusher.connection.bind('disconnected', () => {
    window.dispatchEvent(new CustomEvent('disconnect'));
});
```

**Critérios de conclusão**:
- [ ] Indicador implementado
- [ ] Muda cor baseado no status
- [ ] Posicionado no canto inferior esquerdo

---

### 5.10 Polir Responsividade e Acessibilidade
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 5.1, 5.2, 5.3, 5.4

**Descrição**: Garantir que todo o componente é responsivo e acessível.

**Verificações de Responsividade**:
- [ ] Mobile (320px - 640px): layout vertical, touch-friendly
- [ ] Tablet (640px - 1024px): grid 2 colunas
- [ ] Desktop (> 1024px): grid 3-4 colunas
- [ ] Textos legíveis em todos os tamanhos
- [ ] Botões com área de toque adequada (min 44x44px)

**Verificações de Acessibilidade**:
- [ ] Landmarks ARIA (`role="main"`, etc.)
- [ ] Labels em inputs (`<label>` ou `aria-label`)
- [ ] Contraste de cores (WCAG AA)
- [ ] Navegação por teclado (Tab, Enter, Esc)
- [ ] Screen reader friendly
- [ ] Focus states visíveis

**Critérios de conclusão**:
- [ ] Responsivo em todos os breakpoints testado
- [ ] Acessibilidade verificada (Lighthouse > 90)
- [ ] Navegação por teclado funcional

---

## Fase 6: Testes Automatizados

### 6.1 Criar Testes Unitários para Model Counter
**Complexidade**: 🟢 Baixa
**Estimativa**: 20 min
**Depende de**: 1.2, 2.4

**Descrição**: Criar testes unitários para o model `Counter`.

**Comandos**:
```bash
php artisan make:test --unit CounterTest --pest
```

**Testes a criar**:
```php
it('can create a counter', function() { ... });
it('has default count of zero', function() { ... });
it('can increment count', function() { ... });
it('orders by count descending then name ascending', function() { ... });
it('validates name uniqueness', function() { ... });
```

**Critérios de conclusão**:
- [ ] Arquivo criado em `tests/Unit/CounterTest.php`
- [ ] Mínimo 5 testes cobrindo casos principais
- [ ] Todos os testes passando

---

### 6.2 Criar Testes Feature para Actions
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 2.2, 2.3

**Descrição**: Criar testes feature para as actions `CreateCounter` e `IncrementCounter`.

**Comandos**:
```bash
php artisan make:test --pest Actions/CreateCounterTest
php artisan make:test --pest Actions/IncrementCounterTest
```

**Testes `CreateCounterTest`**:
```php
it('creates a counter with valid name', function() { ... });
it('fails with duplicate name', function() { ... });
it('fails with empty name', function() { ... });
it('fails with name longer than 50 chars', function() { ... });
it('dispatches CounterCreated event', function() { ... });
```

**Testes `IncrementCounterTest`**:
```php
it('increments counter by one', function() { ... });
it('updates updated_at timestamp', function() { ... });
it('fails with invalid counter id', function() { ... });
it('dispatches CounterIncremented event', function() { ... });
```

**Critérios de conclusão**:
- [ ] Arquivos criados em `tests/Feature/Actions/`
- [ ] Testes cobrindo happy path e edge cases
- [ ] Todos os testes passando

---

### 6.3 Criar Testes Feature para Validação
**Complexidade**: 🟡 Média
**Estimativa**: 25 min
**Depende de**: 2.1

**Descrição**: Criar testes para Form Request `StoreCounterRequest`.

**Comandos**:
```bash
php artisan make:test --pest StoreCounterRequestTest
```

**Testes usando datasets**:
```php
it('validates counter name', function (string $name, bool $shouldPass) {
    $data = ['name' => $name];

    if ($shouldPass) {
        expect((new StoreCounterRequest())->validate($data))->toPass();
    } else {
        expect((new StoreCounterRequest())->validate($data))->toFail();
    }
})->with([
    'valid name' => ['Test Counter', true],
    'empty name' => ['', false],
    'too long name' => [str_repeat('a', 51), false],
    'duplicate name' => ['Existing', false], // Criar counter antes
]);
```

**Critérios de conclusão**:
- [ ] Arquivo criado em `tests/Feature/StoreCounterRequestTest.php`
- [ ] Datasets utilizados para múltiplos casos
- [ ] Todos os testes passando

---

### 6.4 Criar Testes Feature para Broadcasting
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 3.1, 3.2

**Descrição**: Criar testes para verificar que eventos estão sendo broadcast corretamente.

**Comandos**:
```bash
php artisan make:test --pest Broadcasting/CounterEventsTest
```

**Testes**:
```php
use Illuminate\Support\Facades\Event;

it('broadcasts CounterCreated event when counter is created', function() {
    Event::fake();

    $action = new CreateCounter();
    $counter = $action->execute('Test Counter');

    Event::assertDispatched(CounterCreated::class, function($event) use ($counter) {
        return $event->counter->id === $counter->id;
    });
});

it('broadcasts CounterIncremented event when counter is incremented', function() {
    Event::fake();

    $counter = Counter::factory()->create();
    $action = new IncrementCounter();
    $action->execute($counter->id);

    Event::assertDispatched(CounterIncremented::class);
});

it('broadcasts to counters channel', function() {
    $event = new CounterCreated(Counter::factory()->create());

    expect($event->broadcastOn())->toBeArray()
        ->and($event->broadcastOn()[0]->name)->toBe('counters');
});
```

**Critérios de conclusão**:
- [ ] Arquivo criado em `tests/Feature/Broadcasting/CounterEventsTest.php`
- [ ] Eventos testados com `Event::fake()`
- [ ] Channels verificados
- [ ] Todos os testes passando

---

### 6.5 Criar Testes Feature para Componente Volt
**Complexidade**: 🔴 Alta
**Estimativa**: 45 min
**Depende de**: 4.1, 4.3

**Descrição**: Criar testes feature completos para o componente Volt principal.

**Comandos**:
```bash
# Teste já criado com --test flag em 4.1
# Editar: tests/Feature/Volt/WelcomeTest.php
```

**Testes**:
```php
use Livewire\Volt\Volt;

it('renders welcome page', function() {
    Volt::test('pages.welcome')
        ->assertSee('Contadores em Tempo Real')
        ->assertStatus(200);
});

it('displays all counters ordered by count', function() {
    Counter::factory()->create(['name' => 'Low', 'count' => 10]);
    Counter::factory()->create(['name' => 'High', 'count' => 100]);

    Volt::test('pages.welcome')
        ->assertSeeInOrder(['100', '10']); // High count primeiro
});

it('can create a new counter', function() {
    Volt::test('pages.welcome')
        ->set('newCounterName', 'Test Counter')
        ->call('createCounter')
        ->assertHasNoErrors();

    expect(Counter::where('name', 'Test Counter')->exists())->toBeTrue();
});

it('validates counter name on creation', function() {
    Volt::test('pages.welcome')
        ->set('newCounterName', '')
        ->call('createCounter')
        ->assertHasErrors(['newCounterName' => 'required']);
});

it('can increment a counter', function() {
    $counter = Counter::factory()->create(['count' => 5]);

    Volt::test('pages.welcome')
        ->call('incrementCounter', $counter->id)
        ->assertHasNoErrors();

    expect($counter->fresh()->count)->toBe(6);
});

it('shows empty state when no counters exist', function() {
    Volt::test('pages.welcome')
        ->assertSee('Nenhum contador ainda');
});
```

**Critérios de conclusão**:
- [ ] Arquivo em `tests/Feature/Volt/WelcomeTest.php`
- [ ] Mínimo 6 testes cobrindo funcionalidades principais
- [ ] Todos os testes passando

---

### 6.6 Criar Testes Browser (Pest v4)
**Complexidade**: 🔴 Alta
**Estimativa**: 60 min
**Depende de**: 4.4, 5.6

**Descrição**: Criar testes browser para verificar fluxo completo incluindo tempo real.

**Comandos**:
```bash
php artisan make:test --pest --browser CounterFlowTest
```

**Testes**:
```php
it('can create and increment counter in real browser', function() {
    Counter::factory()->create(['name' => 'Existing', 'count' => 50]);

    $page = visit('/');

    $page->assertSee('Contadores em Tempo Real')
        ->assertSee('Existing')
        ->assertSee('50')
        ->assertNoJavascriptErrors();

    // Criar novo contador
    $page->click('Novo Contador')
        ->waitFor('modal') // ou seletor específico
        ->fill('newCounterName', 'Browser Test')
        ->click('Criar')
        ->assertSee('Browser Test')
        ->assertSee('0');

    // Incrementar
    $page->click('+ Incrementar') // botão do novo counter
        ->waitForText('1')
        ->assertSee('1');
});

it('shows real-time updates across multiple sessions', function() {
    $counter = Counter::factory()->create(['name' => 'Shared', 'count' => 0]);

    $page1 = visit('/');
    $page2 = visit('/');

    // Incrementar na página 1
    $page1->click('+ Incrementar')
        ->waitForText('1');

    // Verificar atualização na página 2
    $page2->waitForText('1')
        ->assertSee('1');
});

it('maintains counter order after increments', function() {
    Counter::factory()->create(['name' => 'First', 'count' => 10]);
    Counter::factory()->create(['name' => 'Second', 'count' => 5]);

    $page = visit('/');

    $page->assertSeeInOrder(['First', 'Second']);

    // Incrementar Second para ultrapassar First
    // Clicar 6 vezes (5 -> 11)
    for ($i = 0; $i < 6; $i++) {
        $page->click('+ Incrementar'); // botão do Second
    }

    $page->waitFor(500) // Esperar reordenação
        ->assertSeeInOrder(['Second', 'First']);
});
```

**Critérios de conclusão**:
- [ ] Arquivo criado em `tests/Browser/CounterFlowTest.php`
- [ ] Testes de tempo real funcionando
- [ ] Multi-session testado
- [ ] Todos os testes passando

---

### 6.7 Rodar Todos os Testes e Verificar Coverage
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6

**Descrição**: Executar suite completa de testes e verificar cobertura de código.

**Comandos**:
```bash
php artisan test
php artisan test --coverage
php artisan test --coverage --min=80
```

**Verificações**:
- [ ] Todos os testes unitários passando
- [ ] Todos os testes feature passando
- [ ] Todos os testes browser passando
- [ ] Code coverage > 80%

**Critérios de conclusão**:
- [ ] 100% dos testes passando
- [ ] Cobertura mínima de 80% atingida
- [ ] Sem warnings ou deprecations

---

## Fase 7: Otimização e Polish

### 7.1 Implementar Rate Limiting
**Complexidade**: 🟡 Média
**Estimativa**: 25 min
**Depende de**: 2.2, 2.3

**Descrição**: Adicionar rate limiting para prevenir abuso de criação e incremento.

**Implementação no Componente Volt**:
```php
use Illuminate\Support\Facades\RateLimiter;

public function incrementCounter(int $id)
{
    $key = 'increment-counter:' . request()->ip();

    if (RateLimiter::tooManyAttempts($key, 10)) {
        $this->addError('rate_limit', 'Muitos incrementos. Aguarde um momento.');
        return;
    }

    RateLimiter::hit($key, 60); // 10 por minuto

    // Lógica de incremento...
}
```

**Configurações**:
- [ ] Incremento: max 10 por minuto por IP
- [ ] Criação: max 5 por minuto por IP
- [ ] Mensagem de erro amigável

**Critérios de conclusão**:
- [ ] Rate limiting implementado
- [ ] Testado com múltiplas requisições
- [ ] Mensagens de erro claras

---

### 7.2 Adicionar Indexes no Banco de Dados
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 1.1

**Descrição**: Adicionar indexes para otimizar queries de ordenação.

**Comandos**:
```bash
php artisan make:migration add_indexes_to_counters_table
```

**Indexes a adicionar**:
```php
$table->index('count'); // Para ordenação por count
$table->index(['count', 'name']); // Composite index para ordenação completa
```

**Critérios de conclusão**:
- [ ] Migration criada e executada
- [ ] Indexes verificados no banco
- [ ] Performance de queries melhorada

---

### 7.3 Otimizar Queries (Evitar N+1)
**Complexidade**: 🟢 Baixa
**Estimativa**: 15 min
**Depende de**: 4.1

**Descrição**: Verificar e otimizar queries do componente Volt para evitar N+1.

**Verificações**:
```php
// No componente Volt
$counters = computed(function() {
    return Counter::query()
        ->orderByDesc('count')
        ->orderBy('name')
        ->get(); // Não há relacionamentos, então sem N+1
});
```

**Ferramentas**:
- [ ] Usar Laravel Debugbar ou Telescope
- [ ] Verificar número de queries
- [ ] Garantir apenas 1 query para listar counters

**Critérios de conclusão**:
- [ ] Sem N+1 queries
- [ ] Performance otimizada
- [ ] Menos de 5 queries por page load

---

### 7.4 Implementar Caching (Opcional)
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 4.1

**Descrição**: Adicionar caching para lista de contadores (opcional, pode não ser necessário para MVP).

**Implementação**:
```php
use Illuminate\Support\Facades\Cache;

$counters = computed(function() {
    return Cache::remember('counters.all', 60, function() {
        return Counter::ordered()->get();
    });
});

// Invalidar cache nos eventos
public function createCounter() {
    // Criar counter...
    Cache::forget('counters.all');
}
```

**Critérios de conclusão**:
- [ ] Cache implementado se necessário
- [ ] Invalidação correta após updates
- [ ] Performance melhorada

---

### 7.5 Rodar Laravel Pint
**Complexidade**: 🟢 Baixa
**Estimativa**: 5 min
**Depende de**: Todas as tarefas de código

**Descrição**: Formatar todo o código PHP seguindo padrões do projeto.

**Comandos**:
```bash
vendor/bin/pint --dirty
```

**Critérios de conclusão**:
- [ ] Pint executado sem erros
- [ ] Código formatado consistentemente
- [ ] Sem warnings de style

---

### 7.6 Otimizar Assets Frontend
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: Todas as tarefas de frontend

**Descrição**: Buildar assets para produção e otimizar.

**Comandos**:
```bash
npm run build
```

**Verificações**:
- [ ] Build sem erros
- [ ] Assets minificados
- [ ] CSS otimizado pelo Tailwind

**Critérios de conclusão**:
- [ ] Build de produção funcionando
- [ ] Assets otimizados
- [ ] Performance adequada

---

### 7.7 Testar Performance com Lighthouse
**Complexidade**: 🟡 Média
**Estimativa**: 20 min
**Depende de**: 7.6

**Descrição**: Rodar Lighthouse audit e otimizar se necessário.

**Ferramentas**:
- Chrome DevTools > Lighthouse
- Rodar audit para Performance, Accessibility, Best Practices

**Metas**:
- [ ] Performance > 90
- [ ] Accessibility > 90
- [ ] Best Practices > 90
- [ ] SEO > 90

**Critérios de conclusão**:
- [ ] Lighthouse audit rodado
- [ ] Scores acima das metas
- [ ] Issues críticos resolvidos

---

## Fase 8: Documentação e Deploy

### 8.1 Atualizar README
**Complexidade**: 🟢 Baixa
**Estimativa**: 20 min
**Depende de**: Todas as fases anteriores

**Descrição**: Atualizar README.md com instruções de setup e uso.

**Seções a incluir**:
- [ ] Descrição do projeto
- [ ] Requisitos (PHP, Node, etc.)
- [ ] Instalação
  ```bash
  composer install
  npm install
  cp .env.example .env
  php artisan key:generate
  php artisan migrate --seed
  ```
- [ ] Configuração do Reverb
- [ ] Como rodar
  ```bash
  php artisan reverb:start
  php artisan serve
  npm run dev
  ```
- [ ] Como rodar testes
- [ ] Stack tecnológica
- [ ] Screenshots (se possível)

**Critérios de conclusão**:
- [ ] README completo e claro
- [ ] Instruções testadas
- [ ] Links para docs relevantes

---

### 8.2 Documentar Configuração do Reverb
**Complexidade**: 🟢 Baixa
**Estimativa**: 15 min
**Depende de**: 0.2, 3.4

**Descrição**: Criar documentação específica para configuração do Reverb.

**Arquivo**: `/docs/REVERB_SETUP.md`

**Conteúdo**:
- [ ] Variáveis de ambiente necessárias
- [ ] Como rodar servidor Reverb
- [ ] Configuração de channels
- [ ] Troubleshooting comum
- [ ] Links para docs oficiais

**Critérios de conclusão**:
- [ ] Documentação criada
- [ ] Instruções claras e testadas
- [ ] Troubleshooting útil

---

### 8.3 Criar Documentação de API (Eventos)
**Complexidade**: 🟢 Baixa
**Estimativa**: 15 min
**Depende de**: 3.1, 3.2

**Descrição**: Documentar eventos e payloads de broadcasting.

**Arquivo**: `/docs/EVENTS.md`

**Conteúdo**:
```markdown
# Eventos de Broadcasting

## Channel: `counters`

### Evento: `counter.created`
Disparado quando um novo contador é criado.

**Payload**:
{
  "id": 1,
  "name": "Teste A",
  "count": 0,
  "updated_at": "2025-11-12T10:30:00Z"
}

### Evento: `counter.incremented`
Disparado quando um contador é incrementado.

**Payload**:
{
  "id": 1,
  "name": "Teste A",
  "count": 42,
  "updated_at": "2025-11-12T10:30:00Z"
}
```

**Critérios de conclusão**:
- [ ] Documentação criada
- [ ] Payloads documentados
- [ ] Exemplos incluídos

---

### 8.4 Executar Todos os Testes Finais
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 6.7, 7.5

**Descrição**: Rodar suite completa de testes antes do deploy.

**Comandos**:
```bash
php artisan test
php artisan test --coverage --min=80
vendor/bin/pint --test
```

**Critérios de conclusão**:
- [ ] 100% dos testes passando
- [ ] Cobertura > 80%
- [ ] Pint sem erros
- [ ] Sem warnings ou deprecations

---

### 8.5 Fazer Commit e Push Final
**Complexidade**: 🟢 Baixa
**Estimativa**: 5 min
**Depende de**: 8.4

**Descrição**: Commit final com todas as mudanças e push para branch.

**Comandos**:
```bash
git add .
git commit -m "Implement real-time counter system with Laravel Reverb"
git push -u origin claude/online-test-system-011CV34xztPWhKR1ZyGpgdcs
```

**Critérios de conclusão**:
- [ ] Commit criado
- [ ] Push para branch correto
- [ ] Sem conflitos

---

### 8.6 Criar Pull Request
**Complexidade**: 🟢 Baixa
**Estimativa**: 10 min
**Depende de**: 8.5

**Descrição**: Criar PR no GitHub para revisão.

**Comandos**:
```bash
gh pr create --title "Sistema de Contadores em Tempo Real" --body "$(cat <<'EOF'
## Resumo

Implementação completa do sistema de contadores em tempo real usando Laravel Reverb.

## Features Implementadas

- ✅ CRUD de contadores com validação
- ✅ Broadcasting em tempo real via Reverb
- ✅ UI moderna e sofisticada com Tailwind + Flux UI
- ✅ Ordenação automática por contagem
- ✅ Componente Livewire Volt
- ✅ Animações e feedback visual
- ✅ Rate limiting
- ✅ Testes completos (Unit, Feature, Browser)
- ✅ Cobertura > 80%

## Test Plan

- [x] Todos os testes unitários passando
- [x] Todos os testes feature passando
- [x] Testes browser passando
- [x] Broadcasting em tempo real funcionando
- [x] UI responsiva em mobile/tablet/desktop
- [x] Lighthouse score > 90

## Screenshots

[Adicionar screenshots se possível]

EOF
)"
```

**Critérios de conclusão**:
- [ ] PR criado
- [ ] Descrição completa
- [ ] Checklist preenchida

---

### 8.7 Deploy em Ambiente de Teste
**Complexidade**: 🟡 Média
**Estimativa**: 30 min
**Depende de**: 8.6

**Descrição**: Deploy da aplicação em servidor de teste para validação final.

**Passos**:
- [ ] Configurar servidor com Reverb
- [ ] Configurar supervisor para Reverb worker
- [ ] Deploy da aplicação
- [ ] Rodar migrations
- [ ] Testar funcionalidades end-to-end

**Comandos (exemplo com Forge/Deploy)**:
```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
php artisan reverb:start &
```

**Critérios de conclusão**:
- [ ] Aplicação deployada
- [ ] Reverb rodando em produção
- [ ] Funcionalidades testadas end-to-end
- [ ] Broadcasting funcionando

---

## Resumo de Estimativas

| Fase | Tarefas | Tempo Estimado |
|------|---------|----------------|
| Fase 0: Setup e Preparação | 4 | 45 min |
| Fase 1: Backend - Banco de Dados | 5 | 60 min (1h) |
| Fase 2: Backend - Models e Logic | 4 | 75 min (1.25h) |
| Fase 3: Backend - Events e Broadcasting | 5 | 95 min (1.5h) |
| Fase 4: Frontend - Componente Base | 6 | 155 min (2.5h) |
| Fase 5: Frontend - UI/UX Avançado | 10 | 265 min (4.5h) |
| Fase 6: Testes Automatizados | 7 | 240 min (4h) |
| Fase 7: Otimização e Polish | 7 | 125 min (2h) |
| Fase 8: Documentação e Deploy | 7 | 115 min (2h) |
| **TOTAL** | **55 tarefas** | **1175 min (~19.5h)** |

---

## Notas Finais

### Priorização
Se tempo for limitado, focar em:
1. **Crítico**: Fases 0, 1, 2, 3, 4 (core functionality)
2. **Importante**: Fase 6 (testes mínimos), Fase 5.1-5.5 (UI básico)
3. **Nice to have**: Fase 5.6-5.10 (animações), Fase 7 (otimizações)

### Flexibilidade
- Algumas tarefas podem ser combinadas
- Estimativas podem variar baseado em experiência
- Ajustar conforme necessário

### Checklist Final
Antes de considerar projeto completo:
- [ ] Todas as tarefas críticas concluídas
- [ ] Testes principais passando (> 80% coverage)
- [ ] UI funcional e intuitiva
- [ ] Broadcasting em tempo real funcionando
- [ ] Documentação mínima criada
- [ ] Deploy funcionando

---

**Criado em**: 2025-11-12
**Baseado em**: `/docs/PRD.md`
**Status**: Pronto para execução
