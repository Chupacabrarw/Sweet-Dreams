<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'label'          => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'required|string',
            'city'           => 'nullable|string|max:255',
            'city_id'        => 'nullable|string|max:20',
            'province'       => 'nullable|string|max:255',
            'province_id'    => 'nullable|string|max:20',
            'postal_code'    => 'nullable|string|max:10',
            'is_primary'     => 'nullable|boolean',
        ]);

        $user = $request->user();

        if (!empty($data['is_primary'])) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $address = $user->addresses()->create($data);

        return response()->json(['message' => 'Alamat berhasil ditambahkan.', 'address' => $address], 201);
    }

    public function update(Request $request, Address $address)
    {
        abort_if($address->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'label'          => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'required|string',
            'city'           => 'nullable|string|max:255',
            'city_id'        => 'nullable|string|max:20',
            'province'       => 'nullable|string|max:255',
            'province_id'    => 'nullable|string|max:20',
            'postal_code'    => 'nullable|string|max:10',
            'is_primary'     => 'nullable|boolean',
        ]);

        if (!empty($data['is_primary'])) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_primary' => false]);
        }

        $address->update($data);

        return response()->json(['message' => 'Alamat berhasil diubah.', 'address' => $address]);
    }

    public function destroy(Request $request, Address $address)
    {
        abort_if($address->user_id !== $request->user()->id, 403);
        $address->delete();

        return response()->json(['message' => 'Alamat berhasil dihapus.']);
    }

    public function setPrimary(Request $request, Address $address)
    {
        abort_if($address->user_id !== $request->user()->id, 403);

        $request->user()->addresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return response()->json(['message' => 'Alamat utama berhasil diubah.']);
    }
}