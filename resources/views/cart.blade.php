@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Cart</h1>
                <div class="card">
                    <div class="card-body p-0 table-responsive">
                        <table class="table mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Item</th>
                                <th class="text-right">Unit Price</th>
                                <th style="width:20%">Quantity</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($contents as $item)
                                <tr>
                                    <td>{{ $item['product']['name'] }}</td>
                                    <td class="text-right selling-price"
                                        data-selling-price="{{ $item['product']->selling_price }}">{{ number_format($item['product']->selling_price, 2) }}</td>
                                    <td class="text-right">
                                        <form action="{{ url('cart') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item['product']->id }}">
                                            <input type="hidden" name="strategy" value="replace">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-secondary btn-adjust" data-adjust="-"
                                                            type="button" id="button-addon2"><i
                                                                class="fas fa-minus"></i></button>
                                                </div>
                                                <input type="number" class="form-control quantity" name="quantity"
                                                       value="{{ $item['quantity'] }}" min="0" data-max="{{ $item['product']->balance }}" data-name="quantity" >
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary btn-adjust" data-adjust="+"
                                                            type="button" id="button-addon2"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-right amount">{{ number_format($item['quantity'] * $item['product']->selling_price, 2) }}</td>

                                    <td class="text-right">
                                        <button class="btn btn-success update-cart" type="button"><i
                                                    class="fas fa-check"></i></button>
                                        <button class="btn btn-danger btn-remove-item" type="button" ><i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No items in cart</td>
                                </tr>
                            @endforelse
                            </tbody>
                            @if($contents->count())
                                <tfoot>
                                {{--<tr>--}}
                                    {{--<td colspan="3" class="text-right font-weight-bold">Delivery Address:</td>--}}
                                    {{--<td class="d-none">--}}
                                        {{--<div class="form-group m-0">--}}
                                            {{--<textarea id="delivery-address" data-name="delivery_address"--}}
                                                      {{--class="form-control needs-validation">-</textarea>--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                    {{--<td>&nbsp;</td>--}}
                                {{--</tr>--}}
                                <tr>
                                    <td colspan="3" class="text-right border-0  font-weight-bold">Remarks:</td>
                                    <td class="border-0">
                                        <div class="form-group m-0">
                                            <textarea id="remarks" data-name="remarks"
                                                      class="form-control  needs-validation"></textarea>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" class="text-right  border-0  font-weight-bold">Total Amount:</td>
                                    <td class="text-right font-weight-bold border-0" id="total-amount"
                                        style="font-size:1.5rem"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right border-0"></td>
                                    <td class="border-0">
                                        <button type="button"
                                                data-checkout-settings="{!! htmlspecialchars(json_encode(['url' => url('checkout'), 'method' => 'post', 'token' => csrf_token()])) !!}"
                                                id="checkout-btn" class="btn btn-primary btn-block">Checkout
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
      $(document).ready(function () {

        getTotalAmount();

        $('.btn-adjust').click(function () {
          var $this = $(this),
            tr = $this.closest('tr');
          tr.find('.quantity').val(function () {
            var value = Number($(this).val()),
              newValue = $this.data('adjust') === '+'
                ? value + 1
                : value - 1

            return newValue >= 1 ? newValue : 1;
          }).trigger('change')
        })

        $('.quantity').change(function () {
          var maxQuantity = parseFloat($(this).data('max') || 0);
          if(parseFloat($(this).val()) > maxQuantity){
            alert('Available quantity is only ' + maxQuantity);
            $(this).val(maxQuantity)
            return false;
          }

          if(parseFloat($(this).val()) < 0){
            alert('Negative input not allowed!');
            $(this).val(0)
          }
          calculateLineAmount($(this).closest('tr'));
          getTotalAmount();
        })

        $('.update-cart').click(function () {
          var $this = $(this),
            form = $this.closest('tr').find('form'),
            formData = new FormData(form[0]),
            content = $this.html();

          $this.addClass('disabled')
            .html('<i class="fa fa-spin fa-spinner"></i>')

          $.ajax({
            method: form.attr('method'),
            url: form.attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                window.alert('Cart has been successfully updated!')
            },
            error: function (xhr) {
              if(xhr.status === 500){
                window.alert('An internal server error has occured. Please refresh the page and try again!')
              }else if(xhr.status === 422){
                window.alert('Cart cannot accept negative values')
              }

            },
            complete: function () {
              $this.removeClass('disabled').html(content)
            }
          })
        })
      });

      $('.btn-remove-item').click(function () {

        if(!confirm('Are you sure ?')){
            return;
        }

        var $this = $(this),
          form = $this.closest('tr').find('form');

        form.find('.quantity').val(0)

        var formData = new FormData(form[0]);
          content = $this.html();

        $this.addClass('disabled')
          .html('<i class="fa fa-spin fa-spinner"></i>')

        $.ajax({
          method: form.attr('method'),
          url: form.attr('action'),
          data: formData,
          contentType: false,
          processData: false,
          success: function (res) {
            window.location.reload(false)
          },
          error: function (xhr) {
            if(xhr.status === 500){
              window.alert('An internal server error has occured. Please refresh the page and try again!')
            }else if(xhr.status === 422){
              window.alert('Cart cannot accept negative values')
            }

          },
          complete: function () {
            $this.removeClass('disabled').html(content)
          }
        })
      })

      $('#checkout-btn').click(function () {

        var $this = $(this),
          content = $this.html(),
          checkoutSettings = $this.data('checkout-settings'),
          remarks = $('#remarks').val(),
          delivery_address = $('#delivery-address').val()
        _token = checkoutSettings.token;

        $this.addClass('disabled')
          .html('<i class="fa fa-spin fa-spinner"></i>')

        $('.needs-validation').removeClass('is-invalid')
          .next('.invalid-feedback').remove();

        $.ajax({
          url: checkoutSettings.url,
          method: checkoutSettings.method,
          data: {remarks: remarks, delivery_address: delivery_address, _token: _token},
          success: function (response) {
            if(response.hasOwnProperty('redirect')) {
              window.location.href = response.redirect
            }
          },
          error: function (xhr) {
            if (xhr.status === 422) {
              for (var field in xhr.responseJSON.errors) {
                $('[data-name=' + field + ']')
                  .addClass('is-invalid')
                  .after(
                    $('<div />', {
                      class: 'invalid-feedback',
                      html: xhr.responseJSON.errors[field][0]
                    })
                  )
              }
            }
          },
          complete: function () {
            $this.removeClass('disabled').html(content)
          }
        })

      })

      function calculateLineAmount(row) {
        var quantity = Number(row.find('.quantity').val()),
          amount = Number(row.find('.selling-price').data('selling-price'));

        row.find('.amount').text((quantity * amount).toLocaleString(undefined, {
          minimumFractionDigits: 2
        }));
      }


      function getTotalAmount() {
        var total = 0;

        $('.table tbody tr').each(function () {
          var row = $(this),
            quantity = Number(row.find('.quantity').val()),
            sellingPrice = Number(row.find('.selling-price').data('selling-price'));

          total += (quantity * sellingPrice);
        })

        $("#total-amount").text(total.toLocaleString(undefined, {minimumFractionDigits: 2}));
      }
    </script>
@endpush