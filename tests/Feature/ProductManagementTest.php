<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_dashboard_displays_only_the_authenticated_sellers_products_and_summary(): void
    {
        $seller = User::factory()->create();
        $otherSeller = User::factory()->create();

        $seller->products()->create([
            'name' => 'Tas Kanvas',
            'category' => 'Fashion',
            'price' => 150000,
            'stock' => 3,
            'status' => 'active',
        ]);
        $otherSeller->products()->create([
            'name' => 'Produk Rahasia',
            'category' => 'Elektronik',
            'price' => 50000,
            'stock' => 20,
            'status' => 'active',
        ]);

        $response = $this->actingAs($seller)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Tas Kanvas')
            ->assertDontSee('Produk Rahasia')
            ->assertSee('Rp450.000')
            ->assertSee('Stok menipis');
    }

    public function test_products_index_displays_create_edit_and_delete_actions(): void
    {
        $seller = User::factory()->create();
        $seller->products()->create([
            'name' => 'Botol Minum',
            'category' => 'Rumah tangga',
            'price' => 75000,
            'stock' => 20,
            'status' => 'active',
        ]);

        $this->actingAs($seller)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Tambah produk')
            ->assertSee(route('products.create'))
            ->assertSee('Botol Minum')
            ->assertSee('Edit')
            ->assertSee('Hapus')
            ->assertSee('Index produk');
    }

    public function test_seller_can_create_update_and_delete_a_product(): void
    {
        $seller = User::factory()->create();
        $productData = [
            'name' => 'Tas Kanvas',
            'sku' => 'TAS-001',
            'marketplace' => 'Shopee',
            'category' => 'Fashion',
            'price' => 150000,
            'stock' => 12,
            'status' => 'active',
            'description' => 'Tas kanvas untuk sehari-hari.',
        ];

        $this->actingAs($seller)
            ->post(route('products.store'), $productData)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('products.index'));

        $product = Product::query()->where('sku', 'TAS-001')->firstOrFail();
        $this->assertSame($seller->id, $product->user_id);
        $this->assertSame('Shopee', $product->marketplace);

        $this->put(route('products.update', $product), [
            ...$productData,
            'name' => 'Tas Kanvas Premium',
            'stock' => 8,
        ])->assertSessionHasNoErrors()
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Tas Kanvas Premium',
            'stock' => 8,
        ]);

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_seller_cannot_update_or_delete_another_sellers_product(): void
    {
        $seller = User::factory()->create();
        $product = User::factory()->create()->products()->create([
            'name' => 'Produk orang lain',
            'category' => 'Lainnya',
            'price' => 10000,
            'stock' => 4,
            'status' => 'active',
        ]);

        $this->actingAs($seller)
            ->put(route('products.update', $product), [
                'name' => 'Diubah',
                'category' => 'Lainnya',
                'price' => 10000,
                'stock' => 4,
                'status' => 'active',
            ])->assertForbidden();

        $this->delete(route('products.destroy', $product))->assertForbidden();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Produk orang lain']);
    }

    public function test_product_search_and_filters_are_applied_to_the_dashboard(): void
    {
        $seller = User::factory()->create();
        $seller->products()->create([
            'name' => 'Kemeja Linen',
            'sku' => 'KEM-01',
            'category' => 'Fashion',
            'price' => 200000,
            'stock' => 10,
            'status' => 'active',
        ]);
        $seller->products()->create([
            'name' => 'Headphone',
            'sku' => 'EL-01',
            'category' => 'Elektronik',
            'price' => 300000,
            'stock' => 5,
            'status' => 'draft',
        ]);

        $this->actingAs($seller)
            ->get(route('dashboard', ['q' => 'KEM', 'category' => 'Fashion', 'status' => 'active']))
            ->assertOk()
            ->assertSee('Kemeja Linen')
            ->assertDontSee('Headphone');
    }

    public function test_product_input_is_validated(): void
    {
        $seller = User::factory()->create();

        $this->actingAs($seller)
            ->post(route('products.store'), [
                'name' => '',
                    'sku' => '',
                    'marketplace' => 'Tidak tersedia',
                    'category' => 'Kategori tidak valid',
                'price' => -1,
                'stock' => -1,
                'status' => 'unknown',
            ])
            ->assertSessionHasErrors(['name', 'sku', 'marketplace', 'category', 'price', 'stock', 'status']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_create_and_edit_pages_show_marketplace_and_rupiah_fields(): void
    {
        $seller = User::factory()->create();

        $this->actingAs($seller)
            ->get(route('products.create'))
            ->assertOk()
            ->assertSee('Kode barang')
            ->assertSee('Nama barang')
            ->assertSee('Harga barang')
            ->assertSee('Marketplace')
            ->assertSee('Rp');

        $product = $seller->products()->create([
            'name' => 'Botol Minum',
            'sku' => 'BOT-001',
            'category' => 'Rumah tangga',
            'price' => 75000,
            'stock' => 20,
            'status' => 'active',
        ]);

        $this->get(route('products.edit', $product))
            ->assertOk()
            ->assertSee('Edit produk')
            ->assertSee('BOT-001');
    }
}
