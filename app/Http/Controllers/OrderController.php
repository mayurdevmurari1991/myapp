<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
	
	// List userspacific orders
	public function userOrders()
	{
		// Fetch orders specific to the authenticated user
		$orders = Order::where('user_id', auth()->id())->with('inventory')->get();

		// Check if the user has any orders
		if ($orders->isEmpty()) {
			return response()->json(['message' => 'No orders found for this user'], 404);
		}

		return response()->json([
			'message' => 'User-specific orders retrieved successfully',
			'orders' => $orders
		], 200);
	}
	
    // Create an order
    public function store(Request $request)
    {
		// Fetch inventory item
		$inventory = Inventory::findOrFail($request->inventory_id);

		// Check stock availability
		if ($request->quantity > $inventory->available_quantity) {
			return response()->json(['message' => 'Not enough stock available'], 422);
		}
		
        // Validate input
        $request->validate([
            'order_number' => 'required|unique:orders',
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,completed,canceled'
        ]);

        // Create the order
        $order = Order::create([
            'order_number' => $request->order_number,
            'inventory_id' => $request->inventory_id,
			'user_id' => auth()->id(),
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);

		Log::log(Auth::id(), 'Created Order', 'Order', $order->id, $order);

        return response()->json($order, 201);
    }

    // Update an order
    public function update(Request $request, $id)
	{
		$order = Order::where('id', $id)
			->where('user_id', auth()->id()) // Check if the order belongs to the authenticated user
			->first();

		// If the order is not found or does not belong to the user, return an error response
		if (!$order) {
			return response()->json(['message' => 'Order not found or you do not have permission to update this order'], 403);
		}

		// Validate request data
		$validatedData = $request->validate([
			'order_number' => 'required|unique:orders',
			'inventory_id' => 'sometimes|exists:inventories,id',
			'quantity' => 'sometimes|integer|min:1',
			'status' => 'sometimes|in:pending,completed,canceled',
		]);

		// Update order details
		$order->update($validatedData);
		
		Log::log(Auth::id(), 'Updated Order', 'Order', $order->id, $request->all());

		return response()->json(['message' => 'Order updated successfully', 'order' => $order], 200);
	}
}
