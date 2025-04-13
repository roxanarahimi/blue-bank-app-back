<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $dat = Transporter::orderByDesc('TransPorterID')->paginate(50);
        $dat2 = Tour::orderByDesc('TourID')->paginate(50);
        return [$dat,$dat2];

        $dat = DB::connection('sqlsrv')->table('LGS3.Transporter')->select("TransporterID")
            ->first();
        $dat = DB::connection('sqlsrv')->table('DSD3.Tour')->select("TourID")
            ->first();
        $dat= DB::connection('sqlsrv')->table('DSD3.Tour')->col();

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
