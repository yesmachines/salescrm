<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CategoryService $categoryService)
    {
        //
        $input = $request->all();
        $categories = $categoryService->getAllCategory($input);

        $parents = $categoryService->categoryArray();

        return view('categories.index', compact('categories', 'parents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  return view('categories.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CategoryService $categoryService)
    {
        //
        // $this->validate($request, [
        //     'name'          => 'required',
        //     'short_code'    => 'required|unique:categories,short_code',
        //     'division'     => 'required',
        //     'icon_url'      => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);
        $input = $request->all();

        $categoryService->createCategory($input);

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CategoryService $categoryService)
    {
        //
        $parents = $categoryService->categoryArray();

        $category = $categoryService->getCategory($id);

        $body = view('categories._edit')
            ->with(compact('category', 'parents'))
            ->render();

        return response()->json(array('success' => true, 'html' => $body));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, CategoryService $categoryService)
    {
        //
        // $this->validate($request, [
        //     'name'          => 'required',
        //     'short_code'    => 'required|unique:categories,short_code,' . $id,
        //     'division'      => 'required',
        //     'icon_url'      => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        // ]);
        $input = $request->all();

        $category = $categoryService->getCategory($id);

        $categoryService->updateCategory($category, $input);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CategoryService $categoryService)
    {
        //
        $categoryService->deleteCategory($id);

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
