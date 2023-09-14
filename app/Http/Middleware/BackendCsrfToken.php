<?php

namespace app\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;

class BackendCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {   
        try {
            return parent::handle($request, $next);
        } catch (\Exception $e) {
            $result['code'] = 2;
            $result['msg'] = "页面过期,请刷新";
            $result['data'] = [];
            return response()->json($result);
        }
    }
}
