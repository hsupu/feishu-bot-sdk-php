<?php
/**
 * @author xp
 */
namespace FeishuBot\Model;

class ClientUser
{
    private ?string $type;
    private ?string $id;

    public function setOpenId(string $openId) : void {
        $this->type = 'open_id';
        $this->id = $openId;
    }

    public function setEmployeeId(string $employeeId) : void {
        $this->type = 'employee_id';
        $this->id = $employeeId;
    }

    public function isSet() : bool {
        return !is_null($this->type);
    }

    public function get() : array {
        return [$this->type, $this->id];
    }
}
