<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'label'       => 'nullable|string|max:50',
            'name'        => 'required|string|max:100',
            'address'     => 'required|string|max:255',
            'address2'    => 'nullable|string|max:255',
            'city'        => 'required|string|max:100',
            'state'       => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country'     => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'is_default'  => 'boolean',
        ]);

        $userId = auth()->id();
        $data['user_id'] = $userId;
        $data['label']   = $data['label'] ?: 'Home';
        $data['country'] = $data['country'] ?: 'Pakistan';

        if (! empty($data['is_default'])) {
            Address::where('user_id', $userId)->update(['is_default' => false]);
        }

        // If it's the first address, make it default automatically
        if (Address::where('user_id', $userId)->count() === 0) {
            $data['is_default'] = true;
        }

        $address = Address::create($data);

        return response()->json(['success' => true, 'address' => $address]);
    }

    public function update(Request $request, Address $address): JsonResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $data = $request->validate([
            'label'       => 'nullable|string|max:50',
            'name'        => 'required|string|max:100',
            'address'     => 'required|string|max:255',
            'address2'    => 'nullable|string|max:255',
            'city'        => 'required|string|max:100',
            'state'       => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country'     => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'is_default'  => 'boolean',
        ]);

        if (! empty($data['is_default'])) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $address->update($data);

        return response()->json(['success' => true, 'address' => $address->fresh()]);
    }

    public function destroy(Address $address): JsonResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $wasDefault = $address->is_default;
        $address->delete();

        // Assign default to the next address if deleted one was default
        if ($wasDefault) {
            $next = Address::where('user_id', auth()->id())->first();
            $next?->update(['is_default' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function setDefault(Address $address): JsonResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json(['success' => true]);
    }
}
