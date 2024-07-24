<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get a list of orders",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of orders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Order")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $orders = Order::with('items')->get();
        return response()->json($orders);
    }

    /**
     * @OA\Post(
     *     path="/api//orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'items' => 'required|array'
        ]);

        $order = Order::create([
            'user_id' => $validatedData['user_id'],
            'total_amount' => $validatedData['total_amount'],
            'status' => 'pending'
        ]);

        foreach ($validatedData['items'] as $item) {
            $order->items()->create($item);
        }

        return response()->json($order->load('items'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api//orders/{id}",
     *     summary="Get an order by ID",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function show(string $id)
    {
        $order = Order::with('items')->findOrFail($id);
        return response()->json($order);
    }

    /**
     * @OA\Put(
     *     path="/api//orders/{id}",
     *     summary="Update an existing order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'status' => 'required|string'
        ]);

        $order->update($validatedData);
        return response()->json($order->load('items'));
    }

    /**
     * @OA\Delete(
     *     path="/api//orders/{id}",
     *     summary="Delete an order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete(); // Elimina los artículos relacionados con el pedido
        $order->delete(); // Elimina el pedido
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
