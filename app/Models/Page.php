<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes, HasSeo;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'tenant_id',
        'description',
        'status',

    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function seo()
    {
        return $this->morphOne(SeoPlugin::class, 'seoable');
    }

    protected $hidden = [
        'tenant_id',
        'created_at',
        'updated_at',
        'deleted_at',

    ];
}
