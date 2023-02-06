<?php

namespace App\Models\Product;

use App\Models\AbstractModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Review
 * @package App\Models\Product
 *
 * @property int     userId
 * @property int     productId
 * @property string  title
 * @property string  description
 * @property int     rating
 * @property boolean approved
 *
 * @property string  userName
 * @property string  nickname
 * @property string  productName
 * @property User    user
 * @property Product product
 */
class Review extends AbstractModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId',
        'productId',
        'title',
        'description',
        'rating',
        'approved'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'userId'    => 'integer',
        'productId' => 'integer',
        'approved'  => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'productId', 'id');
    }
}