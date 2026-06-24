<?php

namespace App\Http\Middleware;

use App\Models\UserActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            $user = auth()->user();

            // Update last_login fields on user (throttled via cache)
            $cacheKey = 'user_activity_' . $user->id;
            if (!\Cache::has($cacheKey)) {
                \Cache::put($cacheKey, true, 60); // Update at most every 60 seconds

                $user->timestamps = false;
                $user->last_login_at = now();
                $user->save();
                $user->timestamps = true;

                // Upsert session tracking
                UserActivityLog::updateOrCreate(
                    ['session_id' => session()->getId()],
                    [
                        'user_id'        => $user->id,
                        'ip_address'     => $request->ip(),
                        'user_agent'     => $request->userAgent(),
                        'device_type'    => $this->detectDevice($request->userAgent()),
                        'browser'        => $this->detectBrowser($request->userAgent()),
                        'os'             => $this->detectOs($request->userAgent()),
                        'last_active_at' => now(),
                        'is_active'      => true,
                    ]
                );
            }
        }

        return $response;
    }

    private function detectDevice(?string $ua): string
    {
        if (!$ua) return 'unknown';
        if (preg_match('/Mobile|Android|iPhone|iPad/i', $ua)) return 'mobile';
        if (preg_match('/Tablet|iPad/i', $ua)) return 'tablet';
        return 'desktop';
    }

    private function detectBrowser(?string $ua): string
    {
        if (!$ua) return 'unknown';
        if (str_contains($ua, 'Chrome'))  return 'Chrome';
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Safari'))  return 'Safari';
        if (str_contains($ua, 'Edge'))    return 'Edge';
        if (str_contains($ua, 'Opera'))   return 'Opera';
        return 'Unknown';
    }

    private function detectOs(?string $ua): string
    {
        if (!$ua) return 'unknown';
        if (str_contains($ua, 'Windows'))     return 'Windows';
        if (str_contains($ua, 'Mac OS'))      return 'macOS';
        if (str_contains($ua, 'Linux'))       return 'Linux';
        if (str_contains($ua, 'Android'))     return 'Android';
        if (str_contains($ua, 'iPhone'))      return 'iOS';
        return 'Unknown';
    }
}
