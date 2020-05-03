<?php
/**
 * @author xp
 */
namespace FeishuBot\Event;

use FeishuBot\EventHub;
use FeishuBot\Exception\FeishuServerException;
use FeishuBot\Model\EventMetadata;

class AppEventHandler implements IEventHandler
{
    public function __invoke(EventMetadata $metadata, object $event) : void {
        $metadata->appId = $event->app_id;
        $metadata->tenantKey = $event->tenant_key;
        $eventType = $event->type;
        switch ($eventType) {
            case EventHub::APP_OPEN:
                $this->handleAppOpen($metadata, $event);
                return;
            case EventHub::APP_STATUS_CHANGE:
                $this->handleAppStatusChange($metadata, $event);
                return;
        }
    }

    private function handleAppOpen(EventMetadata $metadata, object $event) : void {
        $this->onAppInstalled($metadata);
    }

    private function handleAppStatusChange(EventMetadata $metadata, object $event) : void {
        $status = $event->status;
        switch ($status) {
            case 'start_by_tenant':
                $this->onAppEnabled($metadata);
                return;
            case 'stop_by_tenant':
                $this->onAppDisabled($metadata);
                return;
            case 'stop_by_platform':
                $this->onAppUninstalled($metadata);
                return;
            default:
                throw new FeishuServerException(FeishuServerException::CODE_EVENT_NOT_HANDLED, [
                    'metadata' => $metadata,
                    'event' => $event,
                ]);
        }
    }

    public function onAppInstalled(EventMetadata $metadata) : void {}
    public function onAppUninstalled(EventMetadata $metadata) : void {}
    public function onAppEnabled(EventMetadata $metadata) : void {}
    public function onAppDisabled(EventMetadata $metadata) : void {}
}
