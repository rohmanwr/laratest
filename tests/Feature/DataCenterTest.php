<?php

namespace Tests\Feature;

use App\Models\ArchivedImage;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DataCenterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_dashboard_shows_only_the_authenticated_users_images_and_notes(): void
    {
        Storage::fake('archive');
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $user->archivedImages()->create([
            'path' => '1/landscape.jpg',
            'original_name' => 'landscape.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 2048,
        ]);
        $otherUser->archivedImages()->create([
            'path' => '2/private.jpg',
            'original_name' => 'private.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 4096,
        ]);
        $user->notes()->create(['title' => 'Ide penting', 'body' => 'Catatan milik sendiri.']);
        $otherUser->notes()->create(['title' => 'Rahasia', 'body' => 'Tidak boleh terlihat.']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('landscape.jpg')
            ->assertSee('Ide penting')
            ->assertDontSee('private.jpg')
            ->assertDontSee('Rahasia')
            ->assertSee('Unggah gambar')
            ->assertDontSee('Total gambar')
            ->assertDontSee('Diunggah bulan ini')
            ->assertDontSee('Manajemen pengguna');
    }

    public function test_user_can_upload_multiple_images_with_a_caption(): void
    {
        Storage::fake('archive');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('images.store'), [
                'images' => [
                    UploadedFile::fake()->image('first.jpg'),
                    UploadedFile::fake()->image('second.png'),
                ],
                'caption' => 'Dokumen penting',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', '2 gambar berhasil disimpan.');

        $this->assertDatabaseCount('archived_images', 2);
        foreach (ArchivedImage::all() as $image) {
            $this->assertSame($user->id, $image->user_id);
            $this->assertSame('Dokumen penting', $image->caption);
            $this->assertTrue(Storage::disk('archive')->exists($image->path));
        }
    }

    public function test_user_can_upload_images_selected_from_a_folder(): void
    {
        Storage::fake('archive');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('images.store'), [
                'folder_images' => [
                    UploadedFile::fake()->image('folder-first.jpg'),
                    UploadedFile::fake()->image('folder-second.png'),
                ],
                'caption' => 'Gambar dari folder',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', '2 gambar berhasil disimpan.');

        $this->assertDatabaseCount('archived_images', 2);
        $this->assertDatabaseHas('archived_images', [
            'user_id' => $user->id,
            'original_name' => 'folder-first.jpg',
            'caption' => 'Gambar dari folder',
        ]);
        foreach (ArchivedImage::all() as $image) {
            $this->assertTrue(Storage::disk('archive')->exists($image->path));
        }
    }

    public function test_image_upload_form_displays_500_mb_limits_for_files_and_total_upload(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('images.create'))
            ->assertOk()
            ->assertSee('data-max-file-bytes="524288000"', false)
            ->assertSee('data-max-total-bytes="524288000"', false)
            ->assertSee('500 MB per file, dan 500 MB total per unggahan')
            ->assertDontSee('Batas efektif di server saat ini');
    }

    public function test_image_upload_rejects_invalid_files_and_too_many_files(): void
    {
        Storage::fake('archive');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('images.store'), [
                'images' => [UploadedFile::fake()->create('notes.txt', 2, 'text/plain')],
            ])
            ->assertSessionHasErrors('images.0');

        $this->actingAs($user)
            ->post(route('images.store'), [
                'images' => array_fill(0, 21, UploadedFile::fake()->image('photo.jpg')),
            ])
            ->assertSessionHasErrors('images');

        $this->assertDatabaseCount('archived_images', 0);
    }

    public function test_user_can_view_and_delete_only_their_own_image(): void
    {
        Storage::fake('archive');
        $user = User::factory()->create();
        $otherImage = User::factory()->create()->archivedImages()->create([
            'path' => 'other/hidden.jpg',
            'original_name' => 'hidden.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 100,
        ]);
        $image = $user->archivedImages()->create([
            'path' => 'own/photo.jpg',
            'original_name' => 'photo.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 100,
        ]);
        Storage::disk('archive')->put($image->path, 'image bytes');

        $this->actingAs($user)->get(route('images.file', $image))->assertOk();
        $this->actingAs($user)->get(route('images.file', $otherImage))->assertForbidden();
        $this->actingAs($user)->delete(route('images.destroy', $otherImage))->assertForbidden();
        $this->actingAs($user)->delete(route('images.destroy', $image))->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('archived_images', ['id' => $image->id]);
        $this->assertFalse(Storage::disk('archive')->exists($image->path));
        $this->assertDatabaseHas('archived_images', ['id' => $otherImage->id]);
    }

    public function test_deleting_an_account_also_removes_its_private_image_files(): void
    {
        Storage::fake('archive');
        $user = User::factory()->create();
        $image = $user->archivedImages()->create([
            'path' => 'own/photo.jpg',
            'original_name' => 'photo.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 100,
        ]);
        Storage::disk('archive')->put($image->path, 'image bytes');

        $user->delete();

        $this->assertFalse(Storage::disk('archive')->exists($image->path));
        $this->assertDatabaseMissing('archived_images', ['id' => $image->id]);
    }

    public function test_user_can_create_edit_and_delete_their_own_notes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('notes.store'), ['title' => 'Rencana', 'body' => 'Simpan ide baru.'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard').'#notes');

        $note = Note::query()->firstOrFail();
        $this->assertSame($user->id, $note->user_id);

        $this->put(route('notes.update', $note), ['title' => 'Rencana baru', 'body' => 'Catatan diperbarui.'])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard').'#notes');
        $this->assertDatabaseHas('notes', ['id' => $note->id, 'title' => 'Rencana baru']);

        $otherNote = User::factory()->create()->notes()->create(['title' => 'Milik orang lain', 'body' => 'Pribadi.']);
        $this->put(route('notes.update', $otherNote), ['title' => 'Ubah', 'body' => 'Tidak boleh.'])->assertForbidden();
        $this->delete(route('notes.destroy', $otherNote))->assertForbidden();

        $this->delete(route('notes.destroy', $note))->assertRedirect(route('dashboard').'#notes');
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    public function test_dashboard_can_search_images_by_name_or_caption(): void
    {
        $user = User::factory()->create();
        $user->archivedImages()->create([
            'path' => '1/receipt.jpg',
            'original_name' => 'receipt.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 100,
            'caption' => 'Belanja bulanan',
        ]);
        $user->archivedImages()->create([
            'path' => '1/trip.jpg',
            'original_name' => 'trip.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 100,
            'caption' => 'Liburan',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard', ['q' => 'Belanja']))
            ->assertOk()
            ->assertSee('receipt.jpg')
            ->assertDontSee('trip.jpg');
    }
}
