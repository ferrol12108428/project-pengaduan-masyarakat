<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        // ...$role akan mengubah yg dipisah dengan koma manjadi item array, namanya spread operator
        // $request->user()->role akan ambil data user yang login bagian role
        if (in_array($request->user()->role, $role)) {
            return $next($request);
        } else {
            return redirect()->back();
        }
    }
}
