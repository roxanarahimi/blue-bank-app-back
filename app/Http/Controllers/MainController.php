<?php

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
use App\Http\Resources\PartyResource2;
use App\Http\Resources\TourResource;
use App\Http\Resources\TourResource2;
use App\Http\Resources\TransporterResource;
use App\Models\Party;
use App\Models\Tour;
use App\Models\TourInvoice;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index(Request $request)
    {
        try {
            if($request['mobile'] && $request['mobile']!=''){
                $party = Party::orderByDESC('PartyID')->where('Mobile', $request['mobile'])
                    ->whereHas('Transporter', function ($q) {
                        $q->whereHas('Assignments');
                    })
                    ->first();

                //if there is an error, check if 2 visitors with same data both have transporters assigned/

                if($party){
                    return response(new PartyResource($party),200);
                }else{
                    $p = Party::orderByDESC('PartyID')->where('Mobile', $request['mobile'])
                        ->whereHas('Transporter')->first();
                    return response(new PartyResource2($p),200);
                }
            }else{
//                $i = Tour::whereIn('TourID',[368690,367682])->get();
//                return TourResource::collection($i);
                $dat = Tour::orderByDESC('TourID')
                    ->where('State', 2)
                    ->whereDate('StartDate', date(today()))
                    ->whereHas('TourAssignmentItem', function ($z) use ($request) {
                        $z->whereHas('Assignment', function ($x) use ($request) {
                            $x->whereHas('Transporter', function ($y) use ($request) {
                                $y->WhereHas('Party');
                            });
                        });
                    })

//                    ->whereHas('invoices', function ($q) use ($request) {
//                        $q->whereHas('order', function ($d) {
//                            $d->whereHas('orderItems');
//                        });
//
//                    })

                    ->where('FiscalYearRef', 1405)
                    ->paginate(100);


                return TourResource2::collection($dat);
            }

            $dat = Tour::orderByDESC('TourID')
                ->where('State', 2)
                ->whereDate('StartDate', date(today()->subDays(2)))
                ->whereHas('TourAssignmentItem', function ($z) use ($request) {
                    $z->whereHas('Assignment', function ($x) use ($request) {
                        $x->whereHas('Transporter', function ($y) use ($request) {
                            $y->WhereHas('Party');
                        });
                    });
                })
                ->whereHas('invoices', function ($q) use ($request) {
                    $q->whereHas('order', function ($d) {
                        $d->whereHas('orderItems');
                    });

                })

                ->where('FiscalYearRef', 1405)
//            ->get();
                ->paginate(100);

            return TourResource::collection($dat);
            $dat = Tour::orderByDESC('TourID')
                ->where('State', 2)
//            ->whereDate('StartDate',date(today()))
                ->whereDate('StartDate', '>=', today()->subDays(1))
                ->whereHas('invoices', function ($q) use ($request) {
                    $q->whereHas('order', function ($d) {
                        $d->whereHas('orderItems');
                    });
                })
                ->where('FiscalYearRef', 1405)
//            ->paginate(100);
                ->take(10)->get();
            return TourResource::collection($dat);


            $dat = Transporter::orderByDESC('TransporterID')->first();
            return new TransporterResource($dat);
            $dat = Tour::orderByDESC('TourID')->whereHas('invoices')->paginate(50);
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

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
