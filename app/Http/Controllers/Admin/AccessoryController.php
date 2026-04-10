<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class AccessoryController extends Controller
{
    public function index()
    {
        $types = Accessory::types();
        return view('admin.pages.accessories.index', compact('types'));
    }

    public function getData(Request $request)
    {
        $query = Accessory::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('image_url',    fn ($a) => $a->image_url)
            ->addColumn('type_label',   fn ($a) => $a->type_label)
            ->addColumn('stock_status', fn ($a) => $a->stock_status)
            ->addColumn('status_label', fn ($a) => $a->is_active ? 'Active' : 'Inactive')
            ->addColumn('price_fmt',    fn ($a) => '$' . number_format($a->price, 2))
            ->addColumn('actions', fn ($a) => [
                'showUrl'   => route('admin.accessories.show', $a),
                'editUrl'   => route('admin.accessories.edit', $a),
                'deleteUrl' => route('admin.accessories.destroy', $a),
                'toggleUrl' => route('admin.accessories.toggle', $a),
            ])
            ->make(true);
    }

    public function create()
    {
        $types = Accessory::types();
        return view('admin.pages.accessories.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:' . implode(',', array_keys(Accessory::types())),
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug']        = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images/accessories'), $filename);
            $data['image'] = $filename;
        }

        Accessory::create($data);

        return redirect()->route('admin.accessories.index')
            ->with('success', 'Accessory added successfully.');
    }

    public function show(Accessory $accessory)
    {
        return view('admin.pages.accessories.show', compact('accessory'));
    }

    public function edit(Accessory $accessory)
    {
        $types = Accessory::types();
        return view('admin.pages.accessories.edit', compact('accessory', 'types'));
    }

    public function update(Request $request, Accessory $accessory)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:' . implode(',', array_keys(Accessory::types())),
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug']        = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($accessory->image && file_exists(public_path('uploads/images/accessories/' . $accessory->image))) {
                unlink(public_path('uploads/images/accessories/' . $accessory->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images/accessories'), $filename);
            $data['image'] = $filename;
        }

        $accessory->update($data);

        return redirect()->route('admin.accessories.index')
            ->with('success', 'Accessory updated successfully.');
    }

    public function toggle(Accessory $accessory)
    {
        $accessory->update(['is_active' => !$accessory->is_active]);
        return response()->json(['is_active' => $accessory->is_active]);
    }

    public function destroy(Accessory $accessory)
    {
        if ($accessory->image && file_exists(public_path('uploads/images/accessories/' . $accessory->image))) {
            unlink(public_path('uploads/images/accessories/' . $accessory->image));
        }

        $accessory->delete();

        return redirect()->route('admin.accessories.index')
            ->with('success', 'Accessory deleted successfully.');
    }
}
