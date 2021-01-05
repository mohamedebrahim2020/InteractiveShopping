<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class CancellationReasonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd(App::currentLocale());
        $reason = "reason_desc_" . App::currentLocale();
        return [
            'id' => $this->id,
            'cancel_reason' => $this->$reason,
        ];
    }
}
