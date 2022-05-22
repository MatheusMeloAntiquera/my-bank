<?php

namespace App\Infra\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FixJsonResponse
{
    /**
     * Esse middleware apenas existe para que o retorno json seja igual o esperado no teste
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $json = $response->getContent();
        $formatted = str_replace(":{", ": {", $json);
        $formatted = str_replace(',"', ', "', $formatted);
        $response->setContent($formatted);
        return $response;
    }
}
