<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    /**
     * A basic feature test login.
     */
    public function test_login_valid(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'amirfahmi8@test.com'],
            [
                'email' => 'amirfahmi8@test.com',
                'name' => 'fahmi',
                'password' => bcrypt('password')
            ]
        );

        $userData = [
            "email" => "amirfahmi8@test.com",
            "password" => "password"
        ];

        $this->json("POST", "api/v1/login", $userData, ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "email_verified_at",
                "created_at",
                "updated_at",
                "token"
            ]);

        $this->assertAuthenticated();
    }

    /**
     * A basic feature test login.
     */
    public function test_login_not_registered(): void
    {
        $userData = [
            "email" => "amirfahmi8@testting.com",
            "password" => "password"
        ];

        $this->json("POST", "api/v1/login", $userData, ["Accept" => "application/json"])
            ->assertStatus(401)
            ->assertJsonStructure([
                "message"
            ]);
    }

    /**
     * A basic feature test login.
     */
    public function test_login_wrong_credentioal(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'amirfahmi8@test.com'],
            [
                'email' => 'amirfahmi8@test.com',
                'name' => 'fahmi',
                'password' => bcrypt('password')
            ]
        );

        $userData = [
            "email" => "amirfahmi8@test.com",
            "password" => "passwordsalah"
        ];

        $this->json("POST", "api/v1/login", $userData, ["Accept" => "application/json"])
            ->assertStatus(401)
            ->assertJsonStructure([
                "message"
            ]);
    }

     /**
     * A basic feature test profile.
     */
    public function test_profile()
    {
        $user = User::updateOrCreate(
            ['email' => 'amirfahmi8@test.com'],
            [
                'email' => 'amirfahmi8@test.com',
                'name' => 'fahmi',
                'password' => bcrypt('password')
            ]
        );

        Sanctum::actingAs(
            $user
        );

        $this->json("GET", "api/v1/profile", ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "email_verified_at",
                "created_at",
                "updated_at"
            ]);
    }

    /**
     * A basic feature test register.
     */
    public function test_register_valid(): void
    {

        $userData = [
            "name" => "Fahmi",
            "email" => time()."@test.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $this->json("POST", "api/v1/register", $userData, ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "email_verified_at",
                "created_at",
                "updated_at",
                "token"
            ]);

        $this->assertAuthenticated();
    }
}
