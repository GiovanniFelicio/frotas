<?php

namespace App\Console\Commands;

use App\Solicitations;
use App\Status;
use App\Tipos;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class updateSolicitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:solicitations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        date_default_timezone_set('America/Bahia');
        $date = new DateTime();
        $datetime = $date->format('Y-m-d H:i');
        $solic = Solicitations::whereIn('status', [Status::AGUARDANDO, Status::AUTORIZADO])->get();
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
        }
        echo "Tabela agenda atualizada com sucesso\n";
    }
}
