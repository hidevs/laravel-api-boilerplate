<?php

namespace App\Models;

use App\Contracts\HasSetting;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use HasFactory, Notifiable, HasRolesAndAbilities, HasSetting, InteractsWithMedia;

    const ROLE_SUPER_ADMIN = 'superadmin';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLES = [
        self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_USER
    ];

    protected $fillable = ['name', 'email', 'mobile', 'password',];
    protected $hidden = ['password', 'remember_token',];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function avatars(): MediaCollection
    {
        return $this->getMedia('avatar');
    }

    public function getAvatarAttribute()
    {
        return $this->avatars()->last();
    }

    /**
     * @param string|UploadedFile $file
     * @return Media|null
     */
    public function addAvatar($file): ?Media
    {
        try {
            return $this->addMedia($file)
                ->toMediaCollection('avatar');
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return null;
        }
    }

    public function registerMediaConversions(Media $media = null): void
    {
        try {
            $this->addMediaConversion('small')
                ->width(self::setting('small_avatar_width'))
                ->height(self::setting('small_avatar_height'))
                ->performOnCollections('avatar');

            $this->addMediaConversion('medium')
                ->width(self::setting('medium_avatar_width'))
                ->height(self::setting('medium_avatar_height'))
                ->performOnCollections('avatar');

            $this->addMediaConversion('large')
                ->width(self::setting('large_avatar_width'))
                ->height(self::setting('large_avatar_height'))
                ->performOnCollections('avatar');
        } catch (InvalidManipulation $e) {}
    }







    public function getJWTIdentifier()
    {
        return $this->mobile;
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getAuthIdentifierName(): string
    {
        return 'mobile';
    }
}
