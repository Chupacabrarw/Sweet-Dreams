@extends('admin.layout')

@section('title', 'Detail Pesan Kontak')
@section('page-title', 'Detail Pesan Kontak')
@section('page-subtitle', 'Dikirim ' . $message->created_at->translatedFormat('d M Y, H:i'))

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/contact-messages.css')
@endpush

<a class="contact-back-link" href="{{ route('admin.contact-messages') }}">
    <i data-lucide="arrow-left" aria-hidden="true"></i>
    Kembali ke daftar pesan
</a>

<div class="admin-card contact-detail-card">
    <div class="contact-detail-header">
        <div>
            <span class="contact-status-pill {{ $message->status }}">{{ [
                'new' => 'Baru',
                'in_progress' => 'Diproses',
                'resolved' => 'Selesai',
            ][$message->status] ?? 'Baru' }}</span>
            <h2>{{ $topicLabel }}</h2>
            <p>Pesan #{{ $message->id }} · {{ $message->created_at->translatedFormat('d M Y, H:i') }}</p>
        </div>
    </div>

    <div class="contact-detail-grid">
        <section class="contact-detail-section">
            <h3>Informasi pengirim</h3>
            <dl>
                <div><dt>Nama</dt><dd>{{ $message->name }}</dd></div>
                <div><dt>Email</dt><dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd></div>
                <div><dt>WhatsApp</dt><dd><a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener noreferrer">{{ $message->phone }}</a></dd></div>
                <div><dt>Status akun</dt><dd>{{ $message->user ? 'Terkait akun: ' . $message->user->name . ' (' . $message->user->email . ')' : 'Pesan dikirim tanpa login' }}</dd></div>
                <div><dt>Nomor pesanan</dt><dd>{{ $message->order_number ?: 'Tidak dicantumkan' }}</dd></div>
            </dl>
        </section>

        <section class="contact-detail-section contact-message-body">
            <h3>Isi pesan</h3>
            <p>{{ $message->message }}</p>
        </section>
    </div>

    <form method="POST" action="{{ route('admin.contact-messages.update', $message) }}" class="contact-status-form">
        @csrf
        @method('PUT')
        <label for="contact-message-status">Status tindak lanjut</label>
        <select id="contact-message-status" name="status">
            <option value="new" @selected($message->status === 'new')>Baru</option>
            <option value="in_progress" @selected($message->status === 'in_progress')>Sedang diproses</option>
            <option value="resolved" @selected($message->status === 'resolved')>Selesai</option>
        </select>
        <button type="submit" class="contact-save-status">Simpan Status</button>
    </form>
</div>
@endsection
