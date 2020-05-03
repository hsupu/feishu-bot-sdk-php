<?php
/**
 * @author xp
 */
namespace FeishuBot\Message\PostTag;

class LinkPostTag implements IPostTag
{
    private string $text;
    private bool $isEscaped = false;
    private string $link;

    public function setText(string $text) : void {
        $this->text = $text;
    }

    public function setIsEscaped(bool $isEscaped) : void {
        $this->isEscaped = $isEscaped;
    }

    public function setLink(string $link) : void {
        $this->link = $link;
    }

    public static function getTagType() : string {
        return 'a';
    }

    public function render() : \stdClass {
        $content = new \stdClass();
        $content->text = $this->text;
        $content->un_escape = $this->isEscaped;
        $content->href = $this->link;
        return $content;
    }
}
