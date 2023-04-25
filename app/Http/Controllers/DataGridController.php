<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataGridController extends Controller
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
    public function store(Request $request)
    {
        $date_on = empty($request->input('date_on')) ? '2023-01-01' :$request->input('date_on');
        $date_off = empty($request->input('date_off')) ? '2023-04-20' :$request->input('date_off');


        $date_on = "'" . $date_on . "'";
        $date_off = "'" . $date_off . "'";

        $lead = new Leads();
        $arr = $lead->protect("", $date_on, $date_off);
        $arr_to_json = [];
        foreach ($arr as $key => $item) {
            foreach ($item as $key1 => $item1) {
                if (mb_detect_encoding($item1) != 'utf-8') {
                    $item1 = mb_convert_encoding($item1, 'utf-8');
                }
                $arr_to_json[$key][$key1] = $item1;
            }
        }


        $json = json_encode($arr_to_json, JSON_UNESCAPED_UNICODE);
        echo $json;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
