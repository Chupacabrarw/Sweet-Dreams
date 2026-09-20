<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $data = $request->validate([
            'message'  => 'required|string|max:1000',
            'history'  => 'nullable|array',
        ]);

        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => 'Maaf, chatbot belum dikonfigurasi. Silakan hubungi admin.',
            ], 503);
        }

        // Build conversation history
        $contents = [];

        foreach ($data['history'] ?? [] as $turn) {
            if (!empty($turn['role']) && !empty($turn['text'])) {
                $contents[] = [
                    'role'  => $turn['role'] === 'user' ? 'user' : 'model',
                    'parts' => [['text' => $turn['text']]],
                ];
            }
        }

        // Add current user message
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $data['message']]],
        ];

        $systemPrompt = "Kamu adalah Dreamy, asisten virtual toko Sweet Dreams - brand sleepwear & lingerie premium dari Indonesia. "
            . "Tugasmu adalah membantu pelanggan dengan ramah, sopan, dan profesional. "
            . "Kamu bisa membantu pelanggan terkait: informasi produk (piyama, kimono, daster, lingerie), panduan ukuran, info pengiriman, status pesanan, promo & voucher, cara pembayaran, dan kebijakan retur. "
            . "Toko Sweet Dreams menawarkan gratis ongkir untuk pembelian di atas Rp500.000. "
            . "Metode pembayaran: QRIS, Transfer Bank, E-Wallet (GoPay, OVO, DANA). "
            . "Jam operasional: Senin-Sabtu 10.00-19.00 WIB, Minggu 10.00-16.00 WIB. "
            . "Email: halo@sweetdream.id. "
            . "Jika kamu tidak tahu jawabannya, sarankan pelanggan untuk menghubungi customer service melalui WhatsApp atau email. "
            . "Gunakan bahasa Indonesia yang hangat dan friendly. Tambahkan emoji yang relevan agar terasa personal. "
            . "Jawaban maksimal 3 paragraf pendek. Jangan pernah mengarang informasi produk yang tidak kamu ketahui.";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(20)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$apiKey}",
                [
                    'system_instruction' => [
                        'parts' => [['text' => $systemPrompt]],
                    ],
                    'contents'           => $contents,
                    'generationConfig'   => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 512,
                    ],
                ]
            );

            if ($response->failed()) {
                return response()->json([
                    'reply' => 'Maaf, saya sedang tidak dapat merespons. Silakan coba lagi sebentar. 😊',
                ], 200);
            }

            $result = $response->json();
            $reply  = $result['candidates'][0]['content']['parts'][0]['text']
                      ?? 'Maaf, saya tidak mengerti. Bisa ulangi pertanyaannya? 😊';

            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, terjadi gangguan koneksi. Silakan coba beberapa saat lagi. 🙏',
            ], 200);
        }
    }
}
