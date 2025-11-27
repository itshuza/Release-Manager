<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReleaseNote;
use App\Models\Archive;

class ReleaseNoteController extends Controller
{
    public function index(){ $notes = ReleaseNote::with('archive.project')->latest()->paginate(15); return view('release_notes.index', compact('notes')); }
    public function create(){ $archives = Archive::with('project')->get(); return view('release_notes.create', compact('archives')); }
    public function store(Request $r){
        $r->validate(['version'=>'required','notes'=>'required']);
        ReleaseNote::create([
            'archive_id'=>$r->archive_id?:null,
            'version'=>$r->version,
            'release_date'=>$r->release_date?:null,
            'platforms'=>$r->platforms?:null,
            'notes'=>$r->notes
        ]);
        return redirect()->route('release-notes.index')->with('success','Saved');
    }
    public function show(ReleaseNote $releaseNote){ return view('release_notes.show', compact('releaseNote')); }
    public function edit(ReleaseNote $releaseNote){ $archives = Archive::all(); return view('release_notes.edit', compact('releaseNote','archives')); }
    public function update(Request $r, ReleaseNote $releaseNote){ $r->validate(['version'=>'required','notes'=>'required']); $releaseNote->update($r->only(['archive_id','version','release_date','platforms','notes'])); return redirect()->route('release-notes.index')->with('success','Updated'); }
    public function destroy(ReleaseNote $releaseNote){ $releaseNote->delete(); return back()->with('success','Deleted'); }
}
