<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fileName }} | SIPMA</title>
    <style>
        * { box-sizing: border-box; }
        html, body { width: 100%; height: 100%; margin: 0; overflow: hidden; background: #111; }
        .evidence-file { position: fixed; inset: 0; width: 100vw; height: 100vh; border: 0; display: block; background: #111; }
        .evidence-image { position: fixed; inset: 0; width: 100vw; height: 100vh; padding: 12px; object-fit: contain; display: block; }
        .close-preview {
            position: fixed; top: 12px; right: 14px; z-index: 10;
            width: 38px; height: 38px; border: 0; border-radius: 50%;
            display: grid; place-items: center; color: #fff; background: rgba(15, 23, 42, .82);
            font: 28px/1 Arial, sans-serif; cursor: pointer;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .35);
        }
        .close-preview:hover { background: #0d9488; }
    </style>
</head>
<body>
    <button class="close-preview" type="button" aria-label="Tutup berkas" title="Tutup berkas" onclick="window.close(); if (!window.closed) history.back();">&times;</button>
    @if(str_starts_with($fileMimeType ?? '', 'image/'))
        <img class="evidence-image" src="{{ asset('storage/' . $filePath) }}" alt="{{ $fileName }}">
    @else
        <iframe class="evidence-file" src="{{ asset('storage/' . $filePath) }}" title="{{ $fileName }}"></iframe>
    @endif
</body>
</html>
