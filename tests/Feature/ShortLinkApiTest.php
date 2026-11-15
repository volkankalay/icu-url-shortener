<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ShortLinkApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = SettingSeeder::class;

    public function test_token_login_and_authenticated_link_creation(): void
    {
        $user = User::factory()->create();

        foreach (['username', 'email'] as $field) {
            $token = $this->postJson('/api/auth/token', [$field => $user->{$field}, 'password' => 'password'])
                ->assertOk()->assertHeader('Cache-Control', 'no-store, private')
                ->assertJson(['token_type' => 'Bearer', 'expires_in' => 3600])->json('access_token');

            $this->assertMatchesRegularExpression('/\A[0-9a-f]{64}\z/', $token);
            $this->assertSame($user->id, Cache::get('api:token:' . hash('sha256', $token))['user_id']);
            Auth::forgetGuards();
            $response = $this->withToken($token)->postJson('/api/links', ['url' => 'https://example.com'])
                ->assertCreated();
            $link = $user->links()->sole();
            $response->assertExactJson([
                'success'   => true,
                'short_url' => rtrim(Setting::firstOrFail()->site, '/') . '/' . $link->short,
            ]);
        }
    }

    public function test_api_rejects_invalid_credentials_and_tokens(): void
    {
        $user = User::factory()->create();
        $this->post('/api/links', ['url' => 'https://example.com'])->assertUnauthorized()->assertJsonStructure(['message']);
        $this->postJson('/api/auth/token', ['username' => $user->username, 'password' => 'incorrect'])->assertUnauthorized();
        $this->postJson('/api/auth/token', ['username' => $user->username, 'email' => $user->email, 'password' => 'password'])
            ->assertUnprocessable();
        $token = $this->postJson('/api/auth/token', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()->json('access_token');
        Auth::forgetGuards();
        $this->withToken($token)->postJson('/api/links', ['url' => 'ftp://example.com'])->assertUnprocessable();
        Auth::forgetGuards();
        $this->travel(61)->minutes();
        $this->postJson('/api/links', ['url' => 'https://example.com'])->assertUnauthorized();
    }
}
