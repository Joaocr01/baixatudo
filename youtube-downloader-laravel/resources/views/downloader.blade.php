<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>YouTube Downloader</title>
    <link href="https://fonts.googleapis.com/css2?family=Azeret+Mono:wght@400;600;700&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #FF0033;
            --bg-dark: #0a0a0a;
            --bg-card: #141414;
            --text: #ffffff;
            --text-dim: #888888;
            --accent: #00ff88;
            --border: #222222;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .grain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.03;
            z-index: 1000;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='3.5' numOctaves='4' /%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' /%3E%3C/svg%3E");
            animation: grain 8s steps(10) infinite;
        }

        @keyframes grain {
            0%, 100% { transform: translate(0, 0); }
            10% { transform: translate(-5%, -10%); }
            20% { transform: translate(-15%, 5%); }
            30% { transform: translate(7%, -25%); }
            40% { transform: translate(-5%, 25%); }
            50% { transform: translate(-15%, 10%); }
            60% { transform: translate(15%, 0%); }
            70% { transform: translate(0%, 15%); }
            80% { transform: translate(3%, 35%); }
            90% { transform: translate(-10%, 10%); }
        }

        .app {
            position: relative;
            max-width: 680px;
            margin: 0 auto;
            padding: 60px 20px;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 60px;
            animation: slideDown 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-family: 'Azeret Mono', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.05em;
            margin-bottom: 12px;
        }

        .logo-yt {
            color: var(--primary);
        }

        .logo-down {
            color: var(--text);
        }

        .tagline {
            font-size: 0.95rem;
            color: var(--text-dim);
            font-weight: 300;
            letter-spacing: 0.05em;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s backwards;
            position: relative;
            overflow: hidden;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            opacity: 0.5;
        }

        .input-group {
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-dim);
            margin-bottom: 12px;
        }

        .input-wrapper {
            position: relative;
        }

        input[type="text"] {
            width: 100%;
            padding: 16px 20px;
            font-size: 1rem;
            font-family: 'Azeret Mono', monospace;
            background: var(--bg-dark);
            border: 2px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 0, 51, 0.1);
        }

        input[type="text"]::placeholder {
            color: var(--text-dim);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        button {
            flex: 1;
            padding: 16px 24px;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        button:hover::before {
            width: 300px;
            height: 300px;
        }

        button span {
            position: relative;
            z-index: 1;
        }

        .btn-video {
            background: var(--primary);
            color: white;
        }

        .btn-video:hover {
            background: #cc0029;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 0, 51, 0.3);
        }

        .btn-audio {
            background: var(--accent);
            color: var(--bg-dark);
        }

        .btn-audio:hover {
            background: #00cc6e;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 255, 136, 0.3);
        }

        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .video-info {
            margin-top: 30px;
            padding: 20px;
            background: var(--bg-dark);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: none;
            animation: fadeIn 0.5s;
        }

        .video-info.show {
            display: block;
        }

        .video-thumbnail {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .video-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .video-meta {
            font-size: 0.9rem;
            color: var(--text-dim);
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
            color: var(--text-dim);
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 3px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
            animation: fadeIn 0.3s;
        }

        .message.show {
            display: block;
        }

        .message.error {
            background: rgba(255, 0, 51, 0.1);
            border: 1px solid rgba(255, 0, 51, 0.3);
            color: var(--primary);
        }

        .message.success {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: var(--accent);
        }

        .footer {
            text-align: center;
            margin-top: 60px;
            color: var(--text-dim);
            font-size: 0.85rem;
        }

        @media (max-width: 600px) {
            .app {
                padding: 40px 20px;
            }

            .logo {
                font-size: 2rem;
            }

            .card {
                padding: 30px 20px;
            }

            .button-group {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="grain"></div>
    
    <div class="app">
        <div class="header">
            <h1 class="logo">
                <span class="logo-yt">YT</span><span class="logo-down">Download</span>
            </h1>
            <p class="tagline">Baixe vídeos e áudios do YouTube</p>
        </div>

        <div class="card">
            <div class="input-group">
                <label for="url">Cole o link do YouTube</label>
                <div class="input-wrapper">
                    <input 
                        type="text" 
                        id="url" 
                        placeholder="https://www.youtube.com/watch?v=..."
                        autocomplete="off"
                    >
                </div>
            </div>

            <div class="button-group">
                <button class="btn-video" id="downloadVideo">
                    <span>📹 Baixar Vídeo</span>
                </button>
                <button class="btn-audio" id="downloadAudio">
                    <span>🎵 Baixar Áudio</span>
                </button>
            </div>

            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Processando...</p>
            </div>

            <div class="message" id="message"></div>

            <div class="video-info" id="videoInfo">
                <img class="video-thumbnail" id="thumbnail" alt="Thumbnail">
                <h3 class="video-title" id="title"></h3>
                <p class="video-meta">
                    <span id="uploader"></span> • <span id="duration"></span>
                </p>
            </div>
        </div>

        <div class="footer">
            <p>Para uso pessoal e educacional</p>
        </div>
    </div>

    <script>
        const urlInput = document.getElementById('url');
        const downloadVideoBtn = document.getElementById('downloadVideo');
        const downloadAudioBtn = document.getElementById('downloadAudio');
        const loading = document.getElementById('loading');
        const message = document.getElementById('message');
        const videoInfo = document.getElementById('videoInfo');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function showMessage(text, type = 'error') {
            message.textContent = text;
            message.className = `message ${type} show`;
            setTimeout(() => message.classList.remove('show'), 5000);
        }

        function formatDuration(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        async function getVideoInfo(url) {
            try {
                const response = await fetch('/api/video-info', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ url })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('thumbnail').src = data.data.thumbnail;
                    document.getElementById('title').textContent = data.data.title;
                    document.getElementById('uploader').textContent = data.data.uploader;
                    document.getElementById('duration').textContent = formatDuration(data.data.duration);
                    videoInfo.classList.add('show');
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                showMessage('Erro ao obter informações do vídeo', 'error');
            }
        }

        async function download(type) {
            const url = urlInput.value.trim();

            if (!url) {
                showMessage('Por favor, cole um link do YouTube', 'error');
                return;
            }

            if (!url.includes('youtube.com') && !url.includes('youtu.be')) {
                showMessage('Link inválido. Use um link do YouTube', 'error');
                return;
            }

            loading.classList.add('show');
            downloadVideoBtn.disabled = true;
            downloadAudioBtn.disabled = true;
            message.classList.remove('show');

            try {
                // Obter informações do vídeo primeiro
                await getVideoInfo(url);

                // Iniciar download
                const response = await fetch('/api/download', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ url, type })
                });

                if (!response.ok) {
                    const data = await response.json();
                    throw new Error(data.message || 'Erro ao baixar');
                }

                // Criar blob e fazer download
                const blob = await response.blob();
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                
                // Extrair nome do arquivo do header
                const contentDisposition = response.headers.get('content-disposition');
                const filename = contentDisposition 
                    ? contentDisposition.split('filename=')[1].replace(/"/g, '')
                    : `download.${type === 'audio' ? 'mp3' : 'mp4'}`;
                
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(downloadUrl);
                a.remove();

                showMessage('Download concluído!', 'success');

            } catch (error) {
                showMessage(error.message, 'error');
            } finally {
                loading.classList.remove('show');
                downloadVideoBtn.disabled = false;
                downloadAudioBtn.disabled = false;
            }
        }

        downloadVideoBtn.addEventListener('click', () => download('video'));
        downloadAudioBtn.addEventListener('click', () => download('audio'));

        // Detectar quando usuário cola URL
        urlInput.addEventListener('paste', async (e) => {
            setTimeout(async () => {
                const url = urlInput.value.trim();
                if (url && (url.includes('youtube.com') || url.includes('youtu.be'))) {
                    await getVideoInfo(url);
                }
            }, 100);
        });
    </script>
</body>
</html>
