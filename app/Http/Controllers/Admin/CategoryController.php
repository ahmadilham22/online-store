<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Category::query();

            return DataTables::of($query)->addColumn('action', function ($item) {
                return '
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">
                            Aksi
                            </button>
                            <div class="dropdown-menu">
                                 <a class="dropdown-item" href="' . route('admin.category.edit', $item->id) . '">Sunting</a>
                                 <form action="' . route('admin.category.destroy', $item->id) . '" method="POST">
                                ' . method_field('delete') . csrf_field() .
                    '
                                 <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                 </from>
                            </div>
                        </div>
                    </div>
                ';
            })->editColumn('photo', function ($item) {
                return $item->photo ? '<img src="' . Storage::url($item->photo) . '" style="max-height: 40px;"/>' : '';
            })->rawColumns(['action', 'photo'])->make();
        }
        return view('pages.admin.category.index');
    }

    public function create()
    {
        return view('pages.admin.category.create');
    }

    public function edit($id)
    {
        $item = Category::findOrFail($id);
        return view('pages.admin.category.edit', [
            'item' => $item
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['photo'] = $request->file('photo')->store('assets/category', 'public');

        Category::create($data);

        return redirect()->route('admin.category.index');
    }

    public function update(CategoryRequest $request, $id)
    {
        // $photo = DB::table('categories')->get('photo');
        $item = Category::findOrFail($id);
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        if ($request->photo) {
            // dd($data['photo']);
            $data['photo'] = $request->file('photo')->store('assets/category', 'public');
        }
        // $data['photo'] = $request->file('photo')->store('assets/category', 'public');

        $item->update($data);

        return redirect()->route('admin.category.index');
    }

    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        Storage::disk('local')->delete('public/' . $item->photo);
        $item->delete();
        return redirect()->route('admin.category.index');
    }
}