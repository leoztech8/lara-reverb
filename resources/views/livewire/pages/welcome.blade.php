<?php

use App\Actions\CreateCounter;
use App\Actions\IncrementCounter;
use App\Models\Counter;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $showModal = false;
    public string $newCounterName = '';
    public bool $isCreating = false;

    /**
     * Get all counters ordered by count descending, then name ascending.
     */
    public function with(): array
    {
        return [
            'counters' => Counter::ordered()->get(),
        ];
    }

    /**
     * Create a new counter.
     */
    public function createCounter(): void
    {
        $this->validate([
            'newCounterName' => ['required', 'string', 'max:50', 'unique:counters,name'],
        ], [
            'newCounterName.required' => 'O nome do contador é obrigatório.',
            'newCounterName.max' => 'O nome não pode ter mais de 50 caracteres.',
            'newCounterName.unique' => 'Já existe um contador com este nome.',
        ]);

        $this->isCreating = true;

        try {
            $action = new CreateCounter();
            $action->execute($this->newCounterName);

            $this->reset('newCounterName', 'showModal', 'isCreating');
            $this->dispatch('counter-created');
        } catch (\Exception $e) {
            $this->addError('newCounterName', 'Erro ao criar contador. Tente novamente.');
            $this->isCreating = false;
        }
    }

    /**
     * Increment a counter by ID.
     */
    public function incrementCounter(int $id): void
    {
        try {
            $action = new IncrementCounter();
            $action->execute($id);
        } catch (\Exception $e) {
            // Silently fail - counter will refresh from broadcast
        }
    }

    /**
     * Listen for Echo broadcast events and refresh.
     */
    #[On('echo:counters,.counter.created')]
    #[On('echo:counters,.counter.incremented')]
    public function refreshCounters(): void
    {
        // Counters will be refreshed automatically by with() method
    }
}; ?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    {{-- Header --}}
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <flux:heading size="xl" class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Contadores em Tempo Real
                    </flux:heading>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Sistema de demonstração do Laravel Reverb com WebSockets
                    </p>
                </div>
                <flux:button
                    variant="primary"
                   
                    wire:click="$set('showModal', true)"
                    icon="plus"
                    class="w-full sm:w-auto"
                >
                    Novo Contador
                </flux:button>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        @if($counters->isEmpty())
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 px-6">
                <div class="rounded-full bg-gray-100 dark:bg-gray-800 p-6 mb-6">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <flux:heading class="mb-3 text-gray-700 dark:text-gray-300">
                    Nenhum contador ainda
                </flux:heading>
                <p class="text-gray-500 dark:text-gray-400 mb-8 text-center max-w-md">
                    Crie seu primeiro contador para começar a testar o Laravel Reverb e ver as atualizações em tempo real!
                </p>
                <flux:button
                    variant="primary"
                   
                    wire:click="$set('showModal', true)"
                    icon="plus"
                >
                    Criar Primeiro Contador
                </flux:button>
            </div>
        @else
            {{-- Counters Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($counters as $counter)
                    <div
                        wire:key="counter-{{ $counter->id }}"
                        x-data="{
                            pulse: false,
                            show: false
                        }"
                        x-init="$nextTick(() => show = true)"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        @counter-incremented-{{ $counter->id }}.window="pulse = true; setTimeout(() => pulse = false, 1000)"
                        :class="{ 'ring-2 ring-blue-500': pulse }"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-200 dark:border-gray-700"
                    >
                        {{-- Counter Value --}}
                        <div
                            class="text-6xl font-bold text-center mb-4 bg-gradient-to-br from-blue-600 to-purple-600 bg-clip-text text-transparent transition-all duration-300"
                            :class="{ 'scale-110': pulse }"
                        >
                            {{ number_format($counter->count) }}
                        </div>

                        {{-- Counter Name --}}
                        <div class="text-lg font-semibold text-center text-gray-800 dark:text-gray-200 mb-4 truncate" title="{{ $counter->name }}">
                            {{ $counter->name }}
                        </div>

                        {{-- Increment Button --}}
                        <flux:button
                            wire:click="incrementCounter({{ $counter->id }})"
                            variant="primary"
                            class="w-full"
                            wire:loading.attr="disabled"
                            wire:target="incrementCounter({{ $counter->id }})"
                        >
                            <span wire:loading.remove wire:target="incrementCounter({{ $counter->id }})">
                                + Incrementar
                            </span>
                            <span wire:loading wire:target="incrementCounter({{ $counter->id }})">
                                Incrementando...
                            </span>
                        </flux:button>

                        {{-- Timestamp --}}
                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-3">
                            Atualizado {{ $counter->updated_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    {{-- Create Counter Modal --}}
    <flux:modal wire:model="showModal" class="max-w-md">
        <div class="p-6">
            <flux:heading class="mb-6">Criar Novo Contador</flux:heading>

            <form wire:submit="createCounter" class="space-y-6">
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
                    <flux:description>
                        Máximo de 50 caracteres. O nome deve ser único.
                    </flux:description>
                </flux:field>

                <div class="flex gap-3 justify-end">
                    <flux:button
                        type="button"
                        variant="ghost"
                        wire:click="$set('showModal', false)"
                        :disabled="isCreating"
                    >
                        Cancelar
                    </flux:button>
                    <flux:button
                        type="submit"
                        variant="primary"
                        wire:loading.attr="disabled"
                        wire:target="createCounter"
                    >
                        <span wire:loading.remove wire:target="createCounter">Criar</span>
                        <span wire:loading wire:target="createCounter">Criando...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    {{-- Connection Status Indicator --}}
    <div
        x-data="{ connected: true }"
        @connect.window="connected = true"
        @disconnect.window="connected = false"
        class="fixed bottom-4 left-4 z-50"
    >
        <div
            :class="connected ? 'bg-green-500' : 'bg-red-500'"
            class="flex items-center gap-2 text-white px-4 py-2 rounded-full shadow-lg text-sm font-medium transition-colors duration-300"
        >
            <div
                :class="connected ? 'animate-pulse' : ''"
                class="w-2 h-2 bg-white rounded-full"
            ></div>
            <span x-text="connected ? 'Conectado' : 'Desconectado'"></span>
        </div>
    </div>

    {{-- Global Loading Overlay --}}
    <div
        wire:loading
        wire:target="createCounter"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 flex items-center justify-center"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-2xl">
            <div class="flex items-center gap-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Criando contador...</span>
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Listen for Echo connection events
    if (typeof window.Echo !== 'undefined') {
        window.Echo.connector.pusher.connection.bind('connected', () => {
            window.dispatchEvent(new CustomEvent('connect'));
        });

        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            window.dispatchEvent(new CustomEvent('disconnect'));
        });
    }
</script>
@endscript
