<?php
/**
 * @author xp
 */
namespace FeishuBot;

use FeishuBot\Event\IEventHandler;
use FeishuBot\Exception\FeishuServerException;

class EventHub
{
    public const APP_OPEN = 'app_open';
    public const APP_STATUS_CHANGE = 'app_status_change';

    public const CONTACT_SCOPE_CHANGE = 'contact_scope_change';

    public const USER_ADD = 'user_add';
    public const USER_UPDATE = 'user_update';
    public const USER_LEAVE = 'user_leave';
    public const USER_STATUS_CHANGE = 'user_status_change';

    public const DEPT_ADD = 'dept_add';
    public const DEPT_UPDATE = 'dept_update';
    public const DEPT_LEAVE = 'dept_leave';

    public const P2P_CREATE = 'p2p_chat_create';

    public const GROUP_ADD_BOT = 'add_bot';
    public const GROUP_REMOVE_BOT = 'remove_bot';
    public const GROUP_ADD_USER = 'add_user_to_chat';
    public const GROUP_ADD_USER_REVOKED = 'revoke_add_user_from_chat';
    public const GROUP_REMOVE_USER = 'remove_user_from_chat';
    public const GROUP_DISBAND = 'chat_disband';
    public const GROUP_SETTING_UPDATE = 'group_setting_update';

    public const MESSAGE = 'message';
    public const MESSAGE_READ = 'message_read';

    public const LEAVE_APPROVAL = 'leave_approvalV2';
    public const WORK_APPROVAL = 'work_approval';
    public const SHIFT_APPROVAL = 'shift_approval';
    public const REMEDY_APPROVAL = 'remedy_approval';
    public const TRIP_APPROVAL = 'trip_approval';

    public const WIDGET_CREATE_INSTANCE = 'create_widget_instance';
    public const WIDGET_DELETE_INSTANCE = 'delete_widget_instance';

    private static ?array $events = null;

    private array $registry = [];

    private static function filter(string $event) : ?string {
        if (is_null(self::$events)) {
            $ref = new \ReflectionClass(self::class);
            self::$events = array_values($ref->getConstants());
        }
        if (in_array($event, self::$events)) {
            return $event;
        }
        return null;
    }

    public function __set(string $event, IEventHandler $handler) : void {
        $this->registry[self::filter($event)] = $handler;
    }

    public function __unset(string $event) : void {
        unset($this->registry[$event]);
    }

    public function __get(string $event) : ?callable {
        return $this->registry[$event];
    }

    public function __call(string $event, array $arguments) : void {
        $func = $this->{$event};
        if (is_null($func)) {
            throw new FeishuServerException(FeishuServerException::CODE_EVENT_NOT_HANDLED, [
                'type' => $event,
                'args' => $arguments,
            ]);
        }
        call_user_func_array($func, $arguments);
    }
}
