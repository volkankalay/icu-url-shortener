<?php

namespace Tests\Feature;

use App\Enums\UserType;
use App\Models\Link;
use App\Models\Setting;
use App\Models\User;
use App\Support\Theme;
use App\Support\VisitorOptions;
use Database\Seeders\SettingSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_editor_can_edit_other_users_links_without_admin_permissions(): void
    {
        $owner = User::factory()->create();
        $editor = User::factory()->create(['user_type' => UserType::EDITOR]);
        $link = $owner->links()->create(['url' => 'https://example.com', 'short' => 'oldCode']);

        $this->actingAs($editor)->get(route('admin.links'))->assertOk()
            ->assertSee('Tüm bağlantılar')
            ->assertDontSee('Site ayarları')
            ->assertDontSee('data-disable-link=', escape: false);
        $this->get(route('url.show', $link->short))->assertOk()
            ->assertSee('data-target="#updateModal"', escape: false)
            ->assertSee('Bağlantı sahibi:')
            ->assertDontSee('data-target="#user-type-modal"', escape: false)
            ->assertSee($owner->full_name)
            ->assertSee('@' . $owner->username);
        $this->post(route('url.update.post'), ['shortold' => $link->short, 'shortnew' => 'newCode'])
            ->assertRedirect(route('url.show', 'newCode'));
        $this->assertSame('newCode', $link->refresh()->short);
        $this->post(route('admin.links.delete'), ['link' => $link->id])->assertRedirect(route('dashboard'));
        $this->assertNotSoftDeleted($link);

        $admin = User::factory()->create(['user_type' => UserType::ADMIN]);
        $detail = route('url.show', $link->short);
        $this->actingAs($admin)->get($detail)->assertOk()
            ->assertSee('data-target="#user-type-modal"', escape: false)
            ->assertSee('value="' . $owner->username . '" readonly', escape: false);
        $this->from($detail)->post(route('admin.users.rolechange'), ['username' => $owner->username, 'user_type' => 'editor'])
            ->assertRedirect($detail);
        $this->assertSame(UserType::EDITOR, $owner->refresh()->user_type);

        $this->actingAs(User::factory()->create())->get(route('admin.links'))->assertForbidden();
        $this->post(route('url.update.post'), ['shortold' => 'newCode', 'shortnew' => 'noEdit'])->assertNotFound();
    }

    use RefreshDatabase;

    protected $seeder = SettingSeeder::class;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_basic_test()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(__('homepage.slogan'));
        $response->assertDontSee('homepage.slogan');
        $response->assertSee(asset('img/icu-shortener.svg'));
        $response->assertSee('--theme-background: ' . Theme::COLORS['light']['background'], escape: false);
        $response->assertSee('--theme-background: ' . Theme::COLORS['dark']['background'], escape: false);
        $response->assertSee('id="theme-toggle"', escape: false);
    }

    public function test_missing_short_link_shows_its_notification_on_the_homepage(): void
    {
        $this->followingRedirects()
            ->get('/missing-link')
            ->assertOk()
            ->assertSee(json_encode('Adres Hatası'), escape: false);
    }

    public function test_login_page_receives_site_settings(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('id="theme-toggle"', escape: false)
            ->assertSee('ICU Shortener');
    }

    public function test_failed_login_returns_a_field_error_and_preserves_the_identifier(): void
    {
        $this->from(route('login'))->post(route('login.post'), [
            'username' => 'missing-user',
            'password' => 'incorrect-password',
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('username')
            ->assertSessionHasInput('username', 'missing-user');

        $this->get(route('login'))->assertOk()
            ->assertSee('aria-describedby="username-error"', escape: false)
            ->assertDontSee('incorrect-password');
    }

    public function test_login_with_email_can_remember_the_user(): void
    {
        $this->seed(UserSeeder::class);
        $user = User::firstOrFail();

        $this->post(route('login.post'), [
            'username'   => $user->email,
            'password'   => '123456',
            'rememberme' => '1',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotEmpty($user->refresh()->getRememberToken());
        $this->assertDatabaseHas('logins', ['user_id' => $user->id]);
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_registration_validates_required_fields_and_signs_in_the_new_user(): void
    {
        $this->get(route('register'))->assertOk()
            ->assertSee('id="password_confirmation"', escape: false)
            ->assertSee('id="theme-toggle"', escape: false);

        $this->from(route('register'))->post(route('register'), [])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['name', 'surname', 'email', 'username', 'password']);

        $this->post(route('register'), [
            'name'                  => 'Deniz Ege',
            'surname'               => 'Yılmaz',
            'email'                 => 'deniz@example.com',
            'username'              => 'deniz',
            'password'              => 'test-password',
            'password_confirmation' => 'test-password',
        ])->assertRedirect(route('dashboard'));

        $user = User::where('username', 'deniz')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('logins', ['user_id' => $user->id]);
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_short_link_redirects_and_records_a_visit_without_a_user_agent(): void
    {
        $this->seed(UserSeeder::class);

        $link = new Link;
        $link->url = 'https://example.com';
        $link->short = 'example';
        $link->owner_id = User::firstOrFail()->id;
        $link->save();

        $this->get('/example')->assertRedirect($link->url);
        $this->assertDatabaseHas('visitors', [
            'link_id' => $link->id,
        ]);
        $this->assertSame('Other', $link->visitors()->firstOrFail()->options->browser);
        $this->assertSame(1, $link->refresh()->total_clicks);
        $this->assertSame(1, $link->getTodayCount());
        $this->assertSame(1, $link->getMonthCount());
        $this->assertNotNull($link->visitors()->firstOrFail()->created_at);

        $link->delete();
        $this->get('/example')->assertRedirect(route('home'));
        $this->assertSame(1, $link->visitors()->onlyTrashed()->count());
    }

    public function test_model_casts_and_relationships_match_the_migrated_schema(): void
    {
        $user = User::factory()->create();
        $login = $user->logins()->create(['tarih' => now()]);

        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayNotHasKey('remember_token', $user->toArray());
        $this->assertSame(UserType::REGULAR, $user->refresh()->user_type);
        $this->assertSame($user->id, $login->user->id);
        $this->assertNotNull($login->created_at);
        $login->delete();
        $this->assertSame(0, $user->logins()->count());

        $settings = Setting::firstOrFail();
        $this->assertTrue($settings->login);
        $this->assertTrue($settings->register);
        $settings->delete();
        $this->assertFalse(Setting::query()->exists());
    }

    public function test_dashboard_displays_recent_links_and_the_link_creation_result(): void
    {
        $router = app(Router::class);
        $router->setCompiledRoutes($router->getRoutes()->compile());

        $user = User::factory()->create();

        $this->actingAs($user)->post(route('url.create.post'), ['url' => 'https://example.com/article'])
            ->assertRedirect(route('dashboard'));
        $link = $user->links()->firstOrFail();
        $this->assertMatchesRegularExpression('/\A[0-9A-Za-z]{4}\z/', $link->short);
        $this->post(route('url.create.post'), ['url' => $link->url])
            ->assertSessionHas('short_link', $link->short);
        $this->assertSame(1, $user->links()->count());
        $link->visitors()->create(['options' => new VisitorOptions(ipv4: '192.0.2.5', browser: 'Firefox')]);

        $this->get(route('dashboard'))->assertOk()
            ->assertViewHas('todayVisits', 1)
            ->assertViewHas('monthVisits', 1)
            ->assertSee('Kısa bağlantın hazır')
            ->assertSee('Son 7 gün')
            ->assertSee('/' . $link->short);
        $this->get(route('url.create'))->assertRedirect(route('dashboard') . '#baglanti-olustur');
        $this->get(route('url.list'))->assertOk()
            ->assertSee('https://example.com/article')
            ->assertSee('fa-arrow-right', escape: false)
            ->assertDontSee('id="updateModal"', escape: false);
        $this->get(route('url.show', $link->short))->assertOk()
            ->assertDontSee('Bağlantı sahibi:')
            ->assertSee('Firefox')
            ->assertSee('data-target="#updateModal"', escape: false)
            ->assertSee('Son 7 günün tekil IP sayıları')
            ->assertViewHas('activity', fn (array $activity): bool => count($activity) === 7 && $activity[6]['count'] === 1);

        $this->actingAs(User::factory()->create())->get(route('url.show', $link->short))
            ->assertRedirect(route('url.list'));
        $this->actingAs(User::factory()->create(['user_type' => UserType::ADMIN]))
            ->get(route('url.show', $link->short))->assertOk()->assertSee('Bağlantı sahibi:');
    }

    public function test_visits_are_recorded_once_per_link_ip_and_day(): void
    {
        $this->travelTo(now()->startOfDay()->addHour());
        $user = User::factory()->create();
        $link = $user->links()->create(['url' => 'https://example.com', 'short' => 'uniq']);
        $other = $user->links()->create(['url' => 'https://example.org', 'short' => 'next']);

        foreach (['192.0.2.5', '::ffff:192.0.2.5', '2001:db8::5', '2001:0db8:0:0:0:0:0:5'] as $ip) {
            $this->withServerVariables(['REMOTE_ADDR' => $ip])->get('/uniq', ['Accept-Language' => ''])->assertRedirect($link->url);
        }

        $this->assertSame(2, $link->visitors()->count());
        $this->assertSame(4, $link->refresh()->total_clicks);
        $this->actingAs($user)->get(route('url.list'))
            ->assertOk()
            ->assertSee('class="links-views" data-order="2"', escape: false)
            ->assertSee('Tekil ziyaret');
        $visits = $link->visitors()->orderBy('id')->get();
        $this->assertSame('192.0.2.5', $visits[0]->options->ipv4);
        $this->assertSame('2001:db8::5', $visits[1]->options->ipv6);
        $this->assertArrayNotHasKey('ipv6', json_decode($visits[0]->getRawOriginal('options'), true));
        $this->assertArrayNotHasKey('ipv4', json_decode($visits[1]->getRawOriginal('options'), true));
        $this->assertArrayNotHasKey('browser_version', $visits[0]->toArray()['options']);
        $this->assertArrayNotHasKey('referrer_host', $visits[0]->toArray()['options']);
        $this->assertArrayNotHasKey('language', $visits[0]->toArray()['options']);

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.5'])
            ->get('/next', ['Referer' => 'https://source.example/path?private=value', 'Accept-Language' => 'en;q=0.4,tr-TR;q=0.9'])
            ->assertRedirect($other->url);
        $this->assertSame(1, $other->visitors()->count());
        $otherOptions = $other->visitors()->firstOrFail()->options;
        $this->assertSame('source.example', $otherOptions->referrerHost);
        $this->assertSame('tr-TR', $otherOptions->language);

        $this->travel(1)->day();
        $this->get('/uniq')->assertRedirect($link->url);
        $this->assertSame(3, $link->visitors()->count());
        $this->assertSame(5, $link->refresh()->total_clicks);
    }

    public function test_visitor_options_only_persist_known_fields_with_values(): void
    {
        $user = User::factory()->create();
        $link = $user->links()->create(['url' => 'https://example.com', 'short' => 'safe']);
        $options = VisitorOptions::fromArray(['ipv4' => '1.1.1.1', 'ipv6' => null, 'extra' => 'ignored']);
        $visit = $link->visitors()->create(['options' => $options])->refresh();

        $this->assertInstanceOf(VisitorOptions::class, $visit->options);
        $this->assertSame(['ipv4' => '1.1.1.1'], json_decode($visit->getRawOriginal('options'), true));
        $this->assertSame(['ipv4' => '1.1.1.1'], $visit->toArray()['options']);

        $this->expectException(\InvalidArgumentException::class);
        $link->visitors()->create(['options' => ['extra' => 'untyped']]);
    }

    public function test_short_codes_are_case_sensitive_and_deleted_codes_remain_reserved(): void
    {
        $user = User::factory()->create();
        $original = $user->links()->create(['url' => 'https://example.com/first', 'short' => '09aZ']);
        $other = $user->links()->create(['url' => 'https://example.com/second', 'short' => 'Other']);

        $this->actingAs($user)->post(route('url.update.post'), ['shortold' => 'Other', 'shortnew' => '09az'])
            ->assertRedirect(route('url.show', '09az'));
        $this->get('/09aZ')->assertRedirect($original->url);
        $this->get('/09az')->assertRedirect($other->url);

        $original->delete();
        $this->post(route('url.update.post'), ['shortold' => '09az', 'shortnew' => '09aZ'])
            ->assertSessionHasErrors('shortnew');
        $this->post(route('url.update.post'), ['shortold' => '09az', 'shortnew' => 'abc'])
            ->assertSessionHasErrors('shortnew');
        $this->post(route('url.update.post'), ['shortold' => '09az', 'shortnew' => 'login'])
            ->assertSessionHasErrors('shortnew');
    }

    public function test_short_codes_cannot_reuse_registered_route_prefixes(): void
    {
        Route::get('/destek/{article}', fn () => 'Destek');
        $user = User::factory()->create();
        $user->links()->create(['url' => 'https://example.com', 'short' => 'existing']);

        $this->actingAs($user)->post(route('url.update.post'), ['shortold' => 'existing', 'shortnew' => 'destek'])
            ->assertSessionHasErrors('shortnew');
        $this->post(route('url.update.post'), ['shortold' => 'existing', 'shortnew' => 'bosKod'])
            ->assertRedirect(route('url.show', 'bosKod'));
    }

    public function test_only_admins_can_change_a_user_to_a_valid_user_type(): void
    {
        $admin = User::factory()->create(['user_type' => UserType::ADMIN]);
        $member = User::factory()->create();
        $route = route('admin.users.rolechange');

        $this->actingAs($admin)->get(route('admin.users'))->assertOk()->assertSee('name="user_type"', escape: false);
        $this->from(route('admin.users'))->post($route, ['username' => $member->username, 'user_type' => '2'])
            ->assertSessionHasErrors('user_type');
        $this->from(route('admin.users'))->post($route, ['username' => $member->username, 'user_type' => 'editor'])
            ->assertRedirect(route('admin.users'))->assertSessionHasNoErrors();
        $this->assertSame(UserType::EDITOR, $member->refresh()->user_type);
        $this->assertDatabaseHas('users', ['id' => $member->id, 'user_type' => 'editor']);

        $this->actingAs($member)->post($route, ['username' => $member->username, 'user_type' => 'admin'])
            ->assertRedirect(route('dashboard'));
        $this->assertSame(UserType::EDITOR, $member->refresh()->user_type);
    }

    public function test_admin_soft_deletes_a_link_and_its_visits(): void
    {
        $admin = User::factory()->create(['user_type' => UserType::ADMIN]);
        $link = $admin->links()->create(['url' => 'https://example.com', 'short' => 'stop']);
        $visit = $link->visitors()->create(['options' => new VisitorOptions(ipv4: '192.0.2.5')]);

        $this->actingAs($admin)->get(route('admin.links'))->assertOk()->assertSee('Devre dışı bırak');
        $this->from(route('admin.links'))->post(route('admin.links.delete'), ['link' => $link->id])
            ->assertRedirect(route('admin.links'));

        $this->assertSoftDeleted($link);
        $this->assertSoftDeleted($visit);
        $this->get(route('direct', $link->short))->assertRedirect(route('home'));
    }

    public function test_profile_password_change_uses_the_authenticated_account(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $otherPassword = $other->password;

        $this->actingAs($user)->get(route('profile.index'))->assertOk()->assertSee($user->full_name);
        $this->from(route('profile.index'))->post(route('profile.pass'), [
            'current_password'      => 'incorrect-password',
            'password'              => 'updated-password',
            'password_confirmation' => 'updated-password',
        ])->assertSessionHasErrorsIn('passwordChange', 'current_password')
            ->assertSessionMissing('_old_input.current_password');

        $this->post(route('profile.pass'), [
            'username'              => $other->username,
            'current_password'      => 'password',
            'password'              => 'updated-password',
            'password_confirmation' => 'updated-password',
        ])->assertRedirect(route('profile.index'));

        $this->assertTrue(Hash::check('updated-password', $user->refresh()->password));
        $this->assertSame($otherPassword, $other->refresh()->password);
    }

    public function test_profile_deletion_requires_confirmation_and_signs_out_the_owner(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user)->from(route('profile.index'))->post(route('profile.delete'), [
            'current_password' => 'password',
        ])->assertSessionHasErrorsIn('accountDeletion', 'confirm_delete');
        $this->assertNotSoftDeleted($user);

        $this->post(route('profile.delete'), [
            'username'         => $other->username,
            'current_password' => 'password',
            'confirm_delete'   => '1',
        ])->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertSoftDeleted($user);
        $this->assertNotSoftDeleted($other);
    }

    public function test_admin_settings_validates_and_saves_the_grouped_fields(): void
    {
        $admin = User::factory()->create(['user_type' => UserType::ADMIN]);
        $url = route('admin.settings');

        $this->actingAs($admin)->get($url)->assertOk()
            ->assertSee('Site kimliği')->assertSee('Marka görselleri')->assertSee('Üyelik')->assertSee('Gelişmiş');
        $this->from($url)->post(route('admin.settings.update'), ['site' => 'not-a-url', 'register' => '1'])
            ->assertSessionHasErrors('site');

        $this->post(route('admin.settings.update'), [
            'site'        => 'https://short.example.com',
            'title'       => 'Kısa Bağlantılar',
            'description' => 'Site açıklaması örneği.',
            'register'    => '0',
        ])->assertRedirect($url)->assertSessionHasNoErrors();

        $settings = Setting::firstOrFail();
        $this->assertSame('Site açıklaması örneği.', $settings->description);
        $this->assertFalse($settings->register);
    }
}
