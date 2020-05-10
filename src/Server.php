<?php
/**
 * @author xp
 */
namespace FeishuBot;

use FeishuBot\Exception\FeishuServerException;
use FeishuBot\Model\EventMetadata;

class Server
{
    private Config $config;

    private Encryptor $encryptor;

    private EventHub $eventHub;

    public function __construct(Config $config) {
        $this->config = $config;
        if (!is_null($config->encryptKey)) {
            $this->encryptor = new Encryptor($config->encryptKey);
        }
        $this->eventHub = new EventHub();
    }

    public function getEventHub() : EventHub {
        return $this->eventHub;
    }

    public function onEvent(string $body) {
        /**
         * @var $json object
         */
        $json = json_decode($body, false, 512, JSON_UNESCAPED_UNICODE);
        if (!is_object($json)) {
            throw new FeishuServerException(FeishuServerException::CODE_BAD_REQUEST, $json);
        }
        if (!is_null($this->encryptor)) {
            $json = $this->encryptor->decryptString($json->encrypt);
        }

        $verifyToken = $json->token;
        if ($verifyToken != $this->config->verifyToken) {
            throw new FeishuServerException(FeishuServerException::CODE_TOKEN_MISMATCH, $verifyToken);
        }

        $requestType = $json->type;
        switch ($requestType) {
            case 'url_verification':
                return $this->handleUrlVerification($json);
            case 'event_callback':
                $this->handleEventCallback($json);
                return null;
            case null:
                throw new FeishuServerException(FeishuServerException::CODE_BAD_REQUEST, $json);
            default:
                throw new FeishuServerException(FeishuServerException::CODE_EVENT_NOT_HANDLED, $json);
        }
    }

    public function handleUrlVerification(object $json) : object {
        $response = new \stdClass();
        $response->challenge = $json->challenge;
        return $response;
    }

    public function handleEventCallback(object $json) : void {
        $event = $json->event;
        $eventType = $event->type;
        $metadata = new EventMetadata($json->ts, $json->uuid, $eventType);
        $this->eventHub->{$eventType}($metadata, $event);
    }
}
