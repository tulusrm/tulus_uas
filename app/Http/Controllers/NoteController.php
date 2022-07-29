<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Auth::user()->notes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
        $note = new Note($validated);
        $note->user_id = Auth::user()->id;
        $note->save();
        return response()->json($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note, $id)
    {
        //
        $note = Note::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (empty($note)){
            abort(404, "Data Tidak Ditemukan");
        }
        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note, $id)
    {
        $note = Note::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (empty($note)){
            abort(404, "Data Tidak Ditemukan");
        }
        $validated = $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
        $note->title = $validated['title'];
        $note->description = $validated['description'];
        $note->save();
        return response()->json($note);

        // $note = Note::find($id);
        // if(!$note) {
        //     return response()->json(['message' => 'Data Not Found'], 404);
        // }

        // $this->validate($request, [
        //     'title' => 'required|max:255',
        //     'description' => 'required',
        // ]);

        // $data = $request->all();
        // $note->fill($data);
        // $note->save();

        // return response()->json($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note, $id)
    {
        //
        $note = Note::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (empty($note)){
            abort(404, "Data Tidak Ditemukan");
        }
        $note->delete();
        return response()->json(['message' => 'Data Terhapus']);
    }
}