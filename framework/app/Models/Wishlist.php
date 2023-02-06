<?php

namespace App\Models;

/**
 * Class Wishlist
 * @package App\Models
 *
 * @property int userId
 * @property int productId
 */
class Wishlist extends AbstractModel
{
    protected $table = 'wishlist';

    protected $fillable = [
        'userId',
        'productId'
    ];
}
