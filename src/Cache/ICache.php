<?php
/**
 * @author xp
 */
namespace FeishuBot\Cache;

interface ICache
{
    public function set(string $key, $value, int $expire) : void;

    public function get(string $key);
}
