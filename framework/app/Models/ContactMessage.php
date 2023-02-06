<?php

namespace App\Models;

/**
 * Class ContactMessage
 * @package App\Models\ContactMessage
 *
 * @property string name
 * @property string email
 * @property string subject
 * @property string message
 * @property string userId
 */
class ContactMessage extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'contact_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'userId',
    ];
}