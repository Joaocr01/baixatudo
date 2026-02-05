# 🚀 Guia de Deploy - YouTube Downloader

## ⚠️ IMPORTANTE: Vercel não é recomendado

A Vercel tem limitações para este projeto:
- Não permite instalação de binários (yt-dlp, ffmpeg)
- Limite de tempo de execução muito curto
- Sistema de arquivos efêmero

**Recomendamos usar Railway, Render ou VPS.**

---

## ✅ Opção 1: Railway (Recomendado - GRÁTIS)

Railway oferece $5 de crédito grátis mensalmente, suficiente para uso moderado.

### Passo a Passo:

1. **Crie uma conta no Railway**
   - Acesse: https://railway.app
   - Faça login com GitHub

2. **Faça push do código para GitHub**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/seu-usuario/seu-repo.git
   git push -u origin main
   ```

3. **Crie um novo projeto no Railway**
   - Clique em "New Project"
   - Selecione "Deploy from GitHub repo"
   - Escolha seu repositório

4. **Configure as variáveis de ambiente**
   
   No Railway, vá em Variables e adicione:
   ```
   APP_NAME=YouTube Downloader
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:sua-chave-aqui
   DB_CONNECTION=sqlite
   YOUTUBE_DL_PATH=/usr/local/bin/yt-dlp
   PYTHON_PATH=/usr/bin/python3
   DOWNLOAD_PATH=/tmp/downloads
   ```

5. **Gerar APP_KEY**
   ```bash
   php artisan key:generate --show
   ```

6. **Deploy automático**
   - Railway detectará o Dockerfile automaticamente
   - O deploy será feito automaticamente
   - Você receberá uma URL pública

7. **Acesse sua aplicação**
   - URL estará disponível em Settings > Domains
   - Ex: `https://seu-app.up.railway.app`

---

## ✅ Opção 2: Render (GRÁTIS)

Render oferece plano gratuito permanente.

### Passo a Passo:

1. **Crie uma conta no Render**
   - Acesse: https://render.com
   - Faça login com GitHub

2. **Novo Web Service**
   - Clique em "New +"
   - Selecione "Web Service"
   - Conecte seu repositório GitHub

3. **Configurações**
   ```
   Name: youtube-downloader
   Environment: Docker
   Branch: main
   Plan: Free
   ```

4. **Variáveis de Ambiente**
   
   Adicione as mesmas variáveis do Railway:
   ```
   APP_NAME=YouTube Downloader
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:sua-chave-aqui
   DB_CONNECTION=sqlite
   YOUTUBE_DL_PATH=/usr/local/bin/yt-dlp
   PYTHON_PATH=/usr/bin/python3
   DOWNLOAD_PATH=/tmp/downloads
   ```

5. **Create Web Service**
   - Render fará o build e deploy automaticamente
   - Aguarde 5-10 minutos

6. **Acesse**
   - URL: `https://seu-app.onrender.com`

**Nota:** No plano gratuito, o serviço "dorme" após 15 minutos de inatividade.

---

## ✅ Opção 3: VPS (Digital Ocean, Linode, etc.)

Para uso em produção com alto volume.

### Digital Ocean ($4/mês)

1. **Crie um Droplet**
   - Ubuntu 22.04 LTS
   - Plano básico ($4/mês)

2. **Conecte via SSH**
   ```bash
   ssh root@seu-ip
   ```

3. **Instale dependências**
   ```bash
   # Atualizar sistema
   apt update && apt upgrade -y

   # Instalar PHP e extensões
   apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd

   # Instalar Composer
   curl -sS https://getcomposer.org/installer | php
   mv composer.phar /usr/local/bin/composer

   # Instalar Nginx
   apt install -y nginx

   # Instalar yt-dlp
   curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp
   chmod a+rx /usr/local/bin/yt-dlp

   # Instalar ffmpeg
   apt install -y ffmpeg

   # Instalar Git
   apt install -y git
   ```

