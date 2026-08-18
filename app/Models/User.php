<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable(['name', 'email', 'password', 'phone_number', 'avatar_path', 'kyc_status', 'is_suspended', 'id_card_photo', 'trust_score', 'is_admin', 'balance'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('kyc_documents')
             ->singleFile();

        $this->addMediaCollection('avatar')
             ->singleFile();
    }

    public function getAvatarUrl(): string
    {
        if ($this->hasMedia('avatar')) {
            $url = $this->getFirstMediaUrl('avatar');
            if ($url) {
                $parsed = parse_url($url);
                return (isset($parsed['path']) && str_contains($parsed['path'], 'storage/')) ? $parsed['path'] : $url;
            }
        }

        if ($this->avatar_path) {
            return (str_starts_with($this->avatar_path, 'http') || str_starts_with($this->avatar_path, '/'))
                ? $this->avatar_path
                : asset('storage/' . $this->avatar_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=FFFFFF&background=EF4444&bold=true';
    }

    public function getKycDocumentUrl(): ?string
    {
        if ($this->hasMedia('kyc_documents')) {
            $url = $this->getFirstMediaUrl('kyc_documents');
            if ($url) {
                $parsed = parse_url($url);
                return (isset($parsed['path']) && str_contains($parsed['path'], 'storage/')) ? $parsed['path'] : $url;
            }
        }

        if ($this->id_card_photo) {
            $parsed = parse_url($this->id_card_photo);
            if (isset($parsed['path']) && str_contains($parsed['path'], 'storage/')) {
                return $parsed['path'];
            }
            return $this->id_card_photo;
        }

        return null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trust_score' => 'integer',
            'is_admin' => 'boolean',
            'is_suspended' => 'boolean',
            'balance' => 'decimal:2',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasManyThrough(Order::class, Product::class, 'vendor_id', 'product_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class, 'buyer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'buyer_id');
    }
}
