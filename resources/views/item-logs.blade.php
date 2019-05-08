@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <h1>{{ $item->name }} Logs</h1>
                <h4>Remaining: {{ $item->balance }}</h4>
                <hr>

                <form class="form-inline" method="post" action="{{ url()->current() }}">
                    @csrf
                    @php $maxNegative = $item->balance > 0 ? ($item->balance * -1) : 0 @endphp
                    <div class="form-group mb-2">
                        <label>Adjust Quantity</label>
                        <input type="number" name="quantity" class="form-control mx-2" min="{{$maxNegative}}">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Save</button>
                </form>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th class="text-right">Quantity </th>
                            <th class="text-right">Running <br> Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            /** @var \App\Item $item */
                            $balance = $item->balance;
                        @endphp
                        @foreach($item->logs as $log)
                            <tr>
                                <td>{{ date_create_immutable($log->created_at)->format('F d, Y h:i A') }}</td>
                                <td>{{ $log->reason }}</td>
                                <td class="text-right">{{ number_format($log->quantity) }}</td>
                                <td class="text-right">
                                    @php
                                        $balance -= intval($log->quantity);
                                    @endphp
                                    {{ number_format($loop->first ? $item->balance : $balance) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
