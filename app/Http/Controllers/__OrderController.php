<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\QuotationService;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $data = array('status' => ['open', 'partial']);
        // $orders = $orderService->getOrder($data);

        return view('orders.index');
    }

    public function completedOrders(OrderService $orderService)
    {
        //
        // $data = array('status' => ['closed']);
        // $orders = $orderService->getOrder($data);

        return view('orders.completed');
    }

    public function createNewFromQuote($id,  QuotationService $quotationService)
    {
        $quotation = $quotationService->getQuote($id);

        dd($quotation);

        return view('orders.create', compact('quotation'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OrderService $orderService, $id)
    {
        //
        $order = $orderService->getOrderById($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderService $orderService, $id)
    {
        //
        $order = $orderService->getOrderById($id);
        return view('orders.edit', compact('order'));
    }

    public function orderDetailsUpdate(Request $request, OrderService $orderService)
    {
        $request->validate([
            //  'part_no.*' => 'required',
            'emails.*'  => 'required|email',
        ]);

        $input = $request->all();

        // basic information orders
        $orderService->updateOrderDetails($input);

        // delivery details update
        $orderService->updateOrderDeliveryDetails($input);

        return response()->json($input['order_id']);
    }


    public function orderDeliveryDetailsUpdate(Request $request, OrderService $orderService)
    {
        $request->validate([
            'order_delivery_id' => 'required',
            'item.*'            => 'required',
            //  'part_no.*' => 'required',
            'qty.*'             => 'required|numeric',
            'delivered.*'    => 'required'
        ]);
        $input = $request->all();

        $orderService->updateOrderItems($input);

        return response()->json($input['order_delivery_id']);
    }

    public function orderHistoryDetailsInsert(Request $request, OrderService $orderService)
    {
        $input = $request->all();

        $orderService->insertOrderHistoryDetails($input);
        return response()->json($input['order_id']);
    }

    public function orderDataDeliveryHistoryLoad(Request $request, OrderService $orderService)
    {
        $data = $request->all();

        $id = $data['order_delivery_id'];
        $items = $orderService->loadOrderItems($id);

        $isedit = true;
        $data2 = view('orders.partials._items')
            ->with(compact('items'))
            ->render();

        return response()->json(['data' => $data2]);
    }

    public function  viewOrderDataDeliveryHistoryLoad(Request $request, OrderService $orderService)
    {
        $data = $request->all();

        $id = $data['order_id'];
        $order_deliveries = $orderService->loadOrderDeliveryHistoryDetails($id);

        $isedit = false;
        $data2 = view('orders.partials._delivery')
            ->with(compact('order_deliveries', 'isedit'))
            ->render();

        return response()->json(['data' => $data2]);
    }

    public function  orderHistoryLoad(Request $request, OrderService $orderService)
    {
        $data = $request->all();
        $id = $data['order_id'];

        $order = $orderService->getOrderById($id);
        $customer = ($order->customer) ? $order->customer->fullname : '';
        //$orderService->loadOrderHistoryDetails($id);
        $histories = $order->orderHistory;

        $data2 = view('orders.partials._listhistory')
            ->with(compact('histories', 'customer'))
            ->render();


        return response()->json(['data' => $data2]);
    }

    public function orderHistoryDelete(Request $request, OrderService $orderService)
    {
        $data = $request->all();
        $id = $data['order_history_id'];
        $orderService->deleteOrderHistoryDetails($id);
        return response()->json($id);
    }
    public function replyCommentDelete(Request $request, OrderService $orderService)
    {
        $data = $request->all();
        $id = $data['reply_comment_id'];
        $orderService->deleteReplyComments($id);
        return response()->json($id);
    }

    public function deleteOrderItem(Request $request, OrderService $orderService)
    {
        $data = $request->all();
        $id = $data['order_item_id'];
        $orderService->deleteOrderItem($id);
        return response()->json($id);
    }

    public function updateDeliveryByIdHistoryUpdate(Request $request, OrderService $orderService)
    {
        $data = $request->all();
        $orderService->updateOrderDeliveryDetailsByIdUpdate($data);
        return response()->json($data['order_delivery_id']);
    }

    public function commentReplyInsert(Request $request, OrderService $orderService)
    {
        $input = $request->all();
        $orderService->insertCommentReply($input);
        return response()->json($input['order_id']);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
