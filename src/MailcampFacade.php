<?php

namespace Voicecode\Mailcamp;

use Illuminate\Support\Facades\Facade;

class MailcampFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'mailcamp';
    }
}