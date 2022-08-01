<?php

namespace Velia\Common\Services;

use Velia\Common\Concerns\Service as ConcernsService;
use Velia\Common\Models\User;

abstract class Service implements ConcernsService
{
    protected $endpoint;

    public function __construct(protected User $user, protected $model)
    {
        $this->endpoint = config('velia.auth.user');
    }
}
