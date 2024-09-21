<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trip;
use App\Models\Bus;
use App\Http\Controllers\TripBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class TripBookingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new TripBooking();
    }

    public function test_validation_errors()
{
    $user = User::factory()->create(['id' => 1]);
    $trip = Trip::factory()->create(['id' => 1]);

    $request = Request::create('/book-trip', 'POST', [
        'user_id' => $user->id,
        'trip_id' => $trip->id,
    ]);
    $request->headers->set('Authorization', 'Bearer token');

    try {
        $this->controller->bookTrip($request);
    } catch (\Illuminate\Validation\ValidationException $e) {
        $errors = $e->errors();
        $this->assertArrayHasKey('seat_number', $errors);
        $this->assertEquals('The seat number field is required.', $errors['seat_number'][0]);
    }
}

    public function test_unauthorized_access()
    {
        $user = User::factory()->create();
        $trip = Trip::factory()->create();
        $request = Request::create('/book-trip', 'POST', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'seat_number' => 1
        ]);
        $response = $this->controller->bookTrip($request);
        $this->assertEquals(401, $response->status());
    }

    public function test_trip_already_booked()
    {
        $user = User::factory()->create(['trips_history' => json_encode([['trip_id' => 1]])]);
        $trip = Trip::factory()->create(['id' => 1]);
        $request = Request::create('/book-trip', 'POST', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'seat_number' => 1
        ]);
        $request->headers->set('Authorization', 'Bearer token');
        $response = $this->controller->bookTrip($request);
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Trip already booked', $response->getData()->message);
    }

    public function test_seat_already_occupied()
    {
        $user = User::factory()->create(['trips_history' => json_encode([])]);
        $trip = Trip::factory()->create();
        $bus = Bus::factory()->create(['seats' => json_encode([['seat_number' => 1, 'status' => 'occupied']])]);
        $trip->bus_id = $bus->id;
        $trip->save();

        $request = Request::create('/book-trip', 'POST', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'seat_number' => 1
        ]);
        $request->headers->set('Authorization', 'Bearer token');
        $response = $this->controller->bookTrip($request);
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Seat is already occupied', $response->getData()->message);
    }

    public function test_successful_trip_booking()
    {
        $user = User::factory()->create(['trips_history' => json_encode([])]);
        $trip = Trip::factory()->create();
        $bus = Bus::factory()->create(['seats' => json_encode([['seat_number' => 1, 'status' => 'available']])]);
        $trip->bus_id = $bus->id;
        $trip->save();

        $request = Request::create('/book-trip', 'POST', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'seat_number' => 1
        ]);
        $request->headers->set('Authorization', 'Bearer token');
        $response = $this->controller->bookTrip($request);
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Trip assigned successfully', $response->getData()->message);
    }
}