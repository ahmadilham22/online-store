<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Product::with(['user', 'category']);
            // $query = DB::table('product')->join('user')->join('category')->get();

            return DataTables::of($query)->addColumn('action', function ($item) {
                return '
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">
                            Aksi
                            </button>
                            <div class="dropdown-menu">
                                 <a class="dropdown-item" href="' . route('admin.product.edit', $item->id) . '">Sunting</a>
                                 <form action="' . route('admin.product.destroy', $item->id) . '" method="POST">
                                ' . method_field('delete') . csrf_field() .
                    '
                                 <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                 </from>
                            </div>
                        </div>
                    </div>
                ';
            })->rawColumns(['action', 'photo'])->make();
        }
        return view('pages.admin.product.index');
    }

    public function create()
    {
        $users = User::all();
        $categories = Category::all();
        return view('pages.admin.product.create', [
            'users' => $users,
            'categories' => $categories
        ]);
    }

    public function edit($id)
    {
        $item = Product::findOrFail($id);
        $users = User::all();
        $categories = Category::all();
        return view('pages.admin.product.edit', [
            'item' => $item,
            'users' => $users,
            'categories' => $categories
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        product::create($data);
        return redirect()->route('admin.product.index');
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->all();
        $item = Product::findOrFail($id);
        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('admin.product.index');
    }

    public function destroy($id)
    {
        $item = Product::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.product.index');
    }
}