<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class ApiDebugbar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse &&
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled() &&
            is_object($response->getData())) {
            $response->setData($response->getData(true) + [
                    '_debugbar' => $this->filterData(),
                ]);
        }

        return $response;
    }

    private function filterData()
    {
        $data = app('debugbar')->getData();
        $queries = $data['queries'];
        $statements = [];
        foreach ($queries['statements'] as $statement) {
            $statements[] = [
                'sql' => $statement['sql'],
                'duration' => $statement['duration_str'],
            ];
        }

        return [
            'time' => $data['time']['duration_str'],
            'queries' => [
                'total' => $queries['nb_statements'],
                'duration' => $queries['accumulated_duration_str'],
                'statements' => $statements,
            ],
        ];
    }
}
