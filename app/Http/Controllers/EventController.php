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

    // Image uplaod
    if($req->hasFile('image') && $req->file('image')->isValid()) {
      $reqImage = $req->image;

      $extension = $reqImage->extension();
      $imgName = md5($reqImage->getClientOriginalName() . strtotime('now')) . '.' . $extension;

      $reqImage->move(public_path('img/events'), $imgName);

      $event->image = $imgName;
    }

    $event->save();

    return redirect('/')->with('msg', 'Evento criado com sucesso!');
  }

  public function show($id) {
    $event = Event::findOrFail($id);

    return view('events.show', ['event' => $event]);
  }
}
