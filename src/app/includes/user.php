<?php

const UID_GUEST         = 1;
const NICK_GUEST        = 'Guest';

const GROUP_ROOT        = 0x0;
const GROUP_GUEST       = 0x1;
const GROUP_ADMIN       = 0x2;
const GROUP_USER        = 0x3;
const GROUP_JUDGER      = 0xFF;
const GROUP_INTERNAL    = 0x100;

global $_GROUPS, $_GROUPS_STR;

$_GROUPS = array
(
    GROUP_ROOT      => 'Root',
    GROUP_GUEST     => 'Guest',
    GROUP_ADMIN     => 'Admin',
    GROUP_USER      => 'User',
    GROUP_JUDGER    => 'Judger',
    GROUP_INTERNAL  => 'Internal'
);

$_GROUPS_STR = array
(
    GROUP_ROOT      => 'Root',
    GROUP_GUEST     => 'Guest',
    GROUP_ADMIN     => 'Administrator',
    GROUP_USER      => 'User',
    GROUP_JUDGER    => 'Judger',
    GROUP_INTERNAL  => 'Internal'
);