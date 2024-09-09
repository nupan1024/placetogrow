<?php

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\MicrositeType;
use App\Domain\Users\Models\User;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Permissions;
use App\Support\Definitions\Roles;
use Database\Factories\PermissionFactory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

test('store microsite success', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));
    Sanctum::actingAs($user);

    $category = Category::factory()->create();
    $currency = Currency::factory()->create();
    $type = MicrositeType::factory()->create([
        'name' => MicrositesTypes::DONATIONS->name,
    ]);

    $date = fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d');
    $description = fake()->paragraph();
    $name = fake()->name();
    $status = fake()->boolean() ? 1 : 0;

    $data = [
        'category_id' => $category->id,
        'name' => $name,
        'description' => $description,
        'logo_path' => UploadedFile::fake()->image('microsite_image.png', 640, 480),
        'currency_id' =>  $currency->id,
        'microsites_type_id' =>  $type->id,
        'date_expire_pay' => $date,
        'status' => $status,
    ];

    $response = $this->post(route("microsite.store"), $data);

    $response->assertStatus(302);

    $response->assertRedirect(route('microsites'));

    $response->assertSessionHas('message', __('microsites.success_create'));
    $response->assertSessionHas('type', 'success');

    $this->assertDatabaseHas('microsites', [
        'category_id' => $category->id,
        'name' => $name,
        'description' => $description,
        'currency_id' =>  $currency->id,
        'microsites_type_id' =>  $type->id,
        'status' => $status,
    ]);
});

test('not store microsite because not receive every param', function () {
    PermissionFactory::new()->createMany(Permissions::toArray());

    $role = new Role();
    $role->name = Roles::SUPER_ADMIN->name;
    $role->syncPermissions(Permissions::getPermissions());
    $role->save();

    $user = User::factory()->create([
        'role_id' => $role->id,
    ]);
    $user->assignRole(Role::findById($role->id));
    Sanctum::actingAs($user);

    $category = Category::factory()->create();
    $currency = Currency::factory()->create();
    $type = MicrositeType::factory()->create();

    $date = fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d');
    $description = fake()->paragraph();
    $name = fake()->name();

    $data = [
        'category_id' => $category->id,
        'name' => $name,
        'description' => $description,
        'logo_path' => UploadedFile::fake()->image('microsite_image.png', 640, 480),
        'currency_id' =>  $currency->id,
        'microsites_type_id' =>  $type->id,
        'date_expire_pay' => $date,
    ];

    $response = $this->post(route("microsite.store"), $data);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['status']);

    $this->assertDatabaseMissing('microsites', [
        'category_id' => $category->id,
        'name' => $name,
        'description' => $description,
        'logo_path' => UploadedFile::fake()->image('microsite_image.png', 640, 480),
        'currency_id' =>  $currency->id,
        'microsites_type_id' =>  $type->id,
        'date_expire_pay' => $date,
    ]);
});
