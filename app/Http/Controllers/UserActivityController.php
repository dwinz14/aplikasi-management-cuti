<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserActivityController extends Controller
{
    public function index()
    {
        // Subquery to get max last_activity per user from sessions
        $maxActivitySub = DB::table('sessions')
            ->select('user_id', DB::raw('MAX(last_activity) as max_last_activity'))
            ->groupBy('user_id');

        // Get all users with online/offline status based on last activity within 30 minutes
        $users = DB::table('users')
            ->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
            ->leftJoinSub($maxActivitySub, 'max_sessions', function ($join) {
                $join->on('users.id', '=', 'max_sessions.user_id');
            })
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.role',
                'users.last_login_at',
                'divisions.nama_divisi as division_name',
                DB::raw('CASE WHEN max_sessions.max_last_activity > ' . now()->subMinutes(30)->timestamp . ' THEN 1 ELSE 0 END as is_online')
            )
            ->orderBy('is_online', 'desc')
            ->orderByRaw('CASE WHEN is_online = 1 THEN max_sessions.max_last_activity ELSE users.last_login_at END desc')
            ->paginate(5);

        $onlineCount = $users->where('is_online', 1)->count();

        return view('admin.user-activity.index', compact('users', 'onlineCount'));
    }
}
