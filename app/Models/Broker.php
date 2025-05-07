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
}
