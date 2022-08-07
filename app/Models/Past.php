<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Past
 *
 * @property string $id
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Past newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Past newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Past query()
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 */
class Past extends Model implements HasMedia
{
    use Uuid, HasFactory, InteractsWithMedia;

    public $incrementing = false;
    protected $keyType = 'uuid';


    protected $fillable = ['content'];

    protected $appends = ['files'];

    public function files(): Attribute
    {
        return Attribute::get(function () {
            $files = [];
            $mediaFiles = $this->getMedia('files');

            foreach ($mediaFiles as /* @var $mediaFile Media */ $mediaFile) {
                $files[] = [
                    'id' => $mediaFile->id,
                    'url' => $mediaFile->getTemporaryUrl($this->created_at->addDay()),
                    'type' => $mediaFile->type,
                    'name' => $mediaFile->name,
                    'size' => $mediaFile->size,
                ];
            }

            return $files;
        });
    }
}
