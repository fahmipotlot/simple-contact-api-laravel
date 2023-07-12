<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;
use Laravel\Sanctum\Sanctum;

class ContactTest extends TestCase
{
    /**
     * A basic feature test list.
     */
    public function test_contact_list(): void
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

        $this->json("GET", "api/v1/contact", ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "*" => [
                    "id",
                    "name",
                    "email",
                    "phone",
                    "user_id",
                    "created_at",
                    "updated_at"
                ]
            ]);
    }

    /**
     * A basic feature test list.
     */
    public function test_contact_list_search(): void
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

        $contact = Contact::create(
            [
                'email' => time().'@test.com',
                'name' => 'fahmi',
                'phone' => '085635463254',
                'user_id' => $user->id
            ]
        );

        $this->json("GET", "api/v1/contact", ['q' => 'test'], ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "*" => [
                    "id",
                    "name",
                    "email",
                    "phone",
                    "user_id",
                    "created_at",
                    "updated_at"
                ]
            ]);
    }

    /**
     * A basic feature test detail.
     */
    public function test_contact_detail(): void
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

        $contact = Contact::create(
            [
                'email' => time().'@test.com',
                'name' => 'fahmi',
                'phone' => '085635463254',
                'user_id' => $user->id
            ]
        );

        $this->json("GET", "api/v1/contact/".$contact->id, ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "phone",
                "user_id",
                "created_at",
                "updated_at",
                "user" => [
                    "id",
                    "name",
                    "email",
                    "email_verified_at",
                    "created_at",
                    "updated_at"
                ]
            ]);
    }

    /**
     * A basic feature test detail.
     */
    public function test_contact_detail_not_found(): void
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

        $this->json("GET", "api/v1/contact/0", ["Accept" => "application/json"])
            ->assertStatus(404)
            ->assertJsonStructure([
                "message"
            ]);
    }

    /**
     * A basic feature test store.
     */
    public function test_contact_store(): void
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

        $contact = [
            "name" => "fahmi",
            "email" => time()."@test.com",
            "phone" => "08563456345",
            "user_id" => $user->id
        ];

        $this->json("POST", "api/v1/contact", $contact, ["Accept" => "application/json"])
            ->assertStatus(201)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "phone",
                "user_id",
                "created_at",
                "updated_at",
            ]);
    }

    /**
     * A basic feature test update.
     */
    public function test_contact_update(): void
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

        $contact = Contact::create([
            "name" => "fahmi",
            "email" => time()."@test.com",
            "phone" => "08563456345",
            "user_id" => $user->id
        ]);

        $update_contact = [
            "name" => "fahmi",
            "email" => time()."@test.com",
            "phone" => "08563456345"
        ];

        $this->json("PUT", "api/v1/contact/".$contact->id, $update_contact, ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "email",
                "phone",
                "user_id",
                "created_at",
                "updated_at",
            ]);
    }

     /**
     * A basic feature test delete.
     */
    public function test_contact_delete(): void
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

        $contact = Contact::create([
            "name" => "fahmi",
            "email" => time()."@test.com",
            "phone" => "08563456345",
            "user_id" => $user->id
        ]);     

        $this->json("DELETE", "api/v1/contact/".$contact->id, ["Accept" => "application/json"])
            ->assertStatus(200);
    }
}
