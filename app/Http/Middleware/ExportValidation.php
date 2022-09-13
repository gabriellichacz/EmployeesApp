<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ExportValidation
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
        $validator = Validator::make($request->all(), [
            'checkboxExport' => ['required', 'array'], // Array itself
            'checkboxExport.*' => ['required'], // What the array contains
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        return $next($request);
    }
}
