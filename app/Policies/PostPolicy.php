<?php

namespace App\Policies;

use App\Models\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy extends ModelPolicy
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    public function import(User $user){
        return $user->can('import-' . $this->getModelClass());
    }

    public function export(User $user){
        return $user->can('export-' . $this->getModelClass());
    }
}
