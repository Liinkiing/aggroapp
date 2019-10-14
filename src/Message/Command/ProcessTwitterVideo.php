<?php


namespace App\Message\Command;


use App\Message\AsyncMessage;

class ProcessTwitterVideo implements AsyncMessage
{
    private $requestId;

    public function __construct(int $requestId)
    {
        $this->requestId = $requestId;
    }

    public function getRequestId(): int
    {
        return $this->requestId;
    }

}
