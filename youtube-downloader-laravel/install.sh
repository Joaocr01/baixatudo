#!/bin/bash

echo "🚀 YouTube Downloader - Instalação Rápida"
echo "========================================="
echo ""

# Verificar PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP não encontrado. Instale PHP 8.1 ou superior."
    exit 1
fi

echo "✅ PHP encontrado: $(php -v | head -n 1)"

# Verificar Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer não encontrado. Instale o Composer."
    exit 1
fi

echo "✅ Composer encontrado"

# Instalar dependências
echo ""
echo "📦 Instalando dependências do Laravel..."
composer install --no-dev --optimize-autoloader

# Configurar ambiente
if [ ! -f .env ]; then
    echo ""
    echo "⚙️  Configurando ambiente..."
    cp .env.example .env
    php artisan key:generate
fi

# Verificar yt-dlp
if ! command -v yt-dlp &> /dev/null; then
    echo ""
    echo "⚠️  yt-dlp não encontrado. Instalando..."
    
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
        sudo chmod a+rx /usr/local/bin/yt-dlp
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        brew install yt-dlp
    else
        echo "❌ Sistema operacional não suportado. Instale yt-dlp manualmente."
        echo "   https://github.com/yt-dlp/yt-dlp#installation"
    fi
fi

if command -v yt-dlp &> /dev/null; then
    echo "✅ yt-dlp encontrado: $(yt-dlp --version)"
fi

# Verificar ffmpeg
if ! command -v ffmpeg &> /dev/null; then
    echo ""
    echo "⚠️  ffmpeg não encontrado. É necessário para conversão de áudio."
    echo "   Instale com:"
    
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        echo "   sudo apt install ffmpeg"
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        echo "   brew install ffmpeg"
    fi
else
    echo "✅ ffmpeg encontrado: $(ffmpeg -version | head -n 1)"
fi

# Criar diretórios necessários
echo ""
echo "📁 Criando diretórios..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo ""
echo "✅ Instalação concluída!"
echo ""
echo "Para iniciar o servidor:"
echo "  php artisan serve"
echo ""
echo "Depois acesse: http://localhost:8000"
echo ""
