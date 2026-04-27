<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    // El handle recibe la petición, el siguiente middleware y el nombre del rol a comprobar
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario está autenticado pero no tiene el rol, devolvemos 403
        if (! $request->user()->hasRole($role)) {
            abort(403, 'Acceso no autorizado para este rol.');
        }

        return $next($request);
    }
}
