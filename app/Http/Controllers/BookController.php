<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.jwt');
    }

    // Router functions
    public function list(Request $request){
        return response()->json([
            'counts'            => $this->get_book_count($request->all()),
            'books'             => $this->get_books($request->all()),
            'collection_name'   => $this->get_collection_name($request->all())
    	], Response::HTTP_OK);
    }

    public function get(Request $request){
        return response()->json([
            'collection_name'   => $this->get_collection_name($request->only('id')),
            'collection_id'     => $this->get_collection_id($request->only('id')),
            'book'              => $this->retrive($request->only('id'))
    	], Response::HTTP_OK);
    }

    public function add(Request $request){
        return response()->json([
            'result' => $this->create($request->all())
    	], Response::HTTP_CREATED);
    }
    
    public function edit(Request $request){
        return response()->json([
            'result' => $this->update($request->all()) ? "Book updated successfully." : "Book not found."
    	], Response::HTTP_OK);
    }

    public function remove(Request $request){
        return response()->json([
            'result' => $this->delete($request->only('id')) ? "Book deleted successfully." : "Book not found."
    	], Response::HTTP_OK);
    }

    // Main functions
    public function get_book_count($data){
        return $data['collection_id'] > 0 ? DB::table('books')->where('collection_id', $data['collection_id'])->count() : DB::table('books')->leftJoin('collections', 'collections.id', '=', 'books.collection_id')->where('collections.user_id', $data['user_id'])->count();
    }

    public function get_books($data){
        return $data['collection_id'] > 0 ? 
            DB::table('books')
                ->where('collection_id', $data['collection_id'])
                ->orderBy('books.' . $data['sort_by'])
                ->get() : 
            DB::table('books')
                ->leftJoin('collections', 'collections.id', '=', 'books.collection_id')
                ->where('collections.user_id', $data['user_id'])
                ->orderBy('books.' . $data['sort_by'])
                ->select('books.*')
                ->get();
    }

    public function get_collection_name($data){

        if(isset($data['id'])){
            return DB::table('books')
                ->leftJoin('collections', 'collections.id', '=', 'books.collection_id')
                ->where('books.id', $data['id'])
                ->select('collections.name')
                ->first()->name;
        }else{
            if($data['collection_id'] > 0){
                return DB::table('collections')
                ->where('collections.id', $data['collection_id'])
                ->select('collections.name')
                ->first()->name;
            }else{
                return 'All books';
            }
        }
    }

    public function get_collection_id($data){
        return DB::table('books')
                ->leftJoin('collections', 'collections.id', '=', 'books.collection_id')
                ->where('books.id', $data['id'])
                ->select('collections.id')
                ->first()->id;
    }

    // CRUD functions
    public function create($data){
        // The book table has no duplication restriction. So it is not needed try-catch statement.
        return DB::table('books')->insert([
            'collection_id'     => $data['collection_id'],
            'title'             => $data['title'],
            'author'            => $data['author'],
            'editor'            => $data['editor'],
            'publisher'         => $data['publisher'],
            'publication_date'  => $data['publication_date'],
            'description'       => $data['description'],
            'reference'         => $data['reference'],
            'pages'             => $data['pages'],
            'cover'             => $data['cover'],
            'isbn'              => $data['isbn'],
            'volumn'            => $data['volumn'],
            'note'              => $data['note'],
            'category'          => $data['category'],
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
    }

    public function retrive($data){
        return DB::table('books')->where('id', $data['id'])->first();
    }

    public function update($data){
        return DB::table('books')->where('id', $data['id'])->update([
            'collection_id'     => $data['collection_id'],
            'title'             => $data['title'],
            'author'            => $data['author'],
            'editor'            => $data['editor'],
            'publisher'         => $data['publisher'],
            'publication_date'  => $data['publication_date'],
            'description'       => $data['description'],
            'reference'         => $data['reference'],
            'cover'             => $data['cover'],
            'pages'             => $data['pages'],
            'isbn'              => $data['isbn'],
            'volumn'            => $data['volumn'],
            'category'          => $data['category'],
            'note'              => $data['note'],
            'updated_at'        => Carbon::now()
        ]);
    }

    public function delete($data){
        return DB::table('books')
                ->where('id', $data['id'])
                ->delete();
    }
}
