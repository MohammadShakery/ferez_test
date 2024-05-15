<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;



class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::all();
        $users = User::all();
        $list_ticket = [];
        $collection = collect($users);
        foreach($tickets as $ticket){
            $id = $ticket['id'];
            $result = $collection->find($id);
            $text = [
                'user_id' => $result['id'],
                'user_name' => $result['name'],
                'user_phone' => $result['phone'],
                'ticket_id' => $ticket['id'],
                'ticket_title' => $ticket['title'],
                'description' => $ticket['description'],
            ];
            array_push($list_ticket,$text);
        };
        return response([
            'status' => true ,
            'tickets' => $list_ticket
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Requset $request)
    {
        $ticket = new Ticket();
        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->user_id = $request->user_id;
        $ticket->save();
         
        return ['status'=>200,'respons'=>True];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket-delete();
    }
}
