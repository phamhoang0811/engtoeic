<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grammar;

class GrammarController extends Controller
{
    public function index(Request $request)
    {
        $score = $request->query('score', 450);
        $grammars = Grammar::where('target_score', $score)->get();
        
        return view('grammars', compact('grammars', 'score'));
    }

    public function show($id)
    {
        $grammar = Grammar::findOrFail($id);
        return view('grammar_detail', compact('grammar'));
    }
}