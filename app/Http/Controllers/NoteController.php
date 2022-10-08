<?php

namespace App\Http\Controllers;

use App\Interfaces\Note\NoteInterface as NoteRepository;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * The Note repository instance.
     *
     * @var App\Interfaces\Note\NoteRepository
     */
    protected $noteRepository;

    /**
     * Instantiate a new CategoryController instance.
     */
    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('notes.index')->with('notes', $this->noteRepository->notes());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required',
        ]);

        if ($this->noteRepository->store($request->all())) {
            return to_route('notes.index');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        return view('notes.edit')->with('note', $note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        $request->validate([
            'title' => 'required|max:120',
            'text' => 'required',
        ]);

        if ($this->noteRepository->update($request->all(), $note)) {
            return to_route('notes.show', $note)->with('success', 'Note updated successfully');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if (!$note->user->is(Auth::user())) {
            return abort(403);
        }

        if ($this->noteRepository->delete($note)) {
            return to_route('notes.index')->with('success', 'Note moved to trash');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }
}
