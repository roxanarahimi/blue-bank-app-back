<?php

namespace App\Http\Resources;

use App\Http\Controllers\DateController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource2 extends JsonResource
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
            "Broker" => $this->TourAssignmentItem?->Assignment?->Broker,
            "BrokerParty" => $this->TourAssignmentItem?->Assignment?->Broker?->Party,


            "Transporter" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->FullName,
            "TransporterMobile" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->Mobile,

//            "Transporter" => $this->TourAssignmentItem?->Assignment?->Transporter?->FirstName.' '.$this->TourAssignmentItem?->Assignment?->Transporter?->LastName,
//            "PartyID" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->PartyID,
//            "PartyNumber" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->Number,
//            "Party" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->FullName,
//            "Mobile" => $this->TourAssignmentItem?->Assignment?->Transporter?->Party?->Mobile,
            "CreationDate" => (new DateController)->toPersian(date($this->CreationDate)),
            "Invoices" => TourInvoiceResource::collection($this->Invoices),
//            "TourAssignmentItem" => $this->TourAssignmentItem,
        ];
    }
}
