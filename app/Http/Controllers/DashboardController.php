<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Dashboard;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /* 
     * Constructor
     */

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('pages.dashboard.home.index', [
            'title' => 'Dashboard',
            'countUser' => User::count(),
            'countAlternatif' => Alternatif::count(),
            'countKriteria' => Kriteria::count(),
            'countSubkriteria' => Subkriteria::count(),
            'alternatif' => Alternatif::orderBy('id_alternatif', 'DESC')->filter(request(['search']))->paginate(10)->withQueryString(),
            'user' => User::filter(request(['search']))->paginate(10)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }

    /**
     * Display the specified resource.
     */
}
