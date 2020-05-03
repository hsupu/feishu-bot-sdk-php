<?php
/**
 * @author xp
 */
namespace FeishuBot\Message\PostTag;

interface IPostTag
{
    public static function getTagType() : string;
    public function render() : \stdClass;
}
