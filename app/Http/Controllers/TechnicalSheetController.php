<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Log;

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
        try {
            $apiUrl = 'http://127.0.0.1:8001/api/technical-sheet' . "/$slug/" . ($token ?? '');
            $response = Http::timeout(5)->get($apiUrl);

            if (!$response->successful()) {
                abort(404, 'Ficha técnica no encontrada');
            }

            $data = $response->json();
            if (!isset($data['property'], $data['features'])) {
                abort(500, 'Respuesta inválida del API');
            }

            return view('technical_sheet', [
                'property' => $data['property'],
                'features' => $data['features'],
                'AWS_URL_S3' => config('app.aws_url_s3')
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener la ficha técnica: ' . $e->getMessage());
            abort(500, 'Error inesperado al obtener la ficha técnica.');
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
