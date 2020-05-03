<?php
/**
 * @author xp
 */
namespace FeishuBot\Message;

class ClientImageMessage implements IClientMessage
{
    private string $key;

    public function setKey(string $key) : void {
        $this->key = $key;
    }

    public static function getMessageType() : string {
        return 'image';
    }

    public function render() : object {
        $content = new \stdClass();
        $content->image_key = $this->$key;
        return $content;
    }
}
