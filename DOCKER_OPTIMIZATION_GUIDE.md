# 🚀 Laravel EPI Management - Sistema Docker Otimizado

## 📋 Índice
- [Visão Geral](#visão-geral)
- [Características Principais](#características-principais)
- [Configuração Inicial](#configuração-inicial)
- [Parametrização de Seeders](#parametrização-de-seeders)
- [Comandos Makefile](#comandos-makefile)
- [Melhores Práticas](#melhores-práticas)
- [Arquitetura](#arquitetura)
- [Troubleshooting](#troubleshooting)

## 🎯 Visão Geral

Este projeto implementa um sistema Docker otimizado para o Laravel EPI Management System, seguindo as melhores práticas de containerização com:

- **Multi-stage builds** para imagens menores e mais seguras
- **Parametrização completa** via variáveis de ambiente
- **Um processo por container** para melhor escalabilidade
- **Camadas otimizadas** para cache eficiente
- **Scripts de inicialização inteligentes**

## ✨ Características Principais

### 🔧 Dockerização Avançada
- **Multi-stage build**: Reduz tamanho da imagem final
- **Cache eficiente**: Layers otimizados para rebuilds rápidos
- **Segurança**: Execução com usuário não-privilegiado
- **Health checks**: Monitoramento automático dos serviços

### 🎛️ Parametrização Completa
- Controle total sobre execução de migrations
- Seeders parametrizáveis por classe
- Configurações de ambiente flexíveis
- Otimizações condicionais

### 🛠️ Ferramentas de Desenvolvimento
- Makefile com 30+ comandos úteis
- Scripts de backup e restore
- Perfis de desenvolvimento e produção
- Monitoramento e logs centralizados

## 🚀 Configuração Inicial

### 1. Preparação do Ambiente

```bash
# Clonar ou navegar até o projeto
cd /caminho/para/projeto

# Inicializar configuração
make init

# Ou manualmente:
cp .env.example .env
mkdir -p docker/data/{mysql,redis,storage,logs}
```

### 2. Configurar Variáveis (.env)

```env
# Configurações básicas
COMPOSE_PROJECT_NAME=laravel-epi
APP_ENV=production
APP_DEBUG=false
DB_PASSWORD=sua_senha_segura

# Controle de inicialização
RUN_MIGRATIONS=true
RUN_SEEDERS=false
SEEDER_CLASS=DatabaseSeeder
FORCE_SEED=false
```

### 3. Setup Completo

```bash
# Setup automático (recomendado)
make setup

# Ou passo a passo:
make build
make up
make key
make migrate
```

## 🌱 Parametrização de Seeders

### Configuração via Variáveis de Ambiente

O sistema permite controle granular sobre a execução dos seeders:

```env
# Ativar/desativar seeders
RUN_SEEDERS=true

# Classe específica do seeder
SEEDER_CLASS=DatabaseSeeder  # ou classe específica

# Forçar recriação completa (CUIDADO!)
FORCE_SEED=false

# Otimizações
OPTIMIZE_APP=true
CLEAR_CACHE=true
```

### Execução via Makefile

```bash
# Seeders individuais
make seed-users          # Apenas usuários
make seed-departments    # Apenas departamentos  
make seed-positions      # Apenas cargos
make seed-epi-types      # Apenas tipos de EPI
make seed-epis           # Apenas EPIs
make seed-employees      # Apenas funcionários

# Seeder personalizado
make seed-class SEEDER=MinhaClasseSeeder

# Todos os seeders
make seed-all
```

### Execução via Docker Compose

```bash
# Desenvolvimento com seeders automáticos
RUN_SEEDERS=true FORCE_SEED=true docker-compose -f docker-compose.optimized.yml up -d

# Produção sem seeders
RUN_SEEDERS=false docker-compose -f docker-compose.optimized.yml up -d

# Seeder específico
SEEDER_CLASS=UsuarioSeeder RUN_SEEDERS=true docker-compose -f docker-compose.optimized.yml up -d
```

## 🔨 Comandos Makefile

### Comandos Essenciais

```bash
make help                # Lista todos os comandos
make info               # Informações do projeto
make status             # Status dos serviços
```

### Gerenciamento Docker

```bash
make build              # Build das imagens
make up                 # Iniciar serviços
make down               # Parar serviços
make restart            # Reiniciar serviços
make rebuild            # Rebuild completo
```

### Desenvolvimento

```bash
make shell              # Acesso ao container da app
make shell-db           # Acesso ao MySQL
make tinker             # Laravel Tinker
make logs               # Logs da aplicação
make logs-all           # Logs de todos os serviços
```

### Banco de Dados

```bash
make migrate            # Executar migrations
make migrate-fresh      # Reset completo (CUIDADO!)
make seed               # Executar todos os seeders
make backup-db          # Backup do banco
make restore-db FILE=backup.sql  # Restore do banco
```

### Cache e Otimização

```bash
make cache-clear        # Limpar cache
make optimize           # Otimizar aplicação
make test               # Executar testes
```

### Perfis Pré-configurados

```bash
make dev-start          # Ambiente de desenvolvimento com dados
make prod-start         # Ambiente de produção limpo
make queue-start        # Iniciar workers de queue
make scheduler-start    # Iniciar agendador de tarefas
```

### Limpeza

```bash
make clean              # Limpar containers e volumes
make clean-all          # Limpeza completa (CUIDADO!)
```

## 🏗️ Arquitetura

### Estrutura de Containers

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Application   │    │    Database     │    │     Redis       │
│   (PHP/Apache)  │    │    (MySQL)      │    │    (Cache)      │
│                 │    │                 │    │                 │
│ - Laravel App   │◄──►│ - MySQL 8.0     │    │ - Session Store │
│ - Init Scripts  │    │ - Persistent    │◄──►│ - Queue Backend │
│ - Health Checks │    │ - Health Checks │    │ - Cache Layer   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
        │
        ▼
┌─────────────────┐    ┌─────────────────┐
│  Queue Workers  │    │   Scheduler     │
│   (Optional)    │    │   (Optional)    │
│                 │    │                 │
│ - Background    │    │ - Cron Tasks    │
│ - Job Processing│    │ - Maintenance   │
│ - Scalable      │    │ - Automated     │
└─────────────────┘    └─────────────────┘
```

### Fluxo de Inicialização

1. **Container Start**: Docker inicia o container
2. **Health Checks**: Aguarda dependências (DB, Redis)
3. **Initialization Script**: Executa script parametrizado
4. **Database Setup**: Migrations e seeders (se configurado)
5. **Application Optimization**: Cache e otimizações
6. **Service Ready**: Aplicação disponível

### Multi-stage Build

```dockerfile
Stage 1: Composer Dependencies (composer:2.6)
├── Cache composer.json/lock
├── Install production dependencies
└── Optimize autoloader

Stage 2: Node Dependencies (node:18-alpine)  
├── Cache package.json
├── Install production modules
└── Clean npm cache

Stage 3: Production Runtime (php:8.2-apache)
├── System dependencies
├── PHP extensions
├── Configuration files
├── Application code
├── Permissions setup
└── Security hardening
```

## 📊 Melhores Práticas Implementadas

### 🔒 Segurança
- Execução com usuário não-root (`www-data`)
- Imagens minimalistas
- Secrets via environment variables
- Network isolation

### 🚀 Performance
- OPcache ativado e otimizado
- Multi-layer caching
- Connection pooling
- Resource limits

### 📈 Escalabilidade
- Stateless application design
- Horizontal scaling ready
- Queue workers separados
- Load balancer support

### 🔍 Observabilidade
- Health checks em todos os serviços
- Structured logging
- Metrics endpoints
- Error tracking

### 🛠️ Manutenibilidade
- Infrastructure as Code
- Automated builds
- Version pinning
- Documentation

## 🎯 Cenários de Uso

### Desenvolvimento Local

```bash
# Setup rápido com dados de exemplo
make init
make dev-start

# Trabalhar com seeders específicos
make seed-users
make seed-departments
```

### Ambiente de Staging

```bash
# Deploy com dados reais mas sem debug
APP_ENV=staging RUN_SEEDERS=true make prod-start
```

### Produção

```bash
# Deploy limpo e otimizado
make prod-start

# Ou com controle fino:
RUN_MIGRATIONS=true RUN_SEEDERS=false OPTIMIZE_APP=true make up
```

### CI/CD Pipeline

```bash
# Build e teste
make build
make up
make test

# Deploy
make prod-start
```

## 🚨 Troubleshooting

### Problemas Comuns

**Container não inicia**
```bash
# Verificar logs
make logs-all

# Verificar saúde dos serviços  
make status

# Rebuild se necessário
make rebuild
```

**Banco não conecta**
```bash
# Verificar health check
docker-compose -f docker-compose.optimized.yml ps

# Logs do banco
make logs-db

# Testar conexão manual
make shell-db
```

**Seeders não executam**
```bash
# Verificar configuração
grep -E "RUN_SEEDERS|SEEDER_CLASS" .env

# Executar manualmente
make shell
php artisan db:seed --class=MinhaClasseSeeder --force
```

**Performance baixa**
```bash
# Otimizar aplicação
make optimize

# Verificar configuração do OPcache
make shell
php -i | grep -i opcache
```

### Logs e Debugging

```bash
# Logs em tempo real
make logs

# Logs de todos os serviços
make logs-all

# Acesso direto aos containers
make shell      # App container
make shell-db   # Database container

# Verificar configuração PHP
make shell
php -i

# Status detalhado
docker-compose -f docker-compose.optimized.yml ps --services
```

### Reset Completo

```bash
# CUIDADO: Remove todos os dados!
make clean-all
make setup
```

## 📖 Comandos de Referência Rápida

```bash
# Setup inicial
make init && make setup

# Desenvolvimento diário
make dev-start
make logs
make shell

# Deploy
make prod-start

# Manutenção
make backup-db
make optimize
make clean

# Debug
make status
make logs-all
make shell-db
```

## 🎉 Conclusão

Este sistema Docker otimizado oferece:
- ✅ **Flexibilidade total** na configuração
- ✅ **Performance otimizada** para produção  
- ✅ **Facilidade de desenvolvimento**
- ✅ **Manutenção simplificada**
- ✅ **Escalabilidade horizontal**
- ✅ **Segurança por design**

Para suporte adicional, consulte os logs detalhados ou entre em contato com a equipe de desenvolvimento.

---

*Documentação atualizada em $(date)*