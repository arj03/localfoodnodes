<?php

namespace App\Event;

use App\BaseModel;
use App\User\User;
use App\Event\Event;

class EventUserLink extends BaseModel
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'user_id' => 'required',
        'event_id' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'event_id',
    ];

    /**
     * Get user.
     */
    public function getUser()
    {
        return User::find($this->user_id);
    }

    /**
     * Get event.
     */
    public function getEvent()
    {
        return Event::find($this->event_id);
    }
}
