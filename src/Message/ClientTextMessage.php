<?php
/**
 * @author xp
 */
namespace FeishuBot\Message;

class ClientTextMessage implements IClientMessage
{
    private string $text;

    public function setText(string $text) : void {
        $this->text = $text;
    }

    public static function getMessageType() : string {
        return 'text';
    }

    public function render() : object {
        $content = new \stdClass();
        $content->text = $this->text;
        return $content;
    }
}
