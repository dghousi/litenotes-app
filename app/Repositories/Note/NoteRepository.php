<?php

namespace App\Repositories\Note;

use App\Interfaces\Note\NoteInterface;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NoteRepository implements NoteInterface
{
    public function notes()
    {
        return Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(env('PAGINATION_DEFAULT_VALUE'));
    }

    public function store($request)
    {
        FacadesDB::beginTransaction();

        $note = '';

        try {
            $note = Auth::user()->notes()->create($this->mapRequest($request, null));

            FacadesDB::commit();

            return $note;
        } catch (\Exception$e) {
            Log::error($e);

            FacadesDB::rollback();

            return $note;
        }
    }

    public function update($request, $note)
    {
        FacadesDB::beginTransaction();

        $updatedNote = '';

        try {
            $updatedNote = $note->update($this->mapRequest($request, $note));

            FacadesDB::commit();

            return $updatedNote;
        } catch (\Exception$e) {
            Log::error($e);

            FacadesDB::rollback();

            return $updatedNote;
        }
    }

    /**
     * Map Board Store Request.
     *
     * @param $request
     *
     * @return array
     */
    private function mapRequest($request, $note)
    {
        return [
            'uuid' => $note === null ? Str::uuid() : $note->uuid,
            'title' => $request['title'],
            'text' => $request['text'],
        ];
    }

    /**
     * Delete monument.
     *
     * @param $note
     *
     * @return /Response
     */
    public function delete($note)
    {
        return $note->delete();
    }
}
