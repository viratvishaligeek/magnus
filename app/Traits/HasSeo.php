<?php

namespace App\Traits;

use App\Models\SeoPlugin;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoPlugin::class, 'seoable');
    }

    public function saveSeo(array $data): SeoPlugin
    {
        $seo = $this->seo()->firstOrNew();

        if (isset($data['schema'])) {
            $data['schema'] = is_array($data['schema']) ? $data['schema'] : json_decode($data['schema'], true);
        }

        $seo->fill($data);
        $this->seo()->save($seo);

        return $seo;
    }
}
