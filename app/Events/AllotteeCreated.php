<?php

namespace App\Events;

use App\Models\User;
use App\Models\Allottee;
use App\Models\Application;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AllotteeCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $allottee;
    public $application;
    public $plainPassword;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Allottee $allottee, ?Application $application, string $plainPassword)
    {
        $this->user = $user;
        $this->allottee = $allottee;
        $this->application = $application;
        $this->plainPassword = $plainPassword;
    }
}
