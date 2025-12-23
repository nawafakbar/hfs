<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chatbot(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $systemPrompt = "Kamu adalah chatbot ahli HIDROPONIK.
Jika pertanyaan di luar topik hidroponik, arahkan kembali ke hidroponik secara sopan.";

        $response = Http::withToken(env('CHAT_GPT_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $request->message],
                ],
                'temperature' => 0.5,
            ]);

        return response()->json([
            'reply' => $response['choices'][0]['message']['content']
        ]);
    }
}
