<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            'id'        => 1,
            'sec_id'    => 1,
            'setor_id'  => 1,
            'name'      => 'Giovanni',
            'email'     => 'giovanni.carvalho@fundetec.org.br',
            'password'  => bcrypt('11072001'),
            'level'     => 5,
            'matricula' => 171717,
        );
        DB::table('users')->insert($users);
        $secretarias = array(
            'id' => 1,
            'name' => 'Fundetec',
            'email' => 'fundetec@fundetec.org.br'
        );
        DB::table('secretarias')->insert($secretarias);
        $setor = array(
            'id' => 1,
            'name' => 'Sem Setor',
            'sec_id' => 0,
        );
        DB::table('setores')->insert($setor);
    }
}
