<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;


class EventController extends Controller
{
  public function index() {
    $search = request('search');

    if ($search) {
      $events = Event::where([
        ['title', 'like', '%'.$search.'%']
      ])->get();
    } else {
      $events = Event::all();
    }

    return view('welcome', ['events' => $events, 'search' => $search]);
  }

  public function create() {
    return view('events.create');
  }

  public function store(Request $req) {
    $event = new Event;

    $event->title = $req->title;
    $event->date = $req->date;
    $event->city = $req->city;
    $event->private = $req->private;
    $event->description = $req->description;
    $event->items = $req->items;

    // Image uplaod
    if($req->hasFile('image') && $req->file('image')->isValid()) {
      $reqImage = $req->image;

      $extension = $reqImage->extension();
      $imgName = md5($reqImage->getClientOriginalName() . strtotime('now')) . '.' . $extension;

      $reqImage->move(public_path('img/events'), $imgName);

      $event->image = $imgName;
    }

    $user = auth()->user();
    $event->user_id = $user->id;

    $event->save();

    return redirect('/')->with('msg', 'Evento criado com sucesso!');
  }

  public function show($id) {
    $event = Event::findOrFail($id);

    $eventOwner = User::where('id', $event->user_id)->first()->toArray();

    return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner]);
  }

  public function dashboard() {
    $user = auth()->user();

    $events = $user->events;

    $eventsAsParticipants = $user->eventsAsParticipant;

    return view('events.dashboard', ['events' => $events, 
    'eventsasparticipant' => $eventsAsParticipants
    ]);
  }

  public function destroy($id) {
    Event::findOrFail($id)->delete();

    return redirect('/dashboard')->with('msg', 'Evento excluido com sucesso!');
  }

  public function edit($id) {
    $event = Event::findOrFail($id);

    return view('events.edit', ['event' => $event]);
  }

  public function update(Request $req) {
    $data = $req->all();

    if($req->hasFile('image') && $req->file('image')->isValid()) {
      $reqImage = $req->image;

      $extension = $reqImage->extension();
      $imgName = md5($reqImage->getClientOriginalName() . strtotime('now')) . '.' . $extension;

      $reqImage->move(public_path('img/events'), $imgName);

      $data['image'] = $imgName;
    }

    Event::findOrFail($req->id)->update($data);

    return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
  }

  public function joinEvent($id) {
    $user = auth()->user();

    $user->eventsAsParticipant()->attach($id);

    $event = Event::findOrFail($id);

    return redirect('/dashboard')->with('msg', 'Sua presença foi confirmada no evento ' . $event->title . '!');
  }
}
