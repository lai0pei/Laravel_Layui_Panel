<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
class BackendAuth extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $id = get_credential('admin_id');
   
        if(empty($id)){
            if(!$request->route()->named('f_login')){
                return redirect()->route('f_login');
            }  
        }
       
        return $next($request);
    }
}
