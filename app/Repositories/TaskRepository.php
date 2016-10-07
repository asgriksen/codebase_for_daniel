<?php

namespace App\Repositories;

use App\Contracts\Repositories\TaskRepository as TaskRepositoryContract;
use App\Support\Repository\Traits\Repositories;
use App\Task;

class TaskRepository implements TaskRepositoryContract
{
    use Repositories;

    /**
     * @var Task
     */
    protected $model;

    /**
     * Create new TaskRepository instance.
     *
     * @param Task $task
     */
    public function __construct( Task $task )
    {
        $this->model = $task;
    }
}