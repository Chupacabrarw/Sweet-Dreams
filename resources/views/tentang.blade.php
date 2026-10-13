@extends('layouts.app')

@section('title', 'Tentang Kami - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/tentang.css')
@endpush

{{-- HERO --}}
<section class="tentang-hero" id="tentang-hero">
    <img src="{{ asset($t['tentang_hero_image']) }}" alt="Sweet Dreams - Tentang Kami" class="tentang-hero-img" loading="eager">
    <div class="tentang-hero-overlay">
        <div class="tentang-breadcrumb">
            <a href="/">Home</a>
            <span class="separator">/</span>
            <span>Tentang Kami</span>
        </div>
        <h1>{{ $t['tentang_hero_title'] }}</h1>
        <p>{{ $t['tentang_hero_subtitle'] }}</p>
    </div>
</section>

{{-- STORY --}}
<section class="tentang-story reveal" id="tentang-story">
    <div class="tentang-story-img">
        <img src="{{ asset($t['tentang_story_image']) }}" alt="Sweet Dreams Story" loading="lazy">
    </div>
    <div class="tentang-story-text">
        <span class="label">{{ $t['tentang_story_label'] }}</span>
        <h2>{{ $t['tentang_story_title'] }}</h2>
        <p>{{ $t['tentang_story_p1'] }}</p>
        <p>{{ $t['tentang_story_p2'] }}</p>
        <p>{{ $t['tentang_story_p3'] }}</p>
    </div>
</section>

{{-- VALUES --}}
<section class="tentang-values" id="tentang-values">
    <div class="tentang-values-inner">
        <div class="tentang-values-header reveal">
            <h2>{{ $t['tentang_values_title'] }}</h2>
            <p>{{ $t['tentang_values_subtitle'] }}</p>
        </div>
        <div class="tentang-values-grid">
            <div class="value-card reveal reveal-delay-1" id="value-kualitas">
                <div class="icon-wrap">
                    <i data-lucide="gem" style="width:28px;height:28px;"></i>
                </div>
                <h3>{{ $t['tentang_value1_title'] }}</h3>
                <p>{{ $t['tentang_value1_body'] }}</p>
            </div>
            <div class="value-card reveal reveal-delay-2" id="value-kenyamanan">
                <div class="icon-wrap">
                    <i data-lucide="heart" style="width:28px;height:28px;"></i>
                </div>
                <h3>{{ $t['tentang_value2_title'] }}</h3>
                <p>{{ $t['tentang_value2_body'] }}</p>
            </div>
            <div class="value-card reveal reveal-delay-3" id="value-desain">
                <div class="icon-wrap">
                    <i data-lucide="sparkles" style="width:28px;height:28px;"></i>
                </div>
                <h3>{{ $t['tentang_value3_title'] }}</h3>
                <p>{{ $t['tentang_value3_body'] }}</p>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="tentang-stats reveal" id="tentang-stats">
    <div class="tentang-stats-grid">
        <div class="stat-item" id="stat-pelanggan">
            <div class="stat-number">{{ $t['tentang_stat1_num'] }}</div>
            <div class="stat-label">{{ $t['tentang_stat1_label'] }}</div>
        </div>
        <div class="stat-item" id="stat-produk">
            <div class="stat-number">{{ $t['tentang_stat2_num'] }}</div>
            <div class="stat-label">{{ $t['tentang_stat2_label'] }}</div>
        </div>
        <div class="stat-item" id="stat-kota">
            <div class="stat-number">{{ $t['tentang_stat3_num'] }}</div>
            <div class="stat-label">{{ $t['tentang_stat3_label'] }}</div>
        </div>
        <div class="stat-item" id="stat-rating">
            <div class="stat-number">{{ $t['tentang_stat4_num'] }}</div>
            <div class="stat-label">{{ $t['tentang_stat4_label'] }}</div>
        </div>
    </div>
</section>

{{-- VISION & MISSION --}}
<section class="tentang-vimi" id="tentang-vimi">
    <div class="tentang-vimi-inner">
        <div class="vimi-card reveal reveal-delay-1" id="vimi-visi">
            <div class="vimi-icon">
                <i data-lucide="eye" style="width:22px;height:22px;"></i>
            </div>
            <h3>{{ $t['tentang_visi_title'] }}</h3>
            <p>{{ $t['tentang_visi_body'] }}</p>
        </div>
        <div class="vimi-card reveal reveal-delay-2" id="vimi-misi">
            <div class="vimi-icon">
                <i data-lucide="target" style="width:22px;height:22px;"></i>
            </div>
            <h3>{{ $t['tentang_misi_title'] }}</h3>
            <p>{{ $t['tentang_misi_body'] }}</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="tentang-cta reveal" id="tentang-cta">
    <div class="tentang-cta-card">
        <h2>{{ $t['tentang_cta_title'] }}</h2>
        <p>{{ $t['tentang_cta_body'] }}</p>
        <a href="/katalog" class="tentang-cta-btn" id="btn-cta-katalog">
            {{ $t['tentang_cta_button'] }}
            <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
        </a>
    </div>
</section>
@endsection
