<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->EndDate!=='' ? $end = (new DateController)->toPersian(date($this->EndDate)) : $end = '';
        return [
            "TourID" => $this->TourID,
            "Number" => $this->Number,
            "StartDate" => (new DateController)->toPersian($this->StartDate),
            "EndDate" => $this->$end,
            "State" => $this->State,
            "BrokerName" => $this->TourAssignmentItem?->Assignment?->Broker?->Party?->FullName,
            "BrokerMobile" => $this->TourAssignmentItem?->Assignment?->Broker?->Party?->Mobile,
            "BrokerPartyPartyID" => $this->TourAssignmentItem?->Assignment?->Broker?->Party?->PartyID,

            "TransporterPartyID" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->PartyID,
            "Transporter" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->FullName,
            "TransporterMobile" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->Mobile,
            "CreationDate" => (new DateController)->toPersian(date($this->CreationDate)),
            "Invoices" => TourInvoiceResource::collection($this->Invoices),

        ];
    }
}
