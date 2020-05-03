<?php
/**
 * @author xp
 */
namespace FeishuBot\Message;

class ClientShareChatMessage implements IClientMessage
{
    private string $chatId;

    public function setText(string $chatId) : void {
        $this->chatId = $chatId;
    }

    public static function getMessageType() : string {
        return 'share_chat';
    }

    public function render() : object {
        $content = new \stdClass();
        $content->share_open_chat_id = $this->chatId;
        return $content;
    }
}
