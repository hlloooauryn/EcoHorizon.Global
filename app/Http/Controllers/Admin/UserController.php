<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,operator,customer'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:active,suspended'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'company_name' => $validated['company_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        AuditLog::log('CREATE', $user, "Created user {$user->email} with role {$user->role}.");

        return redirect()->route('admin.users.index')->with('success', "User {$user->email} created successfully.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:admin,operator,customer'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:active,suspended'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $old = $user->toArray();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->company_name = $validated['company_name'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        AuditLog::log('UPDATE', $user, "Updated user {$user->email}.", $old, $user->toArray());

        return redirect()->route('admin.users.index')->with('success', "User {$user->email} updated.");
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own account status.');
        }

        $old = $user->status;
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        AuditLog::log('UPDATE', $user, "Changed status of {$user->email} from {$old} to {$user->status}.");

        return back()->with('success', "User status updated to {$user->status}.");
    }
}

