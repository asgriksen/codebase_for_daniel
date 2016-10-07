<?php

namespace App\Repositories;

use App\Contracts\Repositories\NotificationRepository as NotificationRepositoryContract;
use App\Notification;
use App\Support\Repository\Traits\Repositories;

class NotificationRepository implements NotificationRepositoryContract
{
    use Repositories;

    /**
     * @var Notification
     */
    protected $model;

    /**
     * Create new NotificationRepository instance.
     *
     * @param Notification $notification
     */
    public function __construct( Notification $notification )
    {
        $this->model = $notification;
    }
}