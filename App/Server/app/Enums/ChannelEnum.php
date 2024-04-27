<?php

namespace App\Enums;

class ChannelEnum extends BaseEnum
{
    public const PATH_BROADCAST = 'https://api.line.me/v2/bot/message/broadcast';

    public const PATH_MULTICAST = 'https://api.line.me/v2/bot/message/multicast';
}
