<?php
/**
 * @author xp
 */
namespace FeishuBot\Cache;

class MemoryCache implements ICache
{
    private array $store;

    public function set(string $key, $value, int $expire) : void {
        $expire += time();
        $this->store[$key] = [$value, $expire];
    }

    public function get(string $key) {
        if (!isset($this->store[$key])) {
            return null;
        }
        list($value, $expire) = $this->store[$key];
        if ($expire <= time()) {
            return null;
        }
        return $value;
    }
}
