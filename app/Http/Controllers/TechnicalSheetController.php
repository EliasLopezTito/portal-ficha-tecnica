<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class TechnicalSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('technical_sheet');
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
    public function show(string $slug, ?string $token = null)
    {
        $response = Http::get('http://127.0.0.1:8001/api/technical-sheet/' . $slug . '/' . $token);
        if ($response->successful()) {
            $data = $response->json();
            return view('technical_sheet', ['property' => $data['property'], 'features' => $data['features'], 'AWS_URL_S3' => config('app.aws_url_s3')]);
        } else {
            abort(404, 'Ficha técnica no encontrada');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
