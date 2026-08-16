<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['images', 'variants'])
            ->when($request->string('kategori')->toString(), fn ($query, $slug) => $query->whereHas('category', fn ($q) => $q->where('slug', $slug))
            )
            ->when($request->string('cari')->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%")
            )
            ->latest()
            ->get();

        return view('catalog.index', [
            'products' => $products,
            'categories' => Category::all(),
            'activeCategory' => $request->string('kategori')->toString(),
            'search' => $request->string('cari')->toString(),
        ]);
    }
}
