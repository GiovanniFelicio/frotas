<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserUniqueAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->token_access == null){
            \Auth::logout();

            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                ->route('login')
                ->with('message', 'Sessão reiniciada !!');
        }
        if (auth()->user()->token_access != session()->get('access_token')) {
            // Faz o logout do usuáriofefe
            \Auth::logout();

            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                ->route('login')
                ->with('message', 'A sessão deste usuário está ativa em outro local!');
        }

        return $next($request);
    }
}
