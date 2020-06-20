<?php

namespace App\Http\Middleware;

use Closure;

class RemoveIndexPhp
{
    /**
     * Remove the index.php? section from the URL if the module is a legacy module.
     *
     * Initially i just did a 301 redirect to the non index.php? URL, but this blows away the POST data from
     * datatable generation in Tablequery.php.
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

            $pi = $request->getPathInfo();
            print_r($pi);
            //return redirect($_SERVER['QUERY_STRING'], 301);
        }

        return $next($request);
    }
}
