<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

final class Ipwall
{
    private const ALLOWED_IPS = [
        '127.0.0.1',
        '127.17.0.1/16',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('app.env') === 'local' || $this->isAllowedIp($request->ip()))
            return $next($request);

        throw new AuthorizationException(sprintf("Acsses denied from %s", $request->ip()));
    }
    /**
     * Undocumented function
     *
     * @param string $ip
     * @return boolean
     */
    public function isAllowedIp(string $ip): bool
    {
        return IpUtils::checkIp($ip, self::ALLOWED_IPS);
    }
}
