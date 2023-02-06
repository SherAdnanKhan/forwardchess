<?php

namespace App\Models;

/**
 * Class TaxRate
 * @package App\Models\TaxRate
 *
 * @property string code
 * @property string country
 * @property string rate
 * @property string name
 */
class TaxRate extends AbstractModel
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * @var string
     */
    protected $table = 'tax_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country',
        'rate',
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'country' => 'string'
    ];
}