<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HtmlMinifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cek apakah response adalah HTML
        if ($response instanceof Response && $response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            // Minify HTML
            $output = $response->getContent();
            $minifiedOutput = $this->minifyHtml($output);
            $response->setContent($minifiedOutput);
        }

        return $response;
    }

    /**
     * Minify the given HTML string.
     *
     * @param  string  $html
     * @return string
     */
    protected function minifyHtml(string $html): string
    {
        // $search = [
        //     '/\>\s+/s',
        //     '/\s+</s',
        // ];

        // $replace = [
        //     '> ',
        //     ' <',
        // ];

        // return preg_replace($search, $replace, $html);

        // Remove extra white-space and line-breaks between tags
        $html = preg_replace('/>\s+</', '><', $html);

        // Remove white-space and line-breaks before and after tags
        $html = preg_replace('/^\s+|\s+$/m', '', $html);

        // Remove comments except IE conditionals
        $html = preg_replace('/<!--(?!\s*\[if\s).*?-->/', '', $html);

        return $html;
    }
}
