<?php
/**
 * @author xp
 */
namespace FeishuBot\Event;

use FeishuBot\Model\EventMetadata;

interface IEventHandler
{
    public function __invoke(EventMetadata $metadata, object $event) : void;
}
