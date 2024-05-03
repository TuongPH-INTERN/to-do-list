<?php

namespace App\Interfaces\Task;

use App\Interfaces\CrudRepositoryInterface;

interface TaskRepositoryInterface extends CrudRepositoryInterface
{
    public function getListTask($data);
}
