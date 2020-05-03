<?php
/**
 * @author xp
 */
namespace FeishuBot\Event;

use FeishuBot\EventHub;
use FeishuBot\Model\EventMetadata;
use FeishuBot\Model\EventUser;

class UserEventHandler implements IEventHandler
{
    public function __invoke(EventMetadata $metadata, object $event) : void {
        $metadata->appId = $event->app_id;
        $metadata->tenantKey = $event->tenant_key;
        $user = new EventUser($event->open_id, $event->employee_id);
        $eventType = $event->type;
        switch ($eventType) {
            case EventHub::USER_ADD:
                $this->handleUserAdd($metadata, $user, $event);
                return;
            case EventHub::USER_UPDATE:
                $this->handleUserUpdate($metadata, $user, $event);
                return;
            case EventHub::USER_LEAVE:
                $this->handleUserLeave($metadata, $user, $event);
                return;
            case EventHub::USER_STATUS_CHANGE:
                $this->handleUserStatusChange($metadata, $user, $event);
                return;
        }
    }

    private function handleUserAdd(EventMetadata $metadata, EventUser $user, object $event) {
        $this->onUserAdd($metadata, $user);
    }

    private function handleUserUpdate(EventMetadata $metadata, EventUser $user, object $event) {
        $this->onUserUpdate($metadata, $user);
    }

    private function handleUserLeave(EventMetadata $metadata, EventUser $user, object $event) {
        $this->onUserRemove($metadata, $user);
    }

    private function handleUserStatusChange(EventMetadata $metadata, EventUser $user, object $event) {
        $change = new \stdClass();
        $change->old = $event->before_status;
        $change->new = $event->current_status;
        $change->time = $event->change_time;
        $this->onUserStatusChange($metadata, $user, $change);
    }

    public function onUserAdd(EventMetadata $metadata, EventUser $user) : void {}
    public function onUserUpdate(EventMetadata $metadata, EventUser $user) : void {}
    public function onUserRemove(EventMetadata $metadata, EventUser $user) : void {}
    public function onUserStatusChange(EventMetadata $metadata, EventUser $user, object $change) : void {}
}
