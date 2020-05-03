<?php
/**
 * @author xp
 */
namespace FeishuBot\Message\PostTag;

class MentionPostTag implements IPostTag
{
    private string $userId;

    /**
     * @param string $userId open_id or user_id
     */
    public function setUserId(string $userId) : void {
        $this->userId = $userId;
    }

    public static function getTagType() : string {
        return 'at';
    }

    public function render() : \stdClass {
        $content = new \stdClass();
        $content->user_id = $this->userId;
        return $content;
    }
}
