<?php

namespace App\Http\Middleware;

use App\Solicitations;
use App\Status;
use App\Tipos;
use App\User;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Closure;

class UpdateSolicitations
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
        date_default_timezone_set('America/Bahia');
        $date = new DateTime();
        $datetime = $date->format('Y-m-d H:i');
        $user = Auth::user();
        $up1 = $this->updateIfpassDateHour($datetime, $user->sec_id);
        $up2 = $this->updateIfStatusTwoUser($date, $user->sec_id);
        

        return $next($request);
    }

    protected function updateIfpassDateHour($datetime, $userSec){
        $solic = Solicitations::where('sec_id', $userSec)->whereIn('status', [Status::AGUARDANDO, Status::AUTORIZADO])->get();
        for($i = 0;$i < count($solic); $i++){
            $horasF = date('Y-m-d H:i',strtotime('+15 minutes',strtotime($solic[$i]->data.''.$solic[$i]->horas)));
            if($solic[$i]->status == Status::AUTORIZADO  and $datetime > $horasF){
                $solic[$i]->status = Status::FINALIZADO;
                $solic[$i]->update();
            }
            if($solic[$i]->tipo == Tipos::AGENDAMENTO and $solic[$i]->status == Status::AGUARDANDO){
                $horasSolicit = ($solic[$i]->data.' '.$solic[$i]->horas);
                if ($horasSolicit <= $horasF){
                    $solic[$i]->status = Status::FINALIZADO;
                    $solic[$i]->update();
                }
            }

            if($solic[$i]->status == Status::AGUARDANDO){
                $horasSolicit = ($solic[$i]->data.' '.$solic[$i]->horas);
                if ($datetime > $horasF){
                    $solic[$i]->status = Status::FINALIZADO;
                    $solic[$i]->update();
                }
            }
        }
    }

    protected function updateIfStatusTwoUser($date, $userSec){
        $funcs = User::where('sec_id', $userSec)->get();
        for($i = 0; $i < count($funcs); $i++){
            if($funcs[$i]->status == 2){
                $solic = Solicitations::where('func_id', $funcs[$i]->id)->where('data', $date->format('Y-m-d'))->where('status', Status::AUTORIZADO)->get();
                for($j = 0; $j<count($solic);$j++){
                    if($solic[$j]->horas < $date->format('H:i')){
                        $solic[$i]->status = Status::FINALIZADO;
                        $solic[$i]->update();
                    }
                }
            }
        }
    }
}
