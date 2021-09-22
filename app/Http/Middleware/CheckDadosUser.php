<?php


namespace App\Http\Middleware;


use App\Secretarias;
use App\Setores;
use Closure;

class CheckDadosUser
{
    public function handle($request, Closure $next)
    {
        if(Secretarias::find(auth()->user()->sec_id) == null){
            \Auth::logout();

            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                ->route('login')
                ->with('message', 'Error de Secretaria/Autarquia, caso persista comunique o setor de Informática da Fundetec');
        }
        return $next($request);
    }
}
