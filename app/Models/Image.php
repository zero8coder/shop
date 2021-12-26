<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string path
 * @property string type
 * @property int|mixed user_id
 * @method static find(mixed $avatar_image_id)
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'path'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
