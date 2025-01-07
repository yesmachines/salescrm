<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($slug) {
        $page = Page::whereSlug($slug)->first();
        return view('pages.edit', compact('page'));
    }

    public function update(Request $request, $id) {
        $page = Page::findOrFail($id);
        $page->title = $request->title;
        $page->content = $request->content;
        $page->save();
       return redirect()->back()->with('success', 'Updated successfully');
    }
}
