<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BoardResource;
use DB;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        //  fetch all boards based on current user id
        $boards = Board::all()->where('user_id', $user_id);

        //  return boards as a resource
        return BoardResource::collection($boards);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user_id)
    {
        //  store new board in the database
        $board = new Board;
        $board->user_id = $user_id;
        $board->name = $request->input('name');

        if ($board->save()) {
            //  if saved then return board as a resource
            return new BoardResource($board);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show($id, $user_id)
    {
        //  fetch board based on current user id and board id
        $board = Board::where('user_id', $user_id)->FindOrFail($id);

        //  return board as a resource
        return new BoardResource($board);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        //  update existing board data based on current user id and board id
        $board = Board::where('user_id', $user_id)->FindOrFail($request->board_id);
        $board->user_id = $user_id;
        $board->name = $request->input('name');

        if ($board->save()) {
            //  if saved then return board as a resource
            return new BoardResource($board);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $user_id)
    {
        //  fetch board based on current user id and board id
        $board = Board::where('user_id', $user_id)->FindOrFail($id);

        if ($board->delete()) {
            //  id deleted then return board as a resource
            return new BoardResource($board);
        }
    }

    /**
     * Dump Db.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function dumpDB(Request $request)
    {
        /*
        Needed in SQL File:

        SET GLOBAL sql_mode = '';
        SET SESSION sql_mode = '';
        */
        $get_all_table_query = "SHOW TABLES";
        $result = DB::select(DB::raw($get_all_table_query));

        $tables = [
            "boards","tasks",
        ];

        $structure = '';
        $data = '';
        foreach ($tables as $table) {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";

            $show_table_result = DB::select(DB::raw($show_table_query));

            foreach ($show_table_result as $show_table_row) {
                $show_table_row = (array)$show_table_row;
                $structure .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table;
            $records = DB::select(DB::raw($select_query));

            foreach ($records as $record) {
                $record = (array)$record;
                $table_column_array = array_keys($record);
                foreach ($table_column_array as $key => $name) {
                    $table_column_array[$key] = '`' . $table_column_array[$key] . '`';
                }

                $table_value_array = array_values($record);
                $data .= "\nINSERT INTO $table (";

                $data .= "" . implode(", ", $table_column_array) . ") VALUES \n";

                foreach($table_value_array as $key => $record_column)
                    $table_value_array[$key] = addslashes($record_column);

                $data .= "('" . implode("','", $table_value_array) . "');\n";
            }
        }
        $file_name = __DIR__ . '/../public/dump.sql';
        $file_handle = fopen($file_name, 'w + ');

        $output = $structure . $data;
        fwrite($file_handle, $output);
        fclose($file_handle);
        
        return [
            'd_url' => 'http://kanban.totalncare.com/dump.sql'
        ];

    }
}
