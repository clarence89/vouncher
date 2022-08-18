<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

use function PHPUnit\Framework\assertJson;

class UserTestUnitTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_page_redirection()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
    public function test_login_redirection()
    {
        $user = User::where('id', 1)->first();

        $user->assignRole('super-admin');
        $this->actingAs($user)
            ->get("/")
            ->assertStatus(302);
    }
    public function test_login_redirection_to_login()
    {
        $user = User::where('id', 1)->first();
        $user->assignRole('super-admin');
        $this->actingAs($user)
            ->get("/")
            ->assertRedirect(route('login'));
    }
    public function test_login()
    {


        $this->postJson(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'adminadmin'
        ])
            ->assertOk();
    }
    public function test_not_login()
    {


        $response = $this->postJson(route('login'), [
            'email' => 'admin11@gmail.com',
            'password' => 'adminadmin'
        ])
            ->assertStatus(422);
    }
    public function test_route_ordinary_user_get_voucher()
    {
        $user = User::where('id', 4)->first();
        $this->actingAs($user)
            ->get(route('get.voucher'))
            ->assertJson(['total' => true]);
    }
    public function test_route_ordinary_user_forbidden_get_group_users_moderator_permission()
    {
        $user = User::where('id', 4)->first();
        $this->actingAs($user)
            ->get(route('get.group'))
            ->assertStatus(403);
    }
    public function test_route_ordinary_user_forbidden_get_group_moderators_moderator_permission()
    {
        $user = User::where('id', 4)->first();
        $this->actingAs($user)
            ->get(route('get.get_user_moderator','Group1'))
            ->assertStatus(403);
    }
    public function test_route_ordinary_user_forbidden_get_all_users_superadmin_permission()
    {
        $user = User::where('id', 4)->first();
        $this->actingAs($user)
            ->get(route('admin.get_user','users'))
            ->assertStatus(403);
    }
    public function test_route_moderator_user_get_group_users_moderator_permission()
    {
        $user = User::where('id', 2)->first();
        $this->actingAs($user)
            ->get(route('get.group'))
            ->assertStatus(200);
    }
    public function test_route_ordinary_moderator_get_group_moderators_moderator_permission()
    {
        $user = User::where('id', 2)->first();
        $this->actingAs($user)
            ->get(route('get.get_user_moderator','Group1'))
            ->assertStatus(200);
    }
    public function test_route_ordinary_moderator_forbidden_get_all_users_superadmin_permission()
    {
        $user = User::where('id', 2)->first();
        $this->actingAs($user)
            ->get(route('admin.get_user','users'))
            ->assertStatus(403);
    }
    public function test_route_ordinary_superadmin_get_all_users_superadmin_permission()
    {
        $user = User::where('id', 1)->first();
        $this->actingAs($user)
            ->get(route('admin.get_user','users'))
            ->assertStatus(200);
    }
}
