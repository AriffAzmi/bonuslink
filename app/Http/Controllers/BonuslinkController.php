<?php

namespace App\Http\Controllers;

use Auth,DB;
use App\Point;
use Illuminate\Http\Request;

class BonuslinkController extends Controller
{
    public function showBalance(Request $request)
    {
    	try {
	    	$balance = Point::where('user_id',$request->user_id)
	    	->where('is_void',0)
	    	->sum('amount');

	    	return response()->json([
	    		'status' => true,
	    		'message' => 'Points balance successfully retrieve.',
	    		'data' => $balance
	    	]);

    	} catch (\Exception $e) {
    		
    		return response()->json([
	    		'status' => false,
	    		'message' => 'Failed to retreive points.'
	    	],404);
    	}
    }

    public function addPoints(Request $request)
    {
    	try {
    		
    		$points = Point::create([
    			'user_id' => $request->user_id,
    			'amount' => $request->amount
    		]);

    		$balance = Point::where('user_id',$request->user_id)
	    	->firstOrFail();

	    	return response()->json([
	    		'status' => true,
	    		'message' => 'Points balance successfully retrieve.',
	    		'data' => $balance
	    	]);

    	} catch (\Exception $e) {
    		
    		return response()->json([
	    		'status' => false,
	    		'message' => 'Failed to create new point record.',
	    		'_e' => $e->getMessage()
	    	],404);
    	}
    }

    public function voidPoints(Request $request)
    {
    	try {
    		
	    	$points = Point::where('user_id',$request->user_id)
	    	->where('id',$request->point_id)
	    	->firstOrFail();
	    	$points->is_void = 1;
	    	$points->void_at = date('Y-m-d H:i:s');
	    	$points->save();

	    	return response()->json([
	    		'status' => true,
	    		'message' => 'Points balance successfully void.',
	    	]);

    	} catch (\Exception $e) {
    		
    		return response()->json([
	    		'status' => false,
	    		'message' => 'No points record found.'
	    	],404);
    	}
    }
}
