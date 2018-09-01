<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function showSearchForm()
    {
        return view('search.form');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function executeSearch(Request $request)
    {
        $result = [
            ['id' => 2, 'score' => 0.9],
            ['id' => 1, 'score' => 0.5],
        ];
        return view('search.result', ['result' => $result]);
    }
}
