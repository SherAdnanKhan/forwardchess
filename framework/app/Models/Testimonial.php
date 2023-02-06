<?php

namespace App\Models;

/**
 * Class Testimonial
 * @package App\Models
 *
 * @property string user
 * @property string description
 * @property string video
 * @property bool   active
 */
class Testimonial extends AbstractModel
{
    /**
     * @var string
     */
    protected $table = 'testimonials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'description',
        'video',
        'active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];
}