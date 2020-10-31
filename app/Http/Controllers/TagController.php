<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;


class TagController extends Controller
{
    public function index()
    {
        return Tag::latest()->paginate(5);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required',
        ]);
        return Tag::create([
            'tagName' => $request['tagName'],

        ]);
    }
    public function update(Request $request, $id)
    {
        $tag = Tag::findorFail($id);
        $this->validate($request, [
            'tagName' => 'required',
        ]);
        
        $tag->update($request->all());
        return ['message' => 'update the tag info'];
    }
    public function destroy($id)
    {
        $tag = Tag::findorFail($id);

        //delete the tag
        $tag->delete();
    }

    public function search()
    {

        if ($search = \Request::get('q')) {
            $tags = Tag::where(function ($query) use ($search) {
                $query->where('tagName', 'LIKE', "%$search%");
            })->paginate(20);
        } else {
            $tags = Tag::latest()->paginate(5);
        }
        return $tags;
    }
}
