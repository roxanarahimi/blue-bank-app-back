<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourAssignmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return TourResource2
     */
    public function toArray(Request $request): TourResource2
    {
        return new TourResource2($this->TourAssignmentItem->Tour);
    }
}
