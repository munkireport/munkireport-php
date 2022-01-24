<?php


namespace Compatibility\Kiss\Http;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * This subclass of Laravel's Request class exists to inspect the Request and modify it if it is in the old style,
 * KISSMVC URL path such as /index.php?/controller/action.
 *
 * @package Compatibility\Kiss\Http
 */
class Request extends \Illuminate\Http\Request
{
    /**
     * Create an Illuminate request from a Symfony instance.
     *
     * Patched for MunkiReport:
     * - If URI is "/" then this may be a case where index.php has been stripped, and the Query string contains the
     *   actual controller/action/param routes.
     * - PHP will add underscores to periods in the query string, to avoid this you need to use
     *   $_SERVER["QUERY_STRING"]
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return \Illuminate\Http\Request
     */
    public static function createFromBase(SymfonyRequest $request)
    {
        $uri = $request->getPathInfo();
        if ($uri === "/") {
            $query = $request->query->all();
            $server = $request->server->all();
            $queryString = $request->server->get('QUERY_STRING');
            if ($queryString && $queryString[0] === "/") {
                // This will probably be a MunkiReport style index.php?/path URI
                $server['REQUEST_URI'] = $queryString;
                $server['QUERY_STRING'] = "";
                $query = [];
            }

            $newRequest = (new static)->duplicate(
                $query, $request->request->all(), $request->attributes->all(),
                $request->cookies->all(), $request->files->all(), $server
            );
        } else {
            $newRequest = (new static)->duplicate(
                $request->query->all(), $request->request->all(), $request->attributes->all(),
                $request->cookies->all(), $request->files->all(), $request->server->all()
            );
        }

        $newRequest->headers->replace($request->headers->all());

        $newRequest->content = $request->content;

        $newRequest->request = $newRequest->getInputSource();

        return $newRequest;
    }
}
