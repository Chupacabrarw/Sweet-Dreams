<?php

namespace Tests\Feature;

use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSliderTest extends TestCase
{
    use RefreshDatabase;

    public function test_beranda_menampilkan_banner_dan_foto_slide_bawaan(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('data-hero-slider', $html);
        foreach (SiteContent::DEFAULT_BANNER_SLIDES as $slide) {
            $this->assertStringContainsString($slide, $html);
        }
        $this->assertSame(1 + count(SiteContent::DEFAULT_BANNER_SLIDES), substr_count($html, 'data-hero-index='));
    }

    public function test_admin_bisa_menambah_dan_menghapus_foto_slide(): void
    {
        Storage::fake('public_dir');
        config(['filesystems.upload_disk' => 'public_dir']);
        $admin = User::factory()->create(['role' => 'admin']);
        [$first, $second, $third] = SiteContent::DEFAULT_BANNER_SLIDES;

        $this->actingAs($admin)->put('/admin/konten/banner', [
            'title' => "Sweet Dreams\nStart Here",
            'remove_slides' => [$second, $third],
            'slides' => [UploadedFile::fake()->image('baru.jpg', 1920, 800)],
        ])->assertRedirect(route('admin.content.section', 'banner'))->assertSessionHasNoErrors();

        $slides = SiteContent::bannerSlides();
        $this->assertCount(2, $slides);
        $this->assertSame($first, $slides[0]);
        $this->assertStringStartsWith('images/banner-slide-', $slides[1]);
        Storage::disk('public_dir')->assertExists($slides[1]);

        // Foto slide terlalu kecil ditolak
        $this->actingAs($admin)->put('/admin/konten/banner', [
            'title' => 'Sweet Dreams',
            'slides' => [UploadedFile::fake()->image('kecil.jpg', 800, 600)],
        ])->assertSessionHasErrors('slides.0');
        $this->assertCount(2, SiteContent::bannerSlides());

        // Hapus semua → beranda kembali satu foto tanpa kontrol slider
        $this->actingAs($admin)->put('/admin/konten/banner', [
            'title' => 'Sweet Dreams',
            'remove_slides' => $slides,
        ])->assertSessionHasNoErrors();
        $this->assertSame([], SiteContent::bannerSlides());
        $this->get('/')->assertOk()->assertDontSee('data-hero-index=', false);
    }
}
