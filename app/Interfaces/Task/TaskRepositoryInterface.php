<?php

namespace App\Interfaces\Task;

use App\Interfaces\CrudRepositoryInterface;

interface TaskRepositoryInterface extends CrudRepositoryInterface
{
    public function getListTask($data);
    public function getTotalTasks($userId);
    public function getListUserReminderMail();
}
