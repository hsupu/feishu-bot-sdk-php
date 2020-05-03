<?php
/**
 * @author xp
 */
namespace FeishuBot\Message\PostTag;

class TextPostTag implements IPostTag
{
    private string $text;
    private bool $isEscaped = false;
    private ?int $showLines;

    public function setText(string $text) : void {
        $this->text = $text;
    }

    public function setIsEscaped(bool $isEscaped) : void {
        $this->isEscaped = $isEscaped;
    }

    public function setShowLines(?int $showLines) : void {
        $this->showLines = $showLines;
    }

    public static function getTagType() : string {
        return 'text';
    }

    public function render() : \stdClass {
        $content = new \stdClass();
        $content->text = $this->text;
        $content->un_escape = $this->isEscaped;
        $content->lines = $this->showLines;
        return $content;
    }
}
