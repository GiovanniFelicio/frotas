<?php

namespace App\Http\Controllers;

use App\Secretarias;
use App\Setores;
use App\Status;
use App\User;
use App\UsersActions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UsersActionsController extends Controller
{
    public function show(){
        $user = Auth::user();
        if($user->level == Status::MASTER){
            return view('logs.logs');
        }
        else{
            return abort(403, 'Você não tem permissao suficente');
        }
    }
    public function getdata(){
        $user = Auth::user();
        if($user->level == Status::MASTER){
            $actions = UsersActions::latest()->get();
            for ($i = 0; $i< count($actions); $i++){
                if (Setores::find($actions[$i]->setor_id) == null){
                    $actions[$i]->setor = 'Sem Setor';
                }
                else{
                    $actions[$i]->setor = Setores::find($actions[$i]->setor_id)->name;
                }
                $actions[$i]->secretaria = Secretarias::find($actions[$i]->sec_id)->name;
                $actions[$i]->func = User::find($actions[$i]->func)->name;
                $actions[$i]->data = Carbon::parse($actions[$i]->created_at)->format('d/m/Y').' às '.Carbon::parse($actions[$i]->created_at)->format('H:i');
            }
            return DataTables::of($actions)->make(true);
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
    }
}
