<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = DB::table('users')->get();

            return DataTables::of($query)->addColumn('action', function ($item) {
                return '
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">
                            Aksi
                            </button>
                            <div class="dropdown-menu">
                                 <a class="dropdown-item" href="' . route('admin.user.edit', $item->id) . '">Sunting</a>
                                 <form action="' . route('admin.user.destroy', $item->id) . '" method="POST">
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
        return view('pages.admin.user.index');
    }

    public function create()
    {
        return view('pages.admin.user.create');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);
        return view('pages.admin.user.edit', [
            'item' => $item
        ]);
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        user::create($data);
        return redirect()->route('admin.user.index');
    }

    public function update(UserRequest $request, $id)
    {
        $data = $request->all();
        $item = User::findOrFail($id);
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $item->update($data);

        return redirect()->route('admin.user.index');
    }

    public function destroy($id)
    {
        $item = User::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.user.index');
    }
}