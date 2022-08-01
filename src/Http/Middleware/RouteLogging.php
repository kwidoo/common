<?php

namespace Velia\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Velia\Common\Models\RoutingLog;

class RouteLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (request()->ip() !== '127.0.0.1') {
            $routingLog = RoutingLog::create([
                'method' => request()->getMethod(),
                'path' => request()->getPathInfo(),
                'request' => request()->request->all(),
                'user_id' => request()->user() !== null ? request()->user()->id : null,
                'scope' => request()->user() ? request()->user()->tokenCan('partner') ? 'partner' : (request()->user()->tokenCan('customer') ? 'customer' : 'none') : 'none',
                'ip' => request()->ip(),
                'headers' => [
                    'language' => request()->header('accept-language'),
                    'user-agent' => request()->header('user-agent'),
                    'app-version' => request()->header('app-version')
                ],
            ]);

            $request->merge(['routing_log_id' => $routingLog->id]);
        }
        return $next($request);
    }
}
