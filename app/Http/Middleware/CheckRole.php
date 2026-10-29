<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request, allowing it only when the authenticated
     * user holds one of the roles given in the route definition,
     * e.g. ->middleware('role:admin').
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles  Roles accepted for this route.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(Response::HTTP_FORBIDDEN, 'Debe iniciar sesión para acceder a este recurso.');
        }

        if (! in_array($user->role?->value, $roles, true)) {
            abort(
                Response::HTTP_FORBIDDEN,
                'No tiene permisos para acceder a este recurso. Su rol asignado no está autorizado.',
            );
        }

        return $next($request);
    }
}
