<?php
/**
 * @author xp
 */
namespace FeishuBot\Message;

use FeishuBot\Message\PostTag\IPostTag;

class ClientPostMessage implements IClientMessage
{
    private string $title;
    private array $items;

    public function setKey(string $key) : void {
        $this->key = $key;
    }

    public function append(IPostTag $tag) : void {
        $items[] = $tag;
    }

    public static function getMessageType() : string {
        return 'post';
    }

    public function render() : object {
        $contents = [];
        foreach ($this->items as $item) {
            /**
             * @var $item IPostTag
             */
            $content = $item->render();
            $content->tag = $item::getTagType();
            $contents[] = $content;
        }

        $post = new \stdClass();
        $post->title = $this->title;
        $post->content = $contents;

        $i18n = new \stdClass();
        $i18n->en_us = $post;

        $wrapper = new \stdClass();
        $wrapper->post = $i18n;
        return $wrapper;
    }
}
