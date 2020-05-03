<?php
/**
 * @author xp
 */
namespace FeishuBot\Event;

use FeishuBot\Model\EventMetadata;

class MessageEventHandler implements IEventHandler
{
    public function __invoke(EventMetadata $metadata, object $event) : void {
        $metadata->appId = $event->app_id;
        $metadata->tenantKey = $event->tenant_key;

        $chat = new \stdClass();
        $chat->chatId = $event->open_chat_id;
        $chat->chatType = $event->chat_type;

        $message = new \stdClass();
        $message->messageId = $event->open_message_id;
        $message->rootId = $event->root_id;
        $message->parentId = $event->parent_id;
        $message->userOpenId = $event->open_id;
        $message->chat = $chat;
        $message->type = $event->msg_type;
        $message->isMentioned = $event->is_mention;

        switch ($message->type) {
            case 'text':
                $this->handleTextMessage($metadata, $message, $event);
                return;
            case 'image':
                $this->handleImageMessage($metadata, $message, $event);
                return;
            case 'post':
                $this->handlePostMessage($metadata, $message, $event);
                return;
            case 'file':
                $this->handleFileMessage($metadata, $message, $event);
                return;
            case 'merge_forward':
                $this->handleMergeForwardMessage($metadata, $message, $event);
                return;
        }
    }

    private function handleTextMessage(EventMetadata $metadata, object $message, object $event) {
        $this->onMessage($metadata, $message, [
            'text' => $event->text_without_at_bot,
        ]);
    }

    private function handleImageMessage(EventMetadata $metadata, object $message, object $event) {
        $this->onMessage($metadata, $message, [
            'images' => [$event->image_key],
        ]);
    }

    private function handlePostMessage(EventMetadata $metadata, object $message, object $event) {
        $this->onMessage($metadata, $message, [
            'text' => $event->text_without_at_bot,
            'images' => $event->image_keys,
        ]);
    }

    private function handleFileMessage(EventMetadata $metadata, object $message, object $event) {
        $this->onMessage($metadata, $message, [
            'file' => $event->file_key,
        ]);
    }

    private function handleMergeForwardMessage(EventMetadata $metadata, object $message, object $event) {
        //TODO
    }

    public function onMessage(EventMetadata $metadata, object $message, array $contents) {}
}
