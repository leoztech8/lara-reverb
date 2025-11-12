# Sistema de Contadores em Tempo Real com Laravel Reverb

Sistema de demonstração para Laravel Reverb que permite criar e gerenciar contadores com atualizações em tempo real usando WebSockets.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## 🚀 Features

- ✅ **Tempo Real**: Atualizações instantâneas via Laravel Reverb (WebSockets)
- ✅ **CRUD Completo**: Criar contadores e incrementar valores
- ✅ **Ordenação Automática**: Contadores ordenados por contagem (maior → menor)
- ✅ **UI Moderna**: Interface sofisticada com Tailwind CSS v4 + Flux UI
- ✅ **Responsivo**: Funciona perfeitamente em mobile, tablet e desktop
- ✅ **Dark Mode**: Suporte completo a tema escuro
- ✅ **Animações**: Transições suaves e feedback visual
- ✅ **Indicador de Conexão**: Status em tempo real da conexão WebSocket
- ✅ **Multi-usuário**: Sincronização entre todos os usuários conectados

## 📋 Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18 ou superior
- NPM ou Yarn
- MySQL, PostgreSQL ou SQLite

## 🛠️ Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/lara-reverb.git
cd lara-reverb
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Instale as dependências do JavaScript

```bash
npm install
```

### 4. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure o banco de dados

Edite o arquivo `.env` e configure sua conexão com o banco de dados:

```env
DB_CONNECTION=sqlite
# OU para MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=lara_reverb
# DB_USERNAME=root
# DB_PASSWORD=
```

### 6. Execute as migrations e seeders

```bash
php artisan migrate --seed
```

Isso criará:
- Tabela `counters`
- 15 contadores de demonstração (Laravel, PHP, Livewire, etc.)

### 7. Configure o Laravel Reverb

O Laravel Reverb já está configurado no `.env`. Certifique-se de que essas variáveis estejam presentes:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## 🚀 Executando o Projeto

Você precisa de **3 terminais** rodando simultaneamente:

### Terminal 1: Servidor Laravel

```bash
php artisan serve
```

Acesse: http://localhost:8000

### Terminal 2: Laravel Reverb (WebSocket Server)

```bash
php artisan reverb:start
```

### Terminal 3: Vite (Assets)

```bash
npm run dev
```

## 📚 Uso

### Criar um Novo Contador

1. Clique no botão **"Novo Contador"**
2. Digite um nome único (máx. 50 caracteres)
3. Clique em **"Criar"**
4. O contador aparecerá instantaneamente para todos os usuários conectados

### Incrementar um Contador

1. Clique no botão **"+ Incrementar"** em qualquer card
2. O valor aumentará em +1
3. O card será reordenado automaticamente se necessário
4. Todos os usuários verão a atualização em tempo real

### Monitorar a Conexão

- **Indicador Verde**: Conectado ao servidor WebSocket
- **Indicador Vermelho**: Desconectado

## 🏗️ Arquitetura

### Backend

```
app/
├── Models/
│   └── Counter.php              # Eloquent model
├── Actions/
│   ├── CreateCounter.php        # Ação para criar contador
│   └── IncrementCounter.php     # Ação para incrementar
├── Events/
│   ├── CounterCreated.php       # Evento de criação
│   └── CounterIncremented.php   # Evento de incremento
└── Http/
    └── Requests/
        └── StoreCounterRequest.php  # Validação
```

### Frontend

```
resources/views/
└── pages/
    └── welcome.blade.php        # Componente Volt principal
```

### Database

```sql
CREATE TABLE counters (
    id BIGINT PRIMARY KEY,
    name VARCHAR(50) UNIQUE,
    count INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX(count),
    INDEX(count, name)
);
```

## 🎨 Stack Tecnológica

- **Backend**: Laravel 12
- **Real-time**: Laravel Reverb
- **Frontend**: Livewire 3 + Volt
- **UI**: Flux UI Free + Tailwind CSS v4
- **JavaScript**: Alpine.js (incluído com Livewire)
- **Testing**: Pest v4

## 🧪 Testes

```bash
# Executar todos os testes
php artisan test

# Executar testes com coverage
php artisan test --coverage

# Executar testes específicos
php artisan test --filter=CounterTest
```

## 🔧 Desenvolvimento

### Formatar código (Laravel Pint)

```bash
vendor/bin/pint
```

### Build de produção

```bash
npm run build
```

### Limpar caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📡 Broadcasting

### Como Funciona

1. **Usuário incrementa um contador**
2. Action `IncrementCounter` atualiza o banco de dados
3. Evento `CounterIncremented` é disparado
4. Laravel Reverb faz broadcast do evento para o channel `counters`
5. Todos os clientes conectados via Echo recebem o evento
6. Componente Livewire Volt atualiza automaticamente a UI

### Channels

- **counters**: Canal público (sem autenticação necessária)

### Events

- `counter.created`: Disparado ao criar um novo contador
- `counter.incremented`: Disparado ao incrementar um contador

## 🚀 Deploy

### Requisitos de Produção

- PHP 8.2+
- Composer
- Node.js 18+
- Supervisor (para manter Reverb rodando)
- Redis (recomendado para queues)

### Configuração do Supervisor

```ini
[program:laravel-reverb]
process_name=%(program_name)s
command=php /caminho/para/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/reverb.log
```

### Build de Assets

```bash
npm run build
```

### Otimizações

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## 📝 Documentação Adicional

- [PRD Completo](docs/PRD.md)
- [Tarefas Detalhadas](tasks/TASKS.md)
- [Instalação Manual do Reverb](REVERB_INSTALL.md)

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto é open-source e está disponível sob a licença MIT.

## 👨‍💻 Autor

Desenvolvido para demonstração do Laravel Reverb.

## 🙏 Agradecimentos

- Laravel Team pelo incrível framework
- Livewire Team pelo Livewire e Flux UI
- Tailwind CSS pela biblioteca de utilitários CSS

---

**Divirta-se testando o Laravel Reverb! 🚀**
