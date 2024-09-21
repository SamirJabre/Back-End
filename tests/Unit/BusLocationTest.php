<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Bus;
use Illuminate\Http\Request;
use App\Http\Controllers\BusLocationController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class BusLocationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUpdateLocation()
    {
        $bus = Bus::factory()->create();

        $request = Request::create('/update-location', 'POST', [
            'busId' => $bus->id,
            'current_latitude' => $this->faker->latitude,
            'current_longitude' => $this->faker->longitude,
        ]);

        $controller = new BusLocationController();

        $response = $controller->updateLocation($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Location updated successfully', $response->getData()->message);

        $bus->refresh();
        $this->assertEquals($request->current_latitude, $bus->current_latitude);
        $this->assertEquals($request->current_longitude, $bus->current_longitude);
    }

    public function testUpdateLocationWithInvalidData()
    {
        $request = Request::create('/update-location', 'POST', [
            'busId' => 'invalid',
            'current_latitude' => 'invalid',
            'current_longitude' => 'invalid',
        ]);

        $controller = new BusLocationController();

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->updateLocation($request);
    }

    public function testUpdateLocationBusNotFound()
    {
        $request = Request::create('/update-location', 'POST', [
            'busId' => 9999,
            'current_latitude' => $this->faker->latitude,
            'current_longitude' => $this->faker->longitude,
        ]);

        $controller = new BusLocationController();

        $response = $controller->updateLocation($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Bus not found', $response->getData()->message);
    }

    public function testGetLocation()
    {
        $bus = Bus::factory()->create();

        $request = Request::create('/get-location', 'GET', [
            'busId' => $bus->id,
        ]);
        $request->headers->set('Authorization', 'Bearer fake-token');

        $controller = new BusLocationController();

        $response = $controller->getLocation($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($bus->current_latitude, $response->getData()->current_latitude);
        $this->assertEquals($bus->current_longitude, $response->getData()->current_longitude);
    }

    public function testGetLocationUnauthorized()
    {
        $request = Request::create('/get-location', 'GET', [
            'busId' => 1,
        ]);
        $controller = new BusLocationController();
        $response = $controller->getLocation($request);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Unauthorized', $response->getData()->error);
    }

    public function testGetLocationBusNotFound()
    {
        $request = Request::create('/get-location', 'GET', [
            'busId' => 9999,
        ]);
        $request->headers->set('Authorization', 'Bearer fake-token');
        $controller = new BusLocationController();
        $response = $controller->getLocation($request);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Bus not found', $response->getData()->message);
    }
}