<?php

namespace App\Repositories;

use App\Models\Rate;

class ReviewOrderRepository
{
    public function getRateTags (Rate $rate)
    {
       $tags = $rate->tagsCategory->tags;
       return $tags;
    }

}