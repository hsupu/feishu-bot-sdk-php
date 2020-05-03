<?php
/**
 * @author xp
 */
namespace FeishuBot\Model;

class EventUser
{
    private string $openId;

    private ?string $employeeId;

    public function __construct(string $openId, ?string $employeeId) {
        $this->openId = $openId;
        $this->employeeId = $employeeId;
    }

    public function getOpenId() : string {
        return $this->openId;
    }

    public function getEmployeeId() : ?string {
        return $this->employeeId;
    }
}
