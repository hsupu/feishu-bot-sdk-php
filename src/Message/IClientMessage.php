<?php
/**
 * @author xp
 */
namespace FeishuBot\Message;

interface IClientMessage
{
    public static function getMessageType() : string;
    public function render() : object;
}
