<?php
/**
 * @author xp
 */
namespace FeishuBot\Model;

class EventMetadata
{
    private string $timestamp;

    private string $uuid;

    private string $type;

    public string $appId;

    public string $tenantKey;

    public function __construct(string $ts, string $uuid, string $type) {
        $this->timestamp = $ts;
        $this->uuid = $uuid;
        $this->type = $type;
    }

    public function getTimestamp() : string {
        return $this->timestamp;
    }

    public function getUuid() : string {
        return $this->uuid;
    }

    public function getType() : string {
        return $this->type;
    }

    public function getAppId() : string {
        return $this->appId;
    }

    public function getTenantKey() : string {
        return $this->tenantKey;
    }
}
