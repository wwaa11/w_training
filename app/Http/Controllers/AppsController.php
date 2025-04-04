<?php

namespace App\Http\Controllers;

use App\Models\Apps;
use App\Http\Requests\StoreAppsRequest;
use App\Http\Requests\UpdateAppsRequest;

class AppsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreAppsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Apps $apps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apps $apps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppsRequest $request, Apps $apps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apps $apps)
    {
        //
    }
}
