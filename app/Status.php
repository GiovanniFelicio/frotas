<?php


namespace App;


class Status
{
    public const DESATIVO = 0;
    public const SAIDAVIAGEM = 1;
    public const CHEGADAVIAGEM = 2;
    public const FINALIZADO = 3;

    public const EMCURSO = 2;

    /**Permissões */

    public const USUARIO = 0;
    public const OPERADOR = 1;
    public const ADMINISTRADOR = 2;
    public const MASTER = 3;

    /** */
    public const VEHINOTFOUND = 100;
    public const SECNOTFOUND = 101;
    public const ERROVALIDACAO = 102;
    public const FUNCNOTFOUND = 103;
    public const SETORNOTFOUND = 104;
    
}
