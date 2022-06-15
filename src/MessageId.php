<?php
declare(strict_types=1);
namespace YuanxinHealthy\Amqp;

class MessageId
{
    protected $idClient;
    public function __construct()
    {
        $this->idClient = new \Hidehalo\Nanoid\Client();
    }
    public function generateId()
    {
        return $this->idClient->generateId(21, \Hidehalo\Nanoid\Client::MODE_DYNAMIC);
    }
}