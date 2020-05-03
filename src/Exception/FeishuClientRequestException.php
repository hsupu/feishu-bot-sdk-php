<?php
/**
 * @author xp
 */
namespace FeishuBot\Exception;

class FeishuClientRequestException extends \RuntimeException
{
    public const CODE_BAD_REQUEST = 400;

    private $extra;

    public function __construct(int $code, $extra = null, \Throwable $previous = null) {
        $this->extra = $extra;
        parent::__construct('', $code, $previous);
    }
}
