<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'topic' => ['required', 'in:produk,pesanan,pengiriman,retur,reseller,kerjasama,lainnya'],
            'order_number' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:5', 'max:5000'],
            'privacy' => ['accepted'],
        ]);

        $messageData = [
            ...$data,
            'status' => 'new',
        ];

        if ($request->user()) {
            $request->user()->contactMessages()->create($messageData);
        } else {
            ContactMessage::create($messageData);
        }

        return response()->json([
            'message' => 'Pesan berhasil dikirim. Tim kami akan menghubungi Anda melalui email atau WhatsApp.',
        ], 201);
    }
}
