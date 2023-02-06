<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Newsletter
 * @package App\Models\Newsletter
 *
 * @property string email
 */
class Newsletter extends AbstractModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'newsletter_emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
    ];
}
