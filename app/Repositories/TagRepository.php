<?php

namespace App\Repositories;

use App\Models\Rate;

class TagRepository //tags repository
{
    public function getRateTags(Rate $rate)
    {
        $tags = $rate->tagsCategory->tags;
        return $tags;
    }
}
