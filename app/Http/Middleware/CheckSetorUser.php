<?php


namespace App\Http\Middleware;

use App\Setores;
use Closure;

class CheckSetorUser
{
    public function handle($request, Closure $next)
    {
        if(Setores::find(auth()->user()->setor_id) == null){
            \Auth::logout();

            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                ->route('login')
                ->with('message', 'Error de Setor/Departamento, caso persista comunique o setor de Informática da Fundetec');
        }

        return $next($request);
    }
}