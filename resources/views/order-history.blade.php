@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Order History</h1>
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table mb-0 table-striped table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Delivery Address</th>
                                <th>Remarks</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer->fullname }}</td>
                                    <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $order->delivery_address }}</td>
                                    <td>{{ $order->remarks }}</td>
                                    <td class="text-right">{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-center">
                                        @if($order->order_status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($order->order_status === 'approved')
                                            <span class="badge badge-success"><i class="fas fa-check"></i> Approved</span>
                                        @elseif(in_array($order->order_status, ['rejected', 'cancelled']))
                                            <span class="badge badge-danger"><i class="fas fa-times"></i> {{  ucfirst($order->order_status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-outline-info btn-sm" href="{{ url("orders/{$order->id}") }}">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection