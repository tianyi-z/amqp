<?php
declare(strict_types=1);
namespace YuanxinHealthy\Amqp;
use Hyperf\Amqp\Builder\ExchangeBuilder;
use Hyperf\Amqp\Builder\QueueBuilder;
use Hyperf\Amqp\Message\Type;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Contract\StdoutLoggerInterface;
class Consumer extends ConsumerMessage
{
    protected $type = Type::DIRECT;
    protected $queueDurable = true;
    protected $exchangeDurable = true;

    /**
     * @param $data
     * @param AMQPMessage $message
     *
     * @return string
     */
    public function consumeMessage($data, AMQPMessage $message): string
    {
        try {
            return $this->handle($data, $message);
        } catch (\Throwable $throwable) {
            $log = [
                'ip' => env('POD_ID', ''), // 机器IP
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'code' => $throwable->getCode(),
                'exception' => get_class($throwable),
                'queue' => $this->getQueue(), // 队列
                'exchange' => $this->getExchange(), //交换区.
                'message_id' => $message->getMessageId(), // 消息ID.
                'data' => $data, // 消息体.
            ];
            ApplicationContext::getContainer()->get(StdoutLoggerInterface::class)->error(
                $exception->getTraceAsString(),
                $log
            );
            return Result::NACK;
        }
    }

    /**
     * @param $data
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     *
     * @return string
     */
    public function handle($data, AMQPMessage $message): string
    {
        return Result::ACK;
    }

    /**
     * 是否启用消费
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable && env('ENABLE_AMQP_CONSUMER', false);
    }

    /**
     * @return QueueBuilder
     */
    public function getQueueBuilder(): QueueBuilder
    {
        return parent::getQueueBuilder()->setDurable($this->getQueueDurable());
    }

    /**
     * @return ExchangeBuilder
     */
    public function getExchangeBuilder(): ExchangeBuilder
    {
        return parent::getExchangeBuilder()->setDurable($this->getExchangeDurable());
    }

    /**
     * 交换区持久化.
     *
     * @return boolean.
     */
    public function getExchangeDurable()
    {
        return $this->exchangeDurable;
    }

    /**
     * 队列持久化.
     *
     * @return boolean.
     */
    public function getQueueDurable()
    {
        return $this->queueDurable;
    }
}
