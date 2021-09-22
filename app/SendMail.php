<?php


namespace App;


use Mail;

class SendMail
{
    public static function send($to_name, $emails, $conteudo, $assunto){
        foreach($emails as $email){
            $emailF[] = $email;
        }
        Mail::send('mail.send', ['conteudo' => $conteudo], function($message) use ($emailF, $to_name, $conteudo, $assunto)
        {    
            $message->to($emailF, $to_name)->subject($assunto);    
            $message->from('giovanni.carvalho@fundetec.org.br', 'FrotasVel');
        });
        return true;
    }
    public static function sendcontact($to_name, $email, $conteudo, $assunto){
        Mail::send('mail.send', ['conteudo' => $conteudo], function($message) use ($email, $to_name, $conteudo, $assunto)
        {    
            $message->to($email, $to_name)->subject($assunto);    
            $message->from('giovanni.carvalho@fundetec.org.br', 'FrotasVel');
        });
        return true;
    }
}
