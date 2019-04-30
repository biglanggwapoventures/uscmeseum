@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Sales Report</h1>
                <form class="form-inline" method="get" action="{{url()->current()}}">
                    <label for="start-date">Start Date</label>
                    <input type="date" class="form-control mb-2 mr-sm-2" id="start-date" placeholder="Start Date"
                           name="start" value="{{request('start')}}">

                    <label for="end-date">End Date</label>
                    <input type="date" class="form-control mb-2 mr-sm-2" id="end-date" placeholder="End Date" name="end"
                           value="{{request('end')}}">

                    <button type="submit" class="btn btn-primary mb-2">Submit</button>
                </form>
                <div class="card card-body p-0">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Date</th>
                            <th>Item</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Purchase Cost</th>
                            <th class="text-right">Selling Price</th>
                            <th class="text-right">Income</th>
                            <th class="text-right">Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $totalProfit = 0 @endphp
                        @foreach($data as $item)
                            <tr>
                                <td>Order # {{ $item->order_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('m/d/Y h:i A') }}</td>
                                <td>{{ $item->item->name }}</td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right">{{ number_format($item->cost, 2) }}</td>
                                <td class="text-right">{{ number_format($item->selling_price, 2) }}</td>
                                @php
                                    $income = $item->selling_price * $item->quantity;
                                    $cost = $item->cost * $item->quantity;
                                @endphp
                                <td class="text-right">{{ number_format($income, 2) }}</td>
                                <td class="text-right">{{ number_format(($profit = $income - $cost), 2) }}</td>
                                @php $totalProfit +=  $profit; @endphp
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="7" class="text-right font-weight-bold">
                                Total Profit:
                            </td>
                            <td class="text-right font-weight-bold text-success">
                                {{ number_format($totalProfit, 2)  }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection