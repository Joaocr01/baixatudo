#!/bin/bash

echo "🧪 YouTube Downloader - Testes"
echo "=============================="
echo ""

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Teste 1: PHP
echo -n "1. Verificando PHP... "
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    if [ "$(printf '%s\n' "8.1" "$PHP_VERSION" | sort -V | head -n1)" = "8.1" ]; then
        echo -e "${GREEN}✓${NC} PHP $PHP_VERSION"
    else
        echo -e "${RED}✗${NC} PHP $PHP_VERSION (necessário 8.1+)"
    fi
else
    echo -e "${RED}✗${NC} PHP não encontrado"
fi

# Teste 2: Composer
echo -n "2. Verificando Composer... "
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✓${NC} Instalado"
else
    echo -e "${RED}✗${NC} Não encontrado"
fi

# Teste 3: yt-dlp
echo -n "3. Verificando yt-dlp... "
if command -v yt-dlp &> /dev/null; then
    YT_VERSION=$(yt-dlp --version)
    echo -e "${GREEN}✓${NC} Versão $YT_VERSION"
else
    echo -e "${YELLOW}⚠${NC} Não encontrado (necessário para downloads)"
fi

# Teste 4: ffmpeg
echo -n "4. Verificando ffmpeg... "
if command -v ffmpeg &> /dev/null; then
    echo -e "${GREEN}✓${NC} Instalado"
else
    echo -e "${YELLOW}⚠${NC} Não encontrado (necessário para conversão de áudio)"
fi

# Teste 5: Dependências do Composer
echo -n "5. Verificando dependências... "
if [ -d "vendor" ]; then
    echo -e "${GREEN}✓${NC} Instaladas"
else
    echo -e "${RED}✗${NC} Execute: composer install"
fi

# Teste 6: Arquivo .env
echo -n "6. Verificando .env... "
if [ -f ".env" ]; then
    echo -e "${GREEN}✓${NC} Configurado"
    
    # Verificar APP_KEY
    if grep -q "APP_KEY=base64:" .env; then
        echo "   ${GREEN}✓${NC} APP_KEY configurada"
    else
        echo "   ${YELLOW}⚠${NC} Execute: php artisan key:generate"
    fi
else
    echo -e "${RED}✗${NC} Execute: cp .env.example .env"
fi

# Teste 7: Permissões
echo -n "7. Verificando permissões... "
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    echo -e "${GREEN}✓${NC} OK"
else
    echo -e "${YELLOW}⚠${NC} Execute: chmod -R 775 storage bootstrap/cache"
fi

# Teste 8: Teste de download (opcional)
echo ""
echo -n "Deseja testar um download? (s/n): "
read -r response
if [[ "$response" =~ ^([sS]|[yY])$ ]]; then
    echo ""
    echo "Testando download de vídeo de exemplo..."
    
    TEST_URL="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
    
    if command -v yt-dlp &> /dev/null; then
        yt-dlp --skip-download --print title "$TEST_URL" 2>/dev/null
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✓${NC} Download test passou!"
        else
            echo -e "${RED}✗${NC} Erro no teste de download"
        fi
    else
        echo -e "${YELLOW}⚠${NC} yt-dlp não instalado"
    fi
fi

echo ""
echo "=============================="
echo "Testes concluídos!"
echo ""
