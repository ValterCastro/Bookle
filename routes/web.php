<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchResults;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;


Route::get('/home', [HomeController::class, 'view_home'])->name('home');
Route::get('/home/search_results', [SearchResults::class,'view_search_results'])->name('search_results');
Route::get('/home/search_results', [SearchResults::class,'solr_search_results_non_boosted'])->name('solr_search_results_non_boosted');
Route::post('/home/solr_search_results', [SearchController::class,'searchSolr'])->name('solr_search');
Route::post('/home/form_search_results', [SearchController::class, 'showFirstTitle'])->name('search');
