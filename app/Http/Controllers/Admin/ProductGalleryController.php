<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\ProductGalleryRequest;
use App\Models\Category;
use App\Models\ProductGallery;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductGalleryController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = ProductGallery::with(['product']);
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
                                 <form action="' . route('admin.product-gallery.destroy', $item->id) . '" method="POST">
                                ' . method_field('delete') . csrf_field() .
                    '
                                 <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                 </from>
                            </div>
                        </div>
                    </div>
                ';
            })->editColumn('photos', function ($item) {
                return $item->photos ? '<img src="' . Storage::url($item->photos) . '" style="max-height: 80px;"/>' : '';
            })->rawColumns(['action', 'photos'])->make();
        }
        return view('pages.admin.product-gallery.index');
    }

    public function create()
    {
        $products = Product::all();
        return view('pages.admin.product-gallery.create', [
            'products' => $products,
        ]);
    }

    public function store(ProductGalleryRequest $request)
    {
        $data = $request->all();
        $data['photos'] = $request->file('photos')->store('assets/product', 'public');
        ProductGallery::create($data);
        return redirect()->route('admin.product-gallery.index');
    }

    public function destroy($id)
    {
        $item = ProductGallery::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.product-gallery.index');
    }
}