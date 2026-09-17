<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    protected function defaults(): array
    {
        return [
            'banner_homepage' => [
                'title' => "Sweet Dreams\nStart Here",
                'subtitle' => 'Koleksi sleepwear & lingerie premium untuk kenyamanan dan kepercayaan dirimu.',
                'button_text' => 'Shop Now',
                'link' => '/katalog',
                'image' => 'images/hero-banner.jpg',
            ],
            'info_toko' => [
                'body' => 'Sweet Dreams adalah brand sleepwear wanita yang menghadirkan kenyamanan premium sejak 2019.',
            ],
            'kebijakan_retur' => [
                'body' => 'Produk dapat diretur maksimal 7 hari setelah diterima, dengan tag dan kemasan utuh.',
            ],
            'panduan_ukuran' => [
                'body' => 'Ukur lingkar dada, pinggang, dan pinggul. Cocokkan hasil dengan tabel ukuran kami.',
            ],
        ];
    }

    protected function getOrCreate(string $key): SiteContent
    {
        $defaults = $this->defaults()[$key] ?? [];
        return SiteContent::firstOrCreate(['key' => $key], $defaults);
    }

    public function index()
    {
        return view('admin.content', [
            'banner' => $this->getOrCreate('banner_homepage'),
            'infoToko' => $this->getOrCreate('info_toko'),
            'kebijakanRetur' => $this->getOrCreate('kebijakan_retur'),
            'panduanUkuran' => $this->getOrCreate('panduan_ukuran'),
        ]);
    }

    public function updateBanner(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'link' => 'nullable|string|max:255',
        ]);

        $banner = $this->getOrCreate('banner_homepage');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['image'] = 'images/' . $filename;
        }

        $banner->update($data);

        return redirect()->route('admin.content')->with('success', 'Banner homepage berhasil diperbarui.');
    }

    public function updateText(Request $request, string $key)
    {
        $request->validate(['body' => 'required|string']);

        $content = $this->getOrCreate($key);
        $content->update(['body' => $request->body]);

        return redirect()->route('admin.content')->with('success', 'Konten berhasil diperbarui.');
    }
}