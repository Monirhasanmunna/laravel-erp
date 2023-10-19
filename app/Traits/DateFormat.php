<?php


namespace App\Traits;

trait DateFormat
{
    //"01 Jul 2023" 
    public function dateFormat1($date)
    {
        return date("d M Y",strtotime($date));
    }
}
