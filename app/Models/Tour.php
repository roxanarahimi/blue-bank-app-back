<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'DSD3.Tour';
    protected $hidden = ['Version'];

    use HasFactory;

    public function TourItems()
    {
        return $this->hasMany(TourItem::class, 'TourRef', 'TourID');
    }
    public function Invoices()
    {
        return $this->hasMany(TourInvoice::class, 'TourRef', 'TourID');
    }
    public function TourAssignmentItem()
    {
        return $this->belongsTo(TourAssignmentItem::class, 'TourID','TourRef')->with('Assignment');

    }

}