4. **Clone o projeto**
   ```bash
   cd /var/www
   git clone https://github.com/seu-usuario/seu-repo.git youtube-downloader
   cd youtube-downloader
   ```

5. **Configurar aplicação**
   ```bash
   # Instalar dependências
   composer install --no-dev --optimize-autoloader

   # Configurar ambiente
   cp .env.example .env
   php artisan key:generate

   # Permissões
   chown -R www-data:www-data /var/www/youtube-downloader
   chmod -R 775 storage bootstrap/cache
   ```

6. **Configurar Nginx**
   ```bash
   nano /etc/nginx/sites-available/youtube-downloader
   ```

   Cole:
   ```nginx
   server {
       listen 80;
       server_name seu-dominio.com;
       root /var/www/youtube-downloader/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;

       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }

       error_page 404 /index.php;

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```

7. **Ativar site**
   ```bash
   ln -s /etc/nginx/sites-available/youtube-downloader /etc/nginx/sites-enabled/
   nginx -t
   systemctl restart nginx
   ```

8. **SSL com Let's Encrypt (Opcional)**
   ```bash
   apt install -y certbot python3-certbot-nginx
   certbot --nginx -d seu-dominio.com
   ```

---

## 🔧 Configuração Pós-Deploy

### Otimizações

```bash
# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimizar autoloader
composer dump-autoload --optimize
```

### Monitoramento

```bash
# Ver logs
tail -f storage/logs/laravel.log

# Ver uso de disco
df -h

# Ver processos
htop
```

---

## 🐛 Troubleshooting

### Download não funciona

1. Verificar se yt-dlp está instalado:
   ```bash
   which yt-dlp
   yt-dlp --version
   ```

2. Verificar ffmpeg:
   ```bash
   which ffmpeg
   ffmpeg -version
   ```

3. Testar download manual:
   ```bash
   yt-dlp "https://www.youtube.com/watch?v=dQw4w9WgXcQ"
   ```

### Erro de permissão

```bash
chown -R www-data:www-data /var/www/youtube-downloader
chmod -R 775 storage bootstrap/cache
chmod -R 777 /tmp/downloads
```

### Erro 500

```bash
# Ver logs detalhados
tail -f storage/logs/laravel.log

# Ativar debug temporariamente
APP_DEBUG=true php artisan config:clear
```

---

## 📊 Comparação de Plataformas

| Plataforma | Custo | yt-dlp | ffmpeg | Limite Tempo | Recomendado |
|------------|-------|--------|--------|--------------|-------------|
| Railway    | $5 grátis/mês | ✅ | ✅ | Sem limite | ⭐⭐⭐⭐⭐ |
| Render     | Grátis | ✅ | ✅ | Sem limite | ⭐⭐⭐⭐ |
| Vercel     | Grátis | ❌ | ❌ | 10s | ❌ |
| VPS        | $4-5/mês | ✅ | ✅ | Sem limite | ⭐⭐⭐⭐⭐ |

---

## 💡 Dicas

1. **Use Railway ou Render** para começar rapidamente
2. **VPS é melhor** para uso em produção
3. **Evite Vercel** para este tipo de aplicação
4. **Monitore uso** para não exceder limites gratuitos
5. **Configure CDN** se houver muito tráfego

---

## 🆘 Suporte

- **Railway**: https://railway.app/help
- **Render**: https://render.com/docs
- **Digital Ocean**: https://docs.digitalocean.com

---

## 📝 Checklist de Deploy

- [ ] Código no GitHub
- [ ] Variáveis de ambiente configuradas
- [ ] APP_KEY gerada
- [ ] yt-dlp instalado
- [ ] ffmpeg instalado
- [ ] Permissões corretas
- [ ] SSL configurado (VPS)
- [ ] Backup configurado
- [ ] Monitoramento ativo

---

Boa sorte com seu deploy! 🚀
