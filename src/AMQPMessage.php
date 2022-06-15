<?php
declare(strict_types=1);
namespace YuanxinHealthy\Amqp;
class AMQPMessage extends \PhpAmqpLib\Message\AMQPMessage
{
    /**
     * 返回消息ID.
     *
     * @return string
     */
    public function getMessageId()
    {
        $propertie = $this->get_properties();
        if (!is_array($propertie) || !isset($propertie['message_id'])) {
            return '';
        }
        return $propertie['message_id'];
    }
}