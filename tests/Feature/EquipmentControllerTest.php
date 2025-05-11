<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Equipments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_equipment_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Equipments::factory()->count(2)->create();

        $response = $this->get('/admin/equipment'); 
        $response->assertStatus(200);
        $response->assertViewHas('equipments');
    }

    public function test_admin_can_store_equipment_for_customer()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($admin);

        $response = $this->post('/admin/equipment/store', [ 
            'name' => 'Laptop',
            'description' => 'Battery issue',
            'status' => 'pending',
            'customer_id' => $customer->id,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('equipments', [
            'name' => 'Laptop',
            'customer_id' => $customer->id,
        ]);
    }

    public function test_customer_can_store_own_equipment()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer);

        $response = $this->post('/customer/equipment/store', [ 
            'name' => 'Printer',
            'description' => 'Paper jam',
            'status' => 'pending',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertDatabaseHas('equipments', [
            'name' => 'Printer',
            'customer_id' => $customer->id,
        ]);
    }

    public function test_admin_store_validation_fails_without_customer_id()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->post('/admin/equipment/store', [
            'name' => 'Router',
            'description' => 'No power',
            'status' => 'pending',
            // Missing 'customer_id'
        ]);

        $response->assertSessionHasErrors('customer_id');
    }
}
