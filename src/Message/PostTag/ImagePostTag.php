<?php
/**
 * @author xp
 */
namespace FeishuBot\Message\PostTag;

class ImagePostTag implements IPostTag
{
    private string $imageKey;
    private int $height;
    private int $width;

    public function setImageKey(string $imageKey) : void {
        $this->imageKey = $imageKey;
    }

    public function setHeight(int $height) : void {
        $this->height = $height;
    }

    public function setWidth(int $width) : void {
        $this->width = $width;
    }

    public static function getTagType() : string {
        return 'img';
    }

    public function render() : \stdClass {
        $content = new \stdClass();
        $content->image_key = $this->imageKey;
        $content->height = $this->height;
        $content->width = $this->width;
        return $content;
    }
}
