<?php

namespace App\Interfaces\Note;

interface NoteInterface
{
    public function notes();

    public function store($request);

    public function update($request, $note);

    public function delete($note);
}
