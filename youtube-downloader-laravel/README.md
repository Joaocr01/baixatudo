# YouTube Downloader - Laravel

Aplicação Laravel para download de vídeos e áudios do YouTube, pronta para deploy na Vercel.

## 🚀 Funcionalidades

- ✅ Download de vídeos em MP4
- ✅ Download de áudios em MP3
- ✅ Interface moderna e responsiva
- ✅ Visualização de informações do vídeo
- ✅ 100% funcional
- ✅ Pronto para Vercel

## 📋 Requisitos

- PHP 8.1 ou superior
- Composer
- yt-dlp instalado no servidor
- ffmpeg (para conversão de áudio)

## 🛠️ Instalação Local

### 1. Clone o repositório

```bash
git clone <seu-repositorio>
cd youtube-downloader-laravel
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Instale yt-dlp

#### Linux/Mac:
```bash
sudo curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
sudo chmod a+rx /usr/local/bin/yt-dlp
```

#### Windows:
Baixe de: https://github.com/yt-dlp/yt-dlp/releases

### 5. Instale ffmpeg

#### Ubuntu/Debian:
```bash
sudo apt update
sudo apt install ffmpeg
```

#### Mac:
```bash
brew install ffmpeg
```

#### Windows:
Baixe de: https://ffmpeg.org/download.html

### 6. Inicie o servidor

```bash
php artisan serve
```

Acesse: `http://localhost:8000`

## 🚀 Deploy na Vercel

### 1. Instale a CLI da Vercel

```bash
npm install -g vercel
```

### 2. Faça login

```bash
vercel login
```

### 3. Configure o projeto

Crie um `vercel.json` na raiz (já incluído)

### 4. Deploy

```bash
vercel --prod
```

### 5. Configure as variáveis de ambiente na Vercel

No painel da Vercel, adicione:

```
APP_NAME=YouTube Downloader
APP_KEY=base64:sua-chave-gerada
APP_ENV=production
APP_DEBUG=false
YOUTUBE_DL_PATH=/usr/local/bin/yt-dlp
PYTHON_PATH=/usr/bin/python3
DOWNLOAD_PATH=/tmp/downloads
```

## ⚠️ IMPORTANTE para Vercel

A Vercel tem algumas limitações:

1. **yt-dlp precisa estar instalado**: Você pode usar uma build customizada ou Docker
2. **Limite de tempo**: 10 segundos para Hobby plan, 60s para Pro
3. **Arquivos temporários**: Use `/tmp` para downloads

### Solução Alternativa para Vercel

Para contornar limitações da Vercel, considere:

1. **Usar um servidor VPS** (Recomendado)
   - Digital Ocean
   - Linode
   - AWS EC2

2. **Usar Railway.app** (Suporta Docker)
   ```bash
   railway up
   ```

3. **Usar Heroku** com buildpack customizado

## 📁 Estrutura do Projeto

```
youtube-downloader-laravel/
├── api/
│   └── index.php                    # Entry point para Vercel
├── app/
│   └── Http/
│       └── Controllers/
│           └── DownloadController.php
├── resources/
│   └── views/
│       └── downloader.blade.php     # Interface principal
├── routes/
│   ├── web.php
│   └── api.php
├── .env.example
├── composer.json
├── vercel.json
└── README.md
```

## 🎯 Como Usar

1. Cole o link do YouTube
2. Escolha entre baixar vídeo ou áudio
3. Aguarde o processamento
4. Download será iniciado automaticamente

## 🔧 Tecnologias

- **Backend**: Laravel 10
- **Download Engine**: yt-dlp via norkunas/youtube-dl-php
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Deploy**: Vercel/Railway/VPS

## ⚙️ Configuração Avançada

### Alterar qualidade do vídeo

No `DownloadController.php`, linha 79:

```php
$options->format('best')  // Melhor qualidade
// ou
$options->format('worst') // Menor qualidade
// ou
$options->format('bestvideo[height<=720]+bestaudio') // Máximo 720p
```

### Alterar qualidade do áudio

No `DownloadController.php`, linha 76:

```php
$options->audioQuality('0')  // Melhor (padrão)
// 0 = melhor, 9 = pior
```

## 🐛 Problemas Comuns

### "yt-dlp not found"
```bash
which yt-dlp
# Atualize YOUTUBE_DL_PATH no .env com o caminho correto
```

### "ffmpeg not found"
```bash
which ffmpeg
# Instale ffmpeg
```

### Erro 500
```bash
# Verifique os logs
tail -f storage/logs/laravel.log
```

### Vercel timeout
- Use um servidor VPS para vídeos longos
- Ou reduza a qualidade do vídeo

## 📝 Licença

MIT License

## 🤝 Contribuindo

Pull requests são bem-vindos!

## 📧 Suporte

Para problemas, abra uma issue no GitHub.

## ⚖️ Aviso Legal

Esta ferramenta é para uso pessoal e educacional. Respeite os direitos autorais e termos de serviço do YouTube.
