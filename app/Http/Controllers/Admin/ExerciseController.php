<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        $data = Exercise::all();
        return view('admin.exercise.index', compact('data'));
    }

    public function create()
    {
        return view('admin.exercise.create');
    }

    public function store(Request $request)
    {
        Exercise::create($request->all());
        return redirect('/admin/exercise');
    }

    public function edit($id)
    {
        $data = Exercise::find($id);
        return view('admin.exercise.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        Exercise::find($id)->update($request->all());
        return redirect('/admin/exercise');
    }

    public function destroy($id)
    {
        Exercise::destroy($id);
        return back();
    }
}