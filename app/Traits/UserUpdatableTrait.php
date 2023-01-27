<?php

namespace App\Traits;

use App\Models\User;

trait UserUpdatableTrait
{
    //Set User status to active
    public function activate()
    {
        $this->status = static::STATUS_ACTIVE;
    }

    //Set User status to inactive
    public function deactivate()
    {
        $this->status = static::STATUS_IN_ACTIVE;
    }

    //Set User status to inactive
    public function ban()
    {
        $this->status = static::STATUS_BANNED;
    }

    public function makeCustomer()
    {
        $this->assignRole(static::TYPE_CUSTOMER);
        $this->type = static::TYPE_CUSTOMER;
    }

    /**
     *
     * Set Current Time as last login
     * @return void
     */
    public function setLastLogin()
    {
        $this->last_login = now();
        $this->save();
    }

    /**
     * @param User $user
     * @return void
     */
    public function activateAndMakeCustomer(): void
    {
        $this->activate();
        $this->makeCustomer();
        $this->save();
    }
}
