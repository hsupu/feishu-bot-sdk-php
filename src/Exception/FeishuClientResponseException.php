<?php
/**
 * @author xp
 */
namespace FeishuBot\Exception;

class FeishuClientResponseException extends \RuntimeException
{
    public function __construct(int $code = 0, string $message = "", \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
