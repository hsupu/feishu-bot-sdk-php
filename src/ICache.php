<?php
/**
 * @author xp
 */
namespace FeishuBot;

interface ICache
{
    public const APP_ACCESS_TOKEN_KEY = 'app_access_token';
    public const TENANT_ACCESS_TOKEN_KEY = 'tenant_access_token';

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expire
     * @return void
     */
    public function set($key, $value, $expire);

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);
}
