<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Menampilkan semua testimoni.
     */
    public function index()
    {
        $testimonials = Testimonial::with(['user', 'product'])->latest()->paginate(15);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Memperbarui status testimoni (misal: menyetujui).
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate(['status' => 'required|in:approved,pending']);

        $testimonial->update(['status' => $request->status]);

        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Status testimoni berhasil diperbarui.');
    }

    /**
     * Menghapus testimoni.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Testimoni berhasil dihapus.');
    }
}