<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use DB;

class CategoryService
{
    public function getAllCategory($data = []): Object
    {
        // $query = DB::table('categories as child')
        //     ->leftJoin('categories as parent', 'child.parent_id', '=', 'parent.id')
        //     ->select('child.*', 'parent.name as parent');

        // $categories = $query->orderBy('child.name', 'DESC')->get();

        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();

        return $categories;
    }

    public function getCategory($id): Object
    {
        return Category::find($id);
    }

    public function categoryArray()
    {
        return Category::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }

    public function createCategory(array $userData): Category
    {
        return Category::create([
            'name'        => $userData['name'],
            'description' => $userData['description'],
            'parent_id'   => $userData['parent_id']
        ]);
    }

    public function deleteCategory($id): void
    {
        // delete user
        $category = Category::find($id);
        $category->delete();
    }

    public function updateCategory(Category $category, array $userData): void
    {
        $update = [];

        if (isset($userData['name']) && !empty($userData['name'])) {
            $update['name'] = $userData['name'];
        }
        if (isset($userData['description']) && !empty($userData['description'])) {
            $update['description'] = $userData['description'];
        }
        if (isset($userData['parent_id']) && !empty($userData['parent_id'])) {
            $update['parent_id'] = $userData['parent_id'];
        }

        $category->update($update);
    }
}
