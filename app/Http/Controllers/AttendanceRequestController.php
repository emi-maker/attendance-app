<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRequest;

class AttendanceRequestController extends Controller
{
  public function index()
    {
        $pendingRequests = AttendanceRequest::where('user_id', auth()->id())
        ->where('status', 0)
        ->get();

        $approvedRequests = AttendanceRequest::where('user_id', auth()->id())
        ->where('status', 1)
        ->get();

        return view('requests.index', compact('pendingRequests', 'approvedRequests'));
    } 
}