<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogPortalActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $user = $request->user();

        if (!$user || !in_array($user->role, ['admin', 'qr_client'], true) || !in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $response;
        }

        $route = $request->route();
        $parameters = collect($route?->parameters() ?? [])
            ->map(fn ($value) => is_object($value) && method_exists($value, 'getKey') ? $value->getKey() : $value)
            ->filter(fn ($value) => is_scalar($value) || $value === null)
            ->all();
        $firstSubject = collect($parameters)->first(fn ($value, $key) => !in_array($key, ['token'], true));
        $subjectName = collect($parameters)->keys()->first(fn ($key) => $key !== 'token');
        $safeInput = $this->sanitizeInput($request->all());

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $route?->getName() ?: $request->method() . ' ' . $request->path(),
            'route_name' => $route?->getName(),
            'method' => $request->method(),
            'url' => $request->path(),
            'subject_type' => $subjectName,
            'subject_id' => is_scalar($firstSubject) ? (string) $firstSubject : null,
            'details' => ['route_parameters' => $parameters, 'input' => $safeInput],
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
            'response_status' => $response->getStatusCode(),
        ]);

        return $response;
    }

    private function sanitizeInput(array $input): array
    {
        $safe = [];
        foreach ($input as $key => $value) {
            if (preg_match('/password|passwd|pin|token|secret|proof|credential|_method|_token/i', (string) $key)) {
                continue;
            }

            if (is_array($value)) {
                $safe[$key] = $this->sanitizeInput($value);
            } elseif (is_string($value)) {
                $safe[$key] = mb_substr($value, 0, 1000);
            } elseif (is_int($value) || is_float($value) || is_bool($value) || $value === null) {
                $safe[$key] = $value;
            }
        }

        return $safe;
    }
}
