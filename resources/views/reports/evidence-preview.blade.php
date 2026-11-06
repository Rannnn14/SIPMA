<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fileName }} | Pratinjau Berkas Bukti SIPMA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            width: 100%; height: 100%;
            background: #0b0f19;
            color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar */
        .preview-header {
            height: 56px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 100;
            flex-shrink: 0;
        }
        .file-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .file-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(13, 148, 136, 0.2);
            color: #2dd4bf;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .file-name {
            font-size: 0.95rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #f8fafc;
            max-width: 50vw;
        }
        .file-badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            flex-shrink: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .btn-action {
            height: 36px;
            padding: 0 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.06);
            color: #e2e8f0;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        .btn-close-action {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            width: 36px;
            padding: 0;
            justify-content: center;
        }
        .btn-close-action:hover {
            background: rgba(239, 68, 68, 0.8);
            color: #fff;
        }

        /* Content Area */
        .preview-body {
            flex: 1;
            width: 100%;
            height: calc(100% - 56px);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #030712;
        }

        .evidence-image {
            max-width: 95vw;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .video-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 16px;
            position: relative;
        }
        .evidence-video {
            max-width: min(96vw, 1280px);
            max-height: calc(100vh - 120px);
            width: 100%;
            border-radius: 12px;
            background: #000;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            outline: none;
        }

        .video-tips {
            margin-top: 10px;
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }
        .video-tips kbd {
            background: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: inherit;
            font-size: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .evidence-file {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
            background: #1e293b;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <header class="preview-header">
        <div class="file-info">
            <div class="file-icon">
                @if($isVideo)
                    <i class="fas fa-video"></i>
                @elseif($isImage)
                    <i class="fas fa-image"></i>
                @else
                    <i class="fas fa-file-alt"></i>
                @endif
            </div>
            <div class="file-name" title="{{ $fileName }}">{{ $fileName }}</div>
            <span class="file-badge">
                @if($isVideo)
                    Video
                @elseif($isImage)
                    Gambar
                @else
                    Dokumen
                @endif
            </span>
        </div>
        <div class="header-actions">
            <a href="{{ $streamUrl }}" download="{{ $fileName }}" class="btn-action" title="Unduh Berkas Bukti">
                <i class="fas fa-download"></i>
                <span class="d-none d-sm-inline">Unduh</span>
            </a>
            <button type="button" class="btn-action btn-close-action" aria-label="Tutup" title="Tutup pratinjau" onclick="window.close(); if (!window.closed) history.back();">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="preview-body">
        @if($isImage)
            <img class="evidence-image" src="{{ $streamUrl }}" alt="{{ $fileName }}">
        @elseif($isVideo)
            <div class="video-wrapper">
                <video id="evidenceVideo" class="evidence-video" controls autoplay playsinline preload="metadata">
                    <source src="{{ $streamUrl }}" type="{{ $fileMimeType ?: 'video/mp4' }}">
                    Browser Anda tidak mendukung pemutar video HTML5 langsung.
                </video>
                <div class="video-tips">
                    <span><kbd>Spasi</kbd> Putar / Jeda</span>
                    <span><kbd>←</kbd> <kbd>→</kbd> Geser ±5 Detik</span>
                    <span><kbd>↑</kbd> <kbd>↓</kbd> Volume</span>
                    <span><kbd>F</kbd> Layar Penuh</span>
                </div>
            </div>
        @else
            <iframe class="evidence-file" src="{{ $streamUrl }}" title="{{ $fileName }}"></iframe>
        @endif
    </main>

    @if($isVideo)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('evidenceVideo');
            if (!video) return;

            // Keyboard navigation for smooth scrubbing / control
            window.addEventListener('keydown', function(e) {
                // Ignore if focus is in an input or textarea
                if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

                switch(e.key) {
                    case ' ':
                        e.preventDefault();
                        if (video.paused) video.play(); else video.pause();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        video.currentTime = Math.max(0, video.currentTime - 5);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        video.currentTime = Math.min(video.duration || 999999, video.currentTime + 5);
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        video.volume = Math.min(1, video.volume + 0.1);
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        video.volume = Math.max(0, video.volume - 0.1);
                        break;
                    case 'f':
                    case 'F':
                        e.preventDefault();
                        if (!document.fullscreenElement) {
                            video.requestFullscreen().catch(() => {});
                        } else {
                            document.exitFullscreen().catch(() => {});
                        }
                        break;
                    case 'm':
                    case 'M':
                        e.preventDefault();
                        video.muted = !video.muted;
                        break;
                }
            });
        });
    </script>
    @endif
</body>
</html>
