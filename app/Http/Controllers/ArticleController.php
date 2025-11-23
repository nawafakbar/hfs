<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Tampilkan halaman arsip (semua artikel).
     */
    public function index(Request $request)
    {
        // Mulai query
        $query = Article::latest();

        // Fitur Pencarian (Optional tapi keren)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
        }

        // Ambil data dengan pagination (misal 9 per halaman)
        $articles = $query->paginate(9);

        return view('user.articles.index', compact('articles'));
    }

    /**
     * Tampilkan detail artikel.
     */
    public function show($slug)
    {
        // Cari artikel berdasarkan slug
        $article = Article::where('slug', $slug)->firstOrFail();
        
        // Cari artikel lain untuk rekomendasi (selain yg sedang dibuka)
        $relatedArticles = Article::where('id', '!=', $article->id)->latest()->take(3)->get();

        return view('user.articles.show', compact('article', 'relatedArticles'));
    }
}