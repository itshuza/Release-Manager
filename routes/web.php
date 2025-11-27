<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ReleaseNoteController;
use Illuminate\Support\Facades\Auth;

Auth::routes(); // registers login, register, password reset routes, etc.


 Route::middleware('auth')->group(function(){
     Route::get('/', [ArchiveController::class, 'index'])->name('archives.index');
    //  Route::get('/archives', [ArchiveController::class, 'index'])->name('archives.index');
     Route::get('/archives/create', [ArchiveController::class,'create'])->name('archives.create');
     Route::post('/archives', [ArchiveController::class,'store'])->name('archives.store');
     Route::get('/archives/{archive}/download', [ArchiveController::class,'download'])->name('archives.download');
    Route::resource('release-notes', ReleaseNoteController::class);
  

});

