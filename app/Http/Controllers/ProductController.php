<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const CATEGORIES = [
        'Elektronik',
        'Fashion',
        'Rumah tangga',
        'Kecantikan',
        'Makanan',
        'Olahraga',
        'Lainnya',
    ];

    private const MARKETPLACES = [
        'Shopee',
        'Tokopedia',
        'TikTok Shop',
        'Lazada',
        'Blibli',
        'Lainnya',
    ];

    public function index(Request $request): View
    {
        $userProducts = $request->user()->products();

        $products = (clone $userProducts)
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->trim()->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->input('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('dashboard', [
            'products' => $products,
            'categories' => self::CATEGORIES,
            'totalProducts' => (clone $userProducts)->count(),
            'activeProducts' => (clone $userProducts)->where('status', 'active')->count(),
            'lowStockProducts' => (clone $userProducts)->where('stock', '<=', 5)->count(),
            'inventoryValue' => (clone $userProducts)->selectRaw('COALESCE(SUM(price * stock), 0) as total')->value('total'),
        ]);
    }

    public function create(): View
    {
        return view('products.form', [
            'categories' => self::CATEGORIES,
            'marketplaces' => self::MARKETPLACES,
        ]);
    }

    public function edit(Request $request, Product $product): View
    {
        abort_unless($product->user_id === $request->user()->id, 403);

        return view('products.form', [
            'product' => $product,
            'categories' => self::CATEGORIES,
            'marketplaces' => self::MARKETPLACES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->user()->products()->create($this->validatedData($request));

        return redirect()->route('products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->user_id === $request->user()->id, 403);

        $product->update($this->validatedData($request, $product));

        return redirect()->route('products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->user_id === $request->user()->id, 403);

        $product->delete();

        return redirect()->route('products.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')
                    ->where('user_id', $request->user()->id)
                    ->ignore($product?->id),
            ],
            'marketplace' => ['required', Rule::in(self::MARKETPLACES)],
            'category' => ['required', Rule::in(self::CATEGORIES)],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:99999999'],
            'status' => ['required', Rule::in(['active', 'draft'])],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
