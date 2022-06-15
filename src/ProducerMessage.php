<?php

declare(strict_types=1);
namespace YuanxinHealthy\Amqp;
use Hyperf\Utils\ApplicationContext;
class ProducerMessage extends \Hyperf\Amqp\Message\ProducerMessage
{
    public function getProperties(): array
    {
        $this->properties['message_id'] = ApplicationContext::getContainer()->get( \Hidehalo\Nanoid\Client::class)->generateId(21, \Hidehalo\Nanoid\Client::MODE_DYNAMIC);
        return parent::getProperties();
    }
}
