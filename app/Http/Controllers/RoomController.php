<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('layout.app', compact('rooms'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|unique:rooms,name|max:255',
        ]);

        // Simpan data ke database
        $room = Room::create($validated);

        $sensorTypes = ["dht22", "mq7", "mq135", "dust"];
        $topics = [];

        foreach ($sensorTypes as $sensor) {
            $topic = "room/$room -> id/$sensor";
            $topics[] = $topic;
            Log::info("Topic Created " . $topic);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Room added successfully.');
    }

    public function show(Room $room) {
        return Livewire::mount('components.room-show', [
            'roomId' => $room -> id
        ]);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->back()->with('success', 'Room deleted successfully.');
    }
}
