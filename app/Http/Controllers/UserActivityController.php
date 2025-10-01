<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserActivityController extends Controller
{
    public function index()
    {
        $threshold = now()->subMinutes(30)->timestamp;

        // Subquery untuk ambil last_activity terakhir tiap user
        $maxActivitySub = DB::table('sessions')
            ->select('user_id', DB::raw('MAX(last_activity) as max_last_activity'))
            ->groupBy('user_id');

        // Query utama
        $users = DB::table('users')
            ->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
            ->leftJoinSub($maxActivitySub, 'max_sessions', function ($join) {
                $join->on('users.id', '=', 'max_sessions.user_id');
            })
            ->selectRaw("
                users.id,
                users.name,
                users.email,
                users.role,
                users.last_login_at,
                divisions.nama_divisi as division_name,
                CASE WHEN max_sessions.max_last_activity > ? THEN 1 ELSE 0 END as is_online
            ", [$threshold])
            ->orderBy('is_online', 'desc')
            ->orderByRaw("
                CASE WHEN
                    max_sessions.max_last_activity IS NOT NULL
                THEN max_sessions.max_last_activity
                ELSE users.last_login_at
                END DESC
            ")
            ->paginate(5);

        // Count online users separately without pagination
        $onlineCount = DB::table('users')
            ->leftJoinSub($maxActivitySub, 'max_sessions', function ($join) {
                $join->on('users.id', '=', 'max_sessions.user_id');
            })
            ->where('max_sessions.max_last_activity', '>', $threshold)
            ->count();

        return view('admin.user-activity.index', compact('users', 'onlineCount'));
    }
}
