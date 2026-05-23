#!/bin/bash
# =============================================================
# Setup e Deploy do projeto IEL na VPS
# Execute como root: bash setup-vps.sh
# =============================================================
set -e

APP_DIR="/var/www/iel"
REPO="https://github.com/LuizAlmeida1984/iel.git"

echo "=== [1/5] Atualizando o sistema ==="
apt-get update -y

echo "=== [2/5] Instalando Docker (se necessário) ==="
if ! command -v docker &> /dev/null; then
    curl -fsSL https://get.docker.com | sh
    systemctl enable docker
    systemctl start docker
fi

if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    apt-get install -y docker-compose-plugin || apt-get install -y docker-compose
fi

echo "=== [3/5] Clonando / atualizando o repositório ==="
if [ -d "$APP_DIR/.git" ]; then
    echo "Repositório já existe — fazendo pull..."
    cd "$APP_DIR"
    git pull origin main
else
    echo "Clonando repositório..."
    git clone "$REPO" "$APP_DIR"
    cd "$APP_DIR"
fi

echo "=== [4/5] Criando backend/.env de produção ==="
if [ ! -f "$APP_DIR/backend/.env" ]; then
    cat > "$APP_DIR/backend/.env" << 'ENVEOF'
APP_NAME=IEL
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://2.24.198.67.nip.io

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@iel.com.br"
MAIL_FROM_NAME="IEL"

GEMINI_API_KEY=COLOQUE_SUA_CHAVE_AQUI
GEMINI_MODEL=gemini-2.0-flash
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta/openai/
GEMINI_REQUEST_TIMEOUT=60
ENVEOF
    echo ""
    echo "⚠️  ATENÇÃO: Edite o arquivo backend/.env e coloque a GEMINI_API_KEY correta!"
    echo "   nano $APP_DIR/backend/.env"
    echo ""
fi

echo "=== [5/5] Build e inicialização dos containers ==="
cd "$APP_DIR"

# Para container existente se houver
docker compose down 2>/dev/null || docker-compose down 2>/dev/null || true

# Build e start
docker compose up --build -d 2>/dev/null || docker-compose up --build -d

echo ""
echo "✅  Deploy concluído!"
echo "   App rodando em: http://2.24.198.67"
echo "   API Laravel em: http://2.24.198.67/api"
echo ""
echo "Logs: docker compose logs -f"
