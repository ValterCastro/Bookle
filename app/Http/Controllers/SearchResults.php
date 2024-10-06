<?php

namespace App\Http\Controllers;

class SearchResults
{
    public function view_search_results()
    {

        $books = session('books', []);

        return view('search_results', compact('books'));
    }
}
