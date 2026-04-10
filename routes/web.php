<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Ideas;
use App\Models\Post;
use Illuminate\Http\Request;

Route::view('/', 'welcome', [
    'greeting' => 'Hello, World!',
    'name' => 'John Doe',
    'age' => 30,
    'tasks' => [
        'Learn Laravel',
        'Build a project',
        'Deploy to production',
    ],
]);

Route::view('/about', 'about');
Route::view('/contact', 'contact');

Route::get('/formtest', function(){
    $posts = Post::all();

    return view('formtest',[
        'posts' => $posts,
    ]);
});

Route::post('/formtest', function(){
    Post::create([
        'description' => request('description'),
    ]);

    return redirect('/formtest');
});

Route::delete('/formtest/{id}', function ($id) {
    Post::findOrFail($id)->delete();

    return redirect('/formtest');
});

Route::get('/delete', function(){
    Post::truncate();  

    return redirect('/formtest');
});

Route::get('/emails', function () {
    $emails = session('emails', []);
    return view('emails', compact('emails'));
});

Route::post('/emails', function (Request $request) {
    $request->validate([
        'email' => 'required|email'
    ]);

    $emails = $request->session()->get('emails', []);

    if (count($emails) >= 5) {
        return back()->with('warning', 'Maximum 5 emails reached.');
    }

    if (!in_array($request->email, $emails)) {
        $request->session()->push('emails', $request->email);
        $request->session()->flash('success', 'Email added successfully!');
    } else {
        $request->session()->flash('warning', 'Email already exists.');
    }

    return back();
})->name('emails.store');

Route::delete('/emails/{index}', function (Request $request, $index) {
    $emails = $request->session()->get('emails', []);
    if (isset($emails[$index])) {
        unset($emails[$index]);
        $request->session()->put('emails', array_values($emails));
        $request->session()->flash('success', 'Email deleted.');
    }
    return back();
})->where('index', '[0-9]+');