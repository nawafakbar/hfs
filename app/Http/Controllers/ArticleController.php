<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show($slug)
    {
        // Cari artikel berdasarkan slug
        $article = Article::where('slug', $slug)->firstOrFail();
        
        // Cari artikel lain untuk rekomendasi (selain yg sedang dibuka)
        $relatedArticles = Article::where('id', '!=', $article->id)->latest()->take(3)->get();

        return view('user.articles.show', compact('article', 'relatedArticles'));
    }
}