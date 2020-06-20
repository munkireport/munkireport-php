<?php

namespace App\Http\Middleware;

use Closure;

class RemoveIndexPhp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $searchFor = "index.php?";
        $strPosition = strpos($request->fullUrl(), $searchFor);

        if ($strPosition !== false) {
//            $url = substr($request->fullUrl(), $strPosition + strlen($searchFor));
//            print_r($url);
//            die();
            return redirect($_SERVER['QUERY_STRING'], 301);
        }

        return $next($request);
    }
}
