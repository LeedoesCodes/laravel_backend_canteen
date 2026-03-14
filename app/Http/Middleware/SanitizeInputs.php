<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SanitizeInputs middleware
 *
 * Strips HTML/XSS from all string inputs before they reach controllers.
 * SQL injection is already prevented by Laravel's parameterized queries.
 */
class SanitizeInputs
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Strip all HTML tags to prevent XSS
                $value = strip_tags($value);
                // Convert special HTML characters
                $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
                // Trim whitespace
                $value = trim($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
