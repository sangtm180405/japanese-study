<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    use PerPageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('locked_status')) {
            if ($request->locked_status === 'locked') {
                $query->whereNotNull('locked_at');
            } elseif ($request->locked_status === 'unlocked') {
                $query->whereNull('locked_at');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($this->adminPerPage($request))->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update($request->only(['name', 'email', 'role']));
        Cache::forget('admin:dashboard:stats');

        return redirect()->route('admin.users.index')
                        ->with('success', 'User đã được cập nhật thành công!');
    }

    /**
     * Khóa tài khoản (admin chủ động khóa user).
     */
    public function lock(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Bạn không thể khóa chính mình!');
        }

        $reason = $request->input('reason', 'Khóa bởi quản trị viên.');
        $user->update([
            'locked_at' => now(),
            'locked_reason' => $reason,
        ]);
        SystemLog::add($user, 'user_locked', $user->name . ' (' . $user->email . ') bị khóa bởi admin.', ['source' => 'admin', 'reason' => $reason]);
        Cache::forget('admin:dashboard:stats');

        return redirect()->back()->with('success', 'Đã khóa tài khoản.');
    }

    /**
     * Mở khóa tài khoản (xóa locked_at, locked_reason).
     */
    public function unlock(User $user)
    {
        $user->update([
            'locked_at' => null,
            'locked_reason' => null,
        ]);
        SystemLog::add($user, 'user_unlocked', $user->name . ' (' . $user->email . ') được mở khóa bởi admin.', ['source' => 'admin']);
        Cache::forget('admin:dashboard:stats');

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'Đã mở khóa tài khoản.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Bạn không thể xóa chính mình!');
        }

        $user->delete();
        Cache::forget('admin:dashboard:stats');

        return redirect()->route('admin.users.index')
                        ->with('success', 'User đã được xóa thành công!');
    }
}
