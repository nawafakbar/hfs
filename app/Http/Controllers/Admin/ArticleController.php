<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Tampilkan daftar artikel.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Tampilkan form tambah artikel.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Simpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 1. Buat Slug otomatis
        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        
        // 2. Ambil author otomatis
        $validated['author'] = auth()->user()->name ?? 'Admin'; 

        // 3. (PERBAIKAN) Buat Excerpt otomatis dari konten (ambil 150 huruf pertama)
        $validated['excerpt'] = Str::limit(strip_tags($request->content), 150);

        // 4. Upload Gambar
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit artikel.
     */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update artikel yang sudah ada.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Update Slug jika judul berubah
        if ($request->title !== $article->title) {
            $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        // (PERBAIKAN) Update Excerpt juga saat konten diedit
        $validated['excerpt'] = Str::limit(strip_tags($request->content), 150);

        // Handle Gambar Baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Hapus artikel.
     */
    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus!');
    }
}