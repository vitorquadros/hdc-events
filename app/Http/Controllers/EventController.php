<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;


class EventController extends Controller
{
  public function index() {
    $events = Event::all();

    return view('welcome', ['events' => $events]);
  }

  public function create() {
    return view('events.create');
  }

  public function store(Request $req) {
    $event = new Event;

    $event->title = $req->title;
    $event->city = $req->city;
    $event->private = $req->private;
    $event->description = $req->description;

    $event->save();

    return redirect('/');
  }
}
