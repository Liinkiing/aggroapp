<?php


namespace App\Model;


interface ApiModel
{
    public static function fromApi(array $response);
}
