<?php

namespace App\Http\Controllers;

use App\Services\SiteAssistant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __invoke(Request $request, SiteAssistant $assistant): JsonResponse
    {
        if (! $assistant->enabled()) {
            return response()->json(['message' => 'Asisten virtual sedang dinonaktifkan.'], 503);
        }

        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'history' => ['nullable', 'array', 'max:8'],
            'history.*.role' => ['required', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:1500'],
        ], [
            'message.required' => 'Silakan tulis pertanyaan Anda.',
            'message.max' => 'Pertanyaan terlalu panjang (maksimal 500 karakter).',
        ]);

        return response()->json($assistant->reply(trim($data['message']), $data['history'] ?? []));
    }
}
