<?php

namespace App\Http\Controllers;

class SearchResults
{
    public function view_search_results()
    {

        $books = session('books', []);

        return view('search_results', compact('books'));
    }

    public function solr_search_results_non_boosted()
    {

        $books = session('books', []);

        return view('solr_search_results_non_boosted', compact('books'));
    }
}
