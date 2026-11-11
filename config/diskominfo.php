<?php

return [
    // Rahasia penanda tangan token API. Bila kosong, APP_KEY dipakai.
    'api_token_secret' => env('API_TOKEN_SECRET'),
    'api_token_ttl' => (int) env('API_TOKEN_TTL', 86400), // detik (24 jam)

    // Kata sandi admin awal untuk `db:seed` (kosong: 'password' di lokal, acak di produksi).
    'admin_password' => env('ADMIN_PASSWORD'),

    // Di belakang proxy/CDN (mis. Cloudflare): '*' atau daftar IP dipisah koma.
    'trusted_proxies' => env('TRUSTED_PROXIES'),

    // Batas unggahan (KB)
    'max_image_kb' => 5120,
    'max_file_kb' => 10240,

    // Asisten virtual (widget "AI" di website publik)
    'ai' => [
        'api_key' => env('ANTHROPIC_API_KEY'),       // kosong = mode pencarian (tanpa biaya)
        'model' => env('AI_MODEL', 'claude-haiku-5-5'),
        'max_tokens' => (int) env('AI_MAX_TOKENS', 600),
        'timeout' => (int) env('AI_TIMEOUT', 20),
        'per_minute' => (int) env('AI_PER_MINUTE', 8),   // batas pesan per IP
        'per_day' => (int) env('AI_PER_DAY', 60),
    ],
];
