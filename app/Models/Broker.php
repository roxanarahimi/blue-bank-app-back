<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'SLS3.Broker';
    protected $hidden = ['Version'];

    public function Party()
    {
        return $this->hasOne(Party::class,  'PartyID','PartyRef');
    }
    public function Assignments()
    {
        return $this->hasMany(Assignment::class,  'BrokerRef','BrokerID')
            ->whereHas('TourAssignmentItem',function ($a){
                $a->whereHas('Tour',function ($t){
                    $t->where('State', 2);
                    $t->whereDate('StartDate','>=', date(today()->subDays(2)));
                });
            })
            ->with('TourAssignmentItem',function ($q){
                $q->with('Tour');
            });
    }
}
