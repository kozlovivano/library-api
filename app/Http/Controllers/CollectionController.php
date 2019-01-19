<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class CollectionController extends Controller
{
    // Check whether the request has JWT token
    public function __construct()
    {
        $this->middleware('auth.jwt');
    }

    // Router functions
    public function list(Request $request){
        return response()->json([
            'count'         => $this->get_collection_count($request->only('user_id')),
            'books'         => $this->get_book_count($request->only('user_id')),
            'collections'   => $this->get_collection_infor($request->only('user_id'))
    	], Response::HTTP_OK);
    }

    public function get(Request $request){
        return response()->json([
            'collection' => $this->retrive($request->only('id'))
    	], Response::HTTP_OK);
    }

    public function add(Request $request){
        return response()->json([
            'result' => $this->create($request->only('user_id', 'name', 'reference')) ? "Collection created successfully." : "Duplicate collection name."
    	], Response::HTTP_CREATED);
    }

    public function edit(Request $request){
        return response()->json([
            'result' => $this->update($request->only('id', 'name', 'reference')) ? "Collection updated successfully." : "Collection not found."
    	], Response::HTTP_OK);
    }

    public function remove(Request $request){
        return response()->json([
            'result' => $this->delete($request->only('id')) ? "Collection deleted successfully." : "Collection not found."
    	], Response::HTTP_OK);
    }


    // Main funcitons
    public function get_book_count($data){
        return DB::table('books')
                    ->leftJoin('collections', 'collections.id', '=', 'books.collection_id')
                    ->leftJoin('users', 'users.id', '=', 'collections.user_id')
                    ->where('users.id', $data['user_id'])
                    ->count('books.id');
    }

    public function get_collection_infor($data){
        return DB::table('collections')
                ->leftJoin('books', 'books.collection_id', '=', 'collections.id')
                ->where('collections.user_id', $data['user_id'])
                ->select('collections.*', DB::raw('count(books.id) as books'))
                ->groupBy('collections.id')
                ->get();
    }

    public function get_collection_count($data){
        return DB::table('collections')->where('user_id', $data['user_id'])->count();
    }

    // CRUD functions
    public function create($data){
        try{
            DB::table('collections')->insert([
                'name'          => $data['name'],
                'reference'     => $data['reference'],
                'user_id'       => $data['user_id'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }catch(\Illuminate\Database\QueryException $e){
            return false; // Duplicate collection name
        }catch (\Exception $e) {
            return false;
        }   
        return true;
    }

    public function retrive($data){
        return DB::table('collections')->where('id', $data['id'])->first();
    }

    public function update($data){
        try{
            return DB::table('collections')
                ->where('id', $data['id'])
                ->update([
                    'name'      => $data['name'],
                    'reference' => $data['reference'],
                    'updated_at'    => Carbon::now()
            ]);
        }catch(\Illuminate\Database\QueryException $e){
            return false;
        }catch (\Exception $e) {
            return false;
        }   
    }

    public function delete($data){
        return DB::table('collections')
                ->where('id', $data['id'])
                ->delete();
    }
}
