<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    private const TOPICS = [
        'produk' => 'Spesifikasi & Bahan Produk',
        'pesanan' => 'Status & Informasi Pesanan',
        'pengiriman' => 'Pengiriman & Resi',
        'retur' => 'Retur & Penukaran Barang',
        'reseller' => 'Daftar Jadi Reseller',
        'kerjasama' => 'Kerja Sama & Kolaborasi',
        'lainnya' => 'Lainnya',
    ];

    public function index(Request $request)
    {
        $filter = $request->query('status', 'all');
        abort_unless(in_array($filter, ['all', 'new', 'in_progress', 'resolved'], true), 404);

        $messages = ContactMessage::query()
            ->with('user:id,name,email')
            ->when($filter !== 'all', fn ($query) => $query->where('status', $filter))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact-messages', [
            'messages' => $messages,
            'activeFilter' => $filter,
            'unreadCount' => ContactMessage::whereNull('read_at')->count(),
            'counts' => [
                'all' => ContactMessage::count(),
                'new' => ContactMessage::where('status', 'new')->count(),
                'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
                'resolved' => ContactMessage::where('status', 'resolved')->count(),
            ],
        ]);
    }

    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->read_at === null) {
            $contactMessage->forceFill(['read_at' => now()])->save();
        }

        return view('admin.contact-message', [
            'message' => $contactMessage->load('user:id,name,email'),
            'topicLabel' => self::TOPICS[$contactMessage->topic] ?? 'Lainnya',
            'whatsappNumber' => preg_replace('/\D+/', '', preg_replace('/^0/', '62', $contactMessage->phone)),
        ]);
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,in_progress,resolved'],
        ]);

        $contactMessage->update($data);

        return redirect()
            ->route('admin.contact-messages.show', $contactMessage)
            ->with('success', 'Status pesan berhasil diperbarui.');
    }
}
