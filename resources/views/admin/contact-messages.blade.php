@extends('admin.layout')

@section('title', 'Pesan Kontak')
@section('page-title', 'Pesan Kontak')
@section('page-subtitle', 'Pesan yang dikirim melalui formulir Hubungi Kami')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/contact-messages.css')
@endpush

<div class="contact-inbox-heading">
    <div>
        <h2>Kotak Masuk</h2>
        <p>Kelola pertanyaan yang dikirim pelanggan melalui halaman kontak.</p>
    </div>
    <div class="contact-inbox-unread">
        <span class="contact-unread-dot"></span>
        <strong>{{ $unreadCount }}</strong>
        <span>belum dibaca</span>
    </div>
</div>

<nav class="contact-filter-tabs" aria-label="Filter pesan kontak">
    <a href="{{ route('admin.contact-messages') }}" class="{{ $activeFilter === 'all' ? 'active' : '' }}">Semua <span>{{ $counts['all'] }}</span></a>
    <a href="{{ route('admin.contact-messages', ['status' => 'new']) }}" class="{{ $activeFilter === 'new' ? 'active' : '' }}">Baru <span>{{ $counts['new'] }}</span></a>
    <a href="{{ route('admin.contact-messages', ['status' => 'in_progress']) }}" class="{{ $activeFilter === 'in_progress' ? 'active' : '' }}">Diproses <span>{{ $counts['in_progress'] }}</span></a>
    <a href="{{ route('admin.contact-messages', ['status' => 'resolved']) }}" class="{{ $activeFilter === 'resolved' ? 'active' : '' }}">Selesai <span>{{ $counts['resolved'] }}</span></a>
</nav>

<div class="contact-message-list">
    @if($messages->isEmpty())
        <div class="contact-message-empty">
            <i data-lucide="messages-square" aria-hidden="true"></i>
            <p>Belum ada pesan untuk filter ini.</p>
        </div>
    @else
        @foreach($messages as $message)
            <a class="contact-message-card {{ $message->read_at ? '' : 'unread' }}" href="{{ route('admin.contact-messages.show', $message) }}">
                <div class="contact-message-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($message->name, 0, 1)) }}</div>
                <div class="contact-message-main">
                    <div class="contact-message-card-top">
                        <div class="contact-message-sender">
                            <strong>{{ $message->name }}</strong>
                            <span>{{ $message->email }}</span>
                        </div>
                        <time datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->translatedFormat('d M Y · H:i') }}</time>
                    </div>
                    <div class="contact-message-card-meta">
                        <span class="contact-topic-pill">{{ \Illuminate\Support\Arr::get([
                            'produk' => 'Produk',
                            'pesanan' => 'Pesanan',
                            'pengiriman' => 'Pengiriman',
                            'retur' => 'Retur',
                            'reseller' => 'Reseller',
                            'kerjasama' => 'Kerja sama',
                            'lainnya' => 'Lainnya',
                        ], $message->topic, 'Lainnya') }}</span>
                        <span class="contact-account-label">{{ $message->user ? 'Akun pelanggan: ' . $message->user->name : 'Dikirim tanpa login' }}</span>
                        @if($message->order_number)
                            <span class="contact-order-reference">Order {{ $message->order_number }}</span>
                        @endif
                    </div>
                    <p class="contact-message-preview">{{ \Illuminate\Support\Str::limit($message->message, 180) }}</p>
                </div>
                <div class="contact-message-card-side">
                    <span class="contact-status-pill {{ $message->status }}">{{ [
                        'new' => 'Baru',
                        'in_progress' => 'Diproses',
                        'resolved' => 'Selesai',
                    ][$message->status] ?? 'Baru' }}</span>
                    @if(!$message->read_at)
                        <span class="contact-unread-label"><span class="contact-unread-dot"></span> Belum dibaca</span>
                    @endif
                    <span class="contact-open-link">Buka pesan <i data-lucide="arrow-up-right" aria-hidden="true"></i></span>
                </div>
            </a>
        @endforeach
        <div class="contact-message-pagination">{{ $messages->links() }}</div>
    @endif
</div>
@endsection
