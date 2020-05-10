<?php
/**
 * @author xp
 */
namespace FeishuBot;

use FeishuBot\Cache\ICache;
use FeishuBot\Cache\MemoryCache;

class TokenStorage
{
    private ICache $cache;

    public string $appAccessTokenKeyName = 'app_access_token';

    public string $tenantAccessTokenKeyName = 'tenant_access_token';

    public function __construct(ICache $cache = null) {
        if (is_null($cache)) {
            $cache = new MemoryCache();
        }
        $this->cache = $cache;
    }

    public function getAppAccessToken() : string {
        return $this->cache->get($this->appAccessTokenKeyName);
    }

    public function setAppAccessToken(string $value, int $expire) : void {
        $this->cache->set($this->appAccessTokenKeyName, $value, $expire);
    }

    public function getTenantAccessToken() : string {
        return $this->cache->get($this->tenantAccessTokenKeyName);
    }

    public function setTenantAccessToken(string $value, int $expire) : void {
        $this->cache->set($this->tenantAccessTokenKeyName, $value, $expire);
    }
}
