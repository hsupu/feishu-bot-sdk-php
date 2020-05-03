<?php
/**
 * @author xp
 */
namespace FeishuBot\Event;

use FeishuBot\EventHub;
use FeishuBot\Model\EventMetadata;

class ChatEventHandler implements IEventHandler
{
    public function __invoke(EventMetadata $metadata, object $event) : void {
        $metadata->appId = $event->app_id;
        $metadata->tenantKey = $event->tenant_key;
        $eventType = $event->type;
        switch ($eventType) {
            case EventHub::P2P_CREATE:
                //TODO
                return;
            case EventHub::GROUP_ADD_BOT:
                //TODO
                return;
            case EventHub::GROUP_REMOVE_BOT:
                //TODO
                return;
            case EventHub::GROUP_ADD_USER:
                //TODO
                return;
            case EventHub::GROUP_ADD_USER_REVOKED:
                //TODO
                return;
            case EventHub::GROUP_REMOVE_USER:
                //TODO
                return;
            case EventHub::GROUP_DISBAND:
                //TODO
                return;
            case EventHub::GROUP_SETTING_UPDATE:
                //TODO
                return;
        }
    }
}
