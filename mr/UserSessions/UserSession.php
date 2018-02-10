<?php
namespace Mr\UserSessions;

use Mr\Core\SerialNumberModel;

class UserSession extends SerialNumberModel
{
    protected $table = 'user_sessions';
    public $timestamps = false;
}
