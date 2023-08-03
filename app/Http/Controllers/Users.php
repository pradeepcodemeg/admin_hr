<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class Users extends Controller
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->status)) {
                $data = DB::table('users')
                    ->select('id', 'image', 'firstname', 'lastname', 'role', 'email', 'status', 'created_at', 'updated_at')
                    ->where('status', $request->status)
                    ->get();
            } else {
                $data = DB::table('users')
                    ->select('id', 'image', 'firstname', 'lastname', 'role', 'email', 'status', 'created_at', 'updated_at')
                    ->get();
            }


            return DataTables::of($data)
                ->addColumn('image', function ($data) {
                    $image = '<img id="preview-foto" class="preview2" src="public' . $data->image . '"/>';
                    return $image;
                })
                ->addColumn('edit', function ($data) {
                    $button = ' <p data-placement="top" data-toggle="tooltip" title="Edit" class="p-top">
                            <a href="edit-user/' . $data->id . '">
                            <button type="submit" class="btn btn-primary btn-xs data-title=" Edit">
                                <span class="glyphicon glyphicon-pencil"></span></button>
                                                    </a>
                        </p>';
                    return $button;
                })
                ->addColumn('delete', function ($data) {
                    if ($data->role != "Admin") {
                        $button = '<p data-placement="top" data-toggle="tooltip" title="Delete">
                                    <button class="btn btn-danger btn-xs top"
                                        onclick="deleteUser(' . $data->id . ')" data-title="Delete" data-toggle="modal"
                                        data-target="#delete" readonly>
                                    <span class="glyphicon glyphicon-trash"></span></button>
                                                    </p>';
                    } else {
                        $button = '<p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs top disabled" data-title="Delete" data-toggle="modal">
                                <span class="glyphicon glyphicon-trash"></span></button>
                                                </p>';
                    }
                    return $button;
                })
                ->rawColumns(['image', 'edit', 'delete'])
                ->make(true);
        }
        return view('backend/user-management');
    }
}
