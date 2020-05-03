<?php
/**
 * @author xp
 */
namespace FeishuBot\Model;

class ClientMessageReceiver
{
    private ?string $type;
    private ?string $id;

    public function setChatId(string $chatId) : void {
        $this->type = 'chat_id';
        $this->id = $chatId;
    }

    public function setOpenId(string $openId) : void {
        $this->type = 'open_id';
        $this->id = $openId;
    }

    public function setUserId(string $userId) : void {
        $this->type = 'user_id';
        $this->id = $userId;
    }

    public function setEmployeeId(string $employeeId) : void {
        $this->setUserId($employeeId);
    }

    public function isSet() : bool {
        return !is_null($this->type);
    }

    public function get() : array {
        return [$this->type, $this->id];
    }
}
