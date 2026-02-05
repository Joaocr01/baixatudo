# ⚡ Início Rápido - YouTube Downloader

## 🚀 Deploy Rápido (5 minutos)

### Railway (Recomendado)

```bash
# 1. Instale a CLI do Railway
npm install -g @railway/cli

# 2. Faça login
railway login

# 3. Inicialize o projeto
railway init

# 4. Deploy
railway up

# 5. Adicione as variáveis de ambiente
railway variables set APP_KEY=$(php artisan key:generate --show)
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false

# 6. Abra no navegador
railway open
```

Pronto! Sua aplicação está no ar! 🎉

---

## 💻 Instalação Local (3 minutos)

```bash
# 1. Clone o repositório
git clone <seu-repo>
cd youtube-downloader-laravel

# 2. Execute o instalador
chmod +x install.sh
./install.sh

# 3. Inicie o servidor
php artisan serve

# 4. Abra no navegador
# http://localhost:8000
```

---

## 🐳 Docker (2 minutos)

```bash
# 1. Build da imagem
docker build -t youtube-downloader .

# 2. Execute o container
docker run -p 8080:80 youtube-downloader

# 3. Abra no navegador
# http://localhost:8080
```

---

## ✅ Verificar Instalação

```bash
./test.sh
```

---

## 🎯 Como Usar

1. Abra a aplicação no navegador
2. Cole um link do YouTube
3. Clique em "Baixar Vídeo" ou "Baixar Áudio"
4. Aguarde o download

---

## 📱 Exemplos de URLs

- Vídeo normal: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
- URL curta: `https://youtu.be/dQw4w9WgXcQ`
- Com timestamp: `https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=42s`

---

## 🔧 Configuração Mínima

Apenas estas variáveis são necessárias:

```.env
APP_KEY=base64:sua-chave-aqui
APP_ENV=production
APP_DEBUG=false
```

---

## 🐛 Problemas?

### yt-dlp não encontrado

```bash
# Linux/Mac
sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
sudo chmod a+rx /usr/local/bin/yt-dlp

# Windows
# Baixe de: https://github.com/yt-dlp/yt-dlp/releases
```

### ffmpeg não encontrado

```bash
# Ubuntu/Debian
sudo apt install ffmpeg

# Mac
brew install ffmpeg

# Windows
# Baixe de: https://ffmpeg.org/download.html
```

### Erro de permissão

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📚 Documentação Completa

- **README.md** - Documentação completa
- **DEPLOY.md** - Guia de deploy detalhado
- **test.sh** - Script de testes

---

## 💡 Dicas

1. **Use Railway** para deploy rápido
2. **Teste localmente** antes de fazer deploy
3. **Verifique os logs** se algo der errado
4. **Mantenha yt-dlp atualizado**

---

## 🆘 Ajuda Rápida

```bash
# Ver logs
tail -f storage/logs/laravel.log

# Limpar cache
php artisan cache:clear

# Atualizar yt-dlp
yt-dlp -U

# Reiniciar servidor
php artisan serve --host=0.0.0.0 --port=8000
```

---

Pronto para usar! 🚀
