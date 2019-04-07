@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="d-flex align-items-center">
                    <h1 class="mr-auto mb-0">
                        View Order # {{ $order->id }}
                    </h1>
                    <a class="btn btn-secondary" href="{{ url('orders') }}"><i class="fas fa-chevron-left"></i> Back</a>
                </div>

                <div class="card mt-2">
                    <div class="card-body pb-0">
                        <dl class="row">
                            <dt class="col-sm-3">Customer</dt>
                            <dd class="col-sm-9">{{ $order->customer->fullname }}</dd>

                            <dt class="col-sm-3">Order Date</dt>
                            <dd class="col-sm-9">{{ date_create_immutable($order->created_at)->format('F d, Y h:i A') }}</dd>

                            <dt class="col-sm-3">Order Remarks</dt>
                            <dd class="col-sm-9">{{ $order->remarks ?: '-' }}</dd>

                            <dt class="col-sm-3">Delivery Address</dt>
                            <dd class="col-sm-9">{{ $order->delivery_address  }}</dd>

                            <dt class="col-sm-3">Total Amount</dt>
                            <dd class="col-sm-9">
                                <span class="font-weight-bold text-primary">{{ number_format($order->total_amount, 2)  }}</span>
                            </dd>

                            <dt class="col-sm-3">Status</dt>
                            <dd class="col-sm-9">
                                @if($order->status('approved'))
                                    <span class="badge badge-success"><i class="fas fa-check"></i> {{ ucfirst($order->order_status) }}</span>
                                @else
                                    {{ ucfirst($order->order_status)  }}
                                @endif

                            </dd>
                        </dl>
                    </div>
                    <div class="card-body border-top px-0 pt-0">
                        <table class="table table-striped mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Item</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Ordered Quantity</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td>{{ $detail->item->name }}</td>
                                    <td class="text-right">{{ number_format($detail->selling_price, 2) }}</td>
                                    <td class="text-right">{{ number_format($detail->quantity) }}</td>
                                    <td class="text-right">{{ number_format($detail->amount, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($order->status('pending'))
                        <div class="card-body">
                            <form class="form-inline" method="post" action="{{ url("orders/{$order->id}") }}">
                                @csrf
                                @method('put')
                                <div class="form-group mb-2">
                                    <label for="staticEmail2">Set Status</label>
                                    <select name="order_status" id="order-status" class="form-control mx-2">
                                        <option value="pending"></option>
                                        <option value="approved">Approve</option>
                                        <option value="rejected">Reject</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Confirm</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection