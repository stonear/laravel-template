<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function index(Request $request)
    {
        $per_page = 10;
        $current_page = $request->input("page") ?? 1;
        $offset = ($current_page - 1) * $per_page;

        $results = Cache::remember('pokemon:' . $current_page, 3600, function () use ($offset, $per_page) {
            return Http::get('https://pokeapi.co/api/v2/pokemon', [
                'offset' => $offset,
                'limit' => $per_page,
            ])->object();
        });

        $pokemons = new Paginator($results->results, $results->count, $per_page, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('pokemons.index', compact('pokemons'));
    }
}
