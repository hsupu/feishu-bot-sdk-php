<?php
/**
 * @author xp
 */
namespace FeishuBot\Exception;

class FeishuServerException extends \RuntimeException
{
    public const CODE_TOKEN_MISMATCH = 403;
    public const CODE_BAD_REQUEST = 400;
    public const CODE_EVENT_NOT_HANDLED = 501;

    private $extra;

    public function __construct(int $code, $extra = null, \Throwable $previous = null) {
        $this->extra = $extra;
        parent::__construct('', $code, $previous);
    }
}
