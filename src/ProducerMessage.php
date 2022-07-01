<?php

declare(strict_types=1);
namespace YuanxinHealthy\Amqp;
use Hyperf\Utils\ApplicationContext;
class ProducerMessage extends \Hyperf\Amqp\Message\ProducerMessage
{
    public function getProperties(): array
    {
        $this->properties['message_id'] = ApplicationContext::getContainer()->get(MessageId::class)->generateId();
        return parent::getProperties();
    }

    /**
     * 设置延迟时间
     * @param int $millisecond 延迟多久,毫秒5秒就是5000
     * @param string $name 阿里延时字段delay
     * @return self
     */
    public function setDelayMs(int $millisecond, string $name = 'delay'): self
    {
        $this->properties['application_headers'] = new \PhpAmqpLib\Wire\AMQPTable([$name => $millisecond]);
        return $this;
    }
}
