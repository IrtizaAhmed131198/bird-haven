<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.pages.users.index');
    }

    public function getData(Request $request)
    {
        $query = User::query()
            ->select(['id', 'name', 'email', 'avatar', 'is_admin', 'is_active', 'created_at']);

        if ($request->filled('role')) {
            $query->where('is_admin', $request->role === 'admin');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        return DataTables::eloquent($query)
            ->addColumn('avatar_url', fn ($user) => $user->avatar_url)
            ->addColumn('initials', fn ($user) => strtoupper(substr($user->name, 0, 2)))
            ->addColumn('role_label', fn ($user) => $user->is_admin ? 'Admin' : 'Customer')
            ->addColumn('status_label', fn ($user) => $user->is_active ? 'Active' : 'Inactive')
            ->addColumn('joined', fn ($user) => $user->created_at->format('d M, Y'))
            ->addColumn('actions', function ($user) {
                return [
                    'showUrl'   => route('admin.users.show', $user),
                    'editUrl'   => route('admin.users.edit', $user),
                    'deleteUrl' => route('admin.users.destroy', $user),
                ];
            })
            ->make(true);
    }

    public function create()
    {
        return view('admin.pages.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => ['required', Password::defaults()],
            'is_admin'  => 'boolean',
            'is_active' => 'boolean',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $avatarName = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(public_path('uploads/images/avatars'), $avatarName);
        }

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_admin'  => $request->boolean('is_admin'),
            'is_active' => $request->boolean('is_active', true),
            'avatar'    => $avatarName,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('orders');
        return view('admin.pages.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.pages.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => ['nullable', Password::defaults()],
            'is_admin'  => 'boolean',
            'is_active' => 'boolean',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        $avatarName = $user->avatar;
        if ($request->hasFile('avatar')) {
            // Delete old avatar file if it exists
            if ($user->avatar && file_exists(public_path('uploads/images/avatars/' . $user->avatar))) {
                unlink(public_path('uploads/images/avatars/' . $user->avatar));
            }
            $avatarName = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(public_path('uploads/images/avatars'), $avatarName);
        }

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'is_admin'  => $request->boolean('is_admin'),
            'is_active' => $request->boolean('is_active'),
            'avatar'    => $avatarName,
            ...($request->filled('password') ? ['password' => Hash::make($request->password)] : []),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->avatar && file_exists(public_path('uploads/images/avatars/' . $user->avatar))) {
            unlink(public_path('uploads/images/avatars/' . $user->avatar));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
