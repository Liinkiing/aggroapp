<?php


namespace App\MessageHandler\Command;


use App\Message\Command\ProcessTwitterVideo;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ProcessTwitterVideoHandler implements MessageHandlerInterface
{

    public function __invoke(ProcessTwitterVideo $message)
    {
    }

}
