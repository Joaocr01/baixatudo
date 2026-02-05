<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use YoutubeDl\YoutubeDl;
use YoutubeDl\Options;

class DownloadController extends Controller
{
    private $youtubeDl;
    private $downloadPath;

    public function __construct()
    {
        $this->youtubeDl = new YoutubeDl();
        $this->downloadPath = env('DOWNLOAD_PATH', '/tmp/downloads');
        
        // Criar diretório se não existir
        if (!file_exists($this->downloadPath)) {
            mkdir($this->downloadPath, 0755, true);
        }
    }

    public function getVideoInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'URL inválida'
            ], 400);
        }

        try {
            $collection = $this->youtubeDl->download(
                Options::create()
                    ->skipDownload(true)
                    ->url($request->url)
            );

            $video = $collection->getVideos()[0];

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $video->getTitle(),
                    'thumbnail' => $video->getThumbnail(),
                    'duration' => $video->getDuration(),
                    'uploader' => $video->getUploader(),
                    'description' => $video->getDescription()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter informações do vídeo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'type' => 'required|in:video,audio'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parâmetros inválidos'
            ], 400);
        }

        try {
            $options = Options::create()
                ->downloadPath($this->downloadPath)
                ->url($request->url);

            if ($request->type === 'audio') {
                $options->extractAudio(true)
                    ->audioFormat('mp3')
                    ->audioQuality('0')
                    ->output('%(title)s.%(ext)s');
            } else {
                $options->format('best')
                    ->output('%(title)s.%(ext)s');
            }

            $collection = $this->youtubeDl->download($options);
            $video = $collection->getVideos()[0];

            if ($video->getError() !== null) {
                return response()->json([
                    'success' => false,
                    'message' => $video->getError()
                ], 500);
            }

            $file = $video->getFile();
            $filename = basename($file->getPathname());

            return response()->download(
                $file->getPathname(),
                $filename,
                ['Content-Type' => mime_content_type($file->getPathname())]
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao baixar: ' . $e->getMessage()
            ], 500);
        }
    }
}
