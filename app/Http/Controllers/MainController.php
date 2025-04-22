<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourResource;
use App\Http\Resources\TransporterResource;
use App\Models\Tour;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index(Request $request)
    {
        try {
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
                ->take(10)->get();
            return response(new TourResource($dat), 200);

            $dat = Tour::orderByDESC('TourID')
                ->where('State', 2)
                ->whereHas('invoices', function ($q) use ($request) {
                    $q->whereHas('order', function ($d) {
                        $d->whereHas('orderItems');
                    });
//            $q->with('TourAssignmentItems', function ($z) use ($request) {
//                $z->with('Assignment', function ($x) use ($request) {
//                    $x->with('Transporter', function ($y) use ($request) {
//                        $y->where('TelNumber', $request['mobile']);
//                    });
//                });
//            });
                })
                ->where('FiscalYearRef', 1405)
//            ->get();
                ->paginate(100);
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
