<?php

namespace App\Http\Controllers;


use App\Http\Resources\PartyResource;
use App\Http\Resources\TourResource;
use App\Models\Party;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function tours(Request $request)
    {
        try {

            if ($request['mobile'] && $request['mobile'] != '') {
                $party = Party::orderByDESC('PartyID')
                    ->where('Mobile', $request['mobile'])
                    ->whereHas('Broker')
                    ->with('Broker')
                    ->first();

                if ($party) {
                    if ($party->Broker->State == 2){
                        return response(new PartyResource($party), 200);
                    }elseif ($party->Broker->State == 1){
                        return response(['message'=>'این کاربر غیر فعال است'], 403);
                    }
                } else {
                    return response(['message'=>'کاربری با این شماره وجود ندارد'], 404);
                }
            } else {
                $dat = Tour::orderByDESC('TourID')
                    ->where('State', 2)
//                    ->whereDate('StartDate',date(today()))
                    ->whereDate('StartDate','>=', date(today()->subDays(2)))
                    ->whereHas('TourAssignmentItem', function ($z) use ($request) {
                        $z->whereHas('Assignment', function ($a) use ($request) {
                            $a->whereHas('Broker', function ($p) use ($request) {
                                $p->WhereHas('Party', function ($pm) use ($request) {
                                    if (isset($request['mobile'])) {
                                        $pm->where('Mobile', $request['mobile']);
                                    }
                                });
                            });
                            $a->whereHas('Transporter', function ($t) use ($request) {
                                $t->WhereHas('Party');
                            });

                        });
                    })
                    ->whereHas('Invoices', function ($q) use ($request) {
                        $q->whereHas('Order', function ($d) {
                            $d->whereHas('OrderItems');
                        });
                    })
                    ->where('FiscalYearRef', 1405)
                    ->take(100)->get();
                return TourResource::collection($dat);

            }


        } catch (\Exception $exception) {
            return response($exception);

        }
    }

    public function test(Request $request)
    {
        try {
            $dat = Tour::orderByDESC('TourID')
                ->where('State', 2)
//                    ->whereDate('StartDate',date(today()))
                ->whereDate('StartDate','>=', date(today()->subDays(2)))
                ->whereHas('TourAssignmentItem', function ($z) use ($request) {
                    $z->whereHas('Assignment', function ($a) use ($request) {
                        $a->whereHas('Broker', function ($p) use ($request) {
                            $p->WhereHas('Party', function ($pm) use ($request) {
                                if (isset($request['mobile'])) {
                                    $pm->where('Mobile', $request['mobile']);
                                }
                            });
                        });
                        $a->whereHas('Transporter', function ($t) use ($request) {
                            $t->WhereHas('Party');
                        });

                    });
                })
                ->whereHas('Invoices', function ($q) use ($request) {
                    $q->whereHas('Order', function ($d) {
                        $d->whereHas('OrderItems');
                    });
                })
                ->where('FiscalYearRef', 1405)
                ->take(100)->get();

            return TourResource::collection($dat);

            $dat = DB::connection('sqlsrv')->table('LGS3.Transporter')->select("TransporterID")
                ->first();
            $dat = DB::connection('sqlsrv')->table('DSD3.Tour')->select("TourID")
                ->first();
            return $dat;
        } catch (\Exception $exception) {
            return response($exception);

        }
    }

}
