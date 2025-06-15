<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Berita extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beritas';

    // Status yang tersedia
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'gambar',
        'status',
        'kategori_id',
        'user_id',
        'published_at',
        'rejection_reason'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['excerpt', 'status_label', 'formatted_date'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            $berita->slug = Str::slug($berita->judul);
            if ($berita->status === self::STATUS_APPROVED && !$berita->published_at) {
                $berita->published_at = now();
            }
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('judul')) {
                $berita->slug = Str::slug($berita->judul);
            }
            if ($berita->isDirty('status') && $berita->status === self::STATUS_APPROVED && !$berita->published_at) {
                $berita->published_at = now();
            }
        });
    }

    /**
     * Get the kategori that owns the berita.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Get the user that owns the berita.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the excerpt attribute.
     *
     * @return string
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->isi), 160);
    }

    /**
     * Get the status label attribute.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];

        return $statuses[$this->status] ?? $this->status;
    }
    
    /**
     * Scope a query to only include pending news.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope a query to only include approved news.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    
    /**
     * Scope a query to only include rejected news.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Get the formatted date attribute.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    /**
     * Scope a query to only include published beritas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_APPROVED)
                    ->where('published_at', '<=', now());
    }

    /**
     * Check if the berita is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === self::STATUS_APPROVED && 
               $this->published_at && 
               $this->published_at->lte(now());
    }

    /**
     * Check if the berita is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the berita is rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}