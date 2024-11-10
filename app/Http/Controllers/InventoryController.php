<?php
namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    // List all inventory items
    public function index()
    {
        $inventories = Inventory::all();
        return response()->json(['data' => $inventories], 200);
    }

    // Store a new inventory item
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:150',
            'quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Create inventory item
        $inventory = Inventory::create([
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'threshold' => $request->threshold,
        ]);

        return response()->json(['message' => 'Inventory item created successfully', 'data' => $inventory], 201);
    }

    // Update an inventory item
    public function update(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory item not found'], 404);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'product_name' => 'string|max:150',
            'quantity' => 'integer|min:0',
            'threshold' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Update inventory item
        $inventory->update($request->only(['product_name', 'quantity', 'threshold']));

        // Check if stock level is below the threshold
        if ($inventory->quantity < $inventory->threshold) {
            // Trigger notification (To be implemented later in Notification Service)
        }

        return response()->json(['message' => 'Inventory item updated successfully', 'data' => $inventory], 200);
    }

    // Soft delete an inventory item
    public function destroy($id)
    {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['message' => 'Inventory item not found'], 404);
        }

        $inventory->delete();
        return response()->json(['message' => 'Inventory item deleted successfully'], 200);
    }
}
