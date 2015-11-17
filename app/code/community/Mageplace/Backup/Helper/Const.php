<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */
class Mageplace_Backup_Helper_Const
{
    const NAME = 'mpbackup';

    const CRON_SCHEDULES_RUN_LIFETIME_CYCLE = 360; //One week - 6*60 minutes

    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_SUCCESS = 'success';
    const STATUS_MISSED  = 'missed';
    const STATUS_ERROR   = 'error';

    const BACKUP_PROCESS_REQUEST_PERIOD = 1;
    const BACKUP_PROCESS_RESPONSE_SIZE  = 262144; /* == 256kb */

    const MEMORY_LIMIT_OK    = 'OK';
    const MEMORY_LIMIT_FALSE = 'FALSE';
}