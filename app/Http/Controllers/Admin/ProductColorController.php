<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductColor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductColorController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:60',
            'hex' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $name = trim($data['name']);
        $slug = Str::slug($name);

        if ($slug === '') {
            throw ValidationException::withMessages([
                'name' => 'Nama warna harus berisi huruf atau angka.',
            ]);
        }

        if (ProductColor::where('slug', $slug)->exists()) {
            throw ValidationException::withMessages([
                'name' => 'Warna dengan nama tersebut sudah tersimpan. Pilih warna itu dari daftar.',
            ]);
        }

        $color = ProductColor::create([
            'name' => $name,
            'slug' => $slug,
            'hex' => strtoupper($data['hex']),
        ]);

        return response()->json([
            'message' => 'Warna berhasil disimpan.',
            'color' => [
                'id' => $color->id,
                'name' => $color->name,
                'slug' => $color->slug,
                'hex' => $color->hex,
            ],
        ], 201);
    }
}
