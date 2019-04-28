<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome/css/all.min.css') }}" rel="stylesheet">

    @stack('css')
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-dark bg-success navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @if(Auth::check() && Auth::user()->isRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/categories') }}">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/items') }}">Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/users') }}">Users</a>
                        </li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif
                    @else
                        @if(auth()->user()->isRole('standard'))
                            <li class="nav-item">
                                <a href="{{ url('cart') }}" class="nav-link">
                                    @if($count = Cart::count())
                                        <span class="badge badge-light">{{ $count }}</span>
                                    @endif
                                    <i class="fas fa-shopping-cart"></i>
                                    Cart
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->firstname }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a href="{{ url('orders') }}" class="dropdown-item">Order History</a>
                                <a href="{{ url('profile') }}" class="dropdown-item">Profile</a>

                                @if(auth()->user()->isRole('standard'))
                                    <a href="{{ url('my-favorites') }}" class="dropdown-item">My Favorites</a>
                                @endif




                                @if(auth()->user()->isRole('admin'))
                                    <a href="{{ url('admin/most-favorited-items') }}" class="dropdown-item">Most
                                        Favorited</a>
                                    <a href="{{ url('admin/sales-report') }}" class="dropdown-item">Sales
                                        Reports</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}" q
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>

@stack('js')
<script type="text/javascript">
  $(document).ready(function () {

    var counter = $('.dynamic-table tbody tr').length ? $('.dynamic-table tbody tr').length - 1 : 0;
    $('.dynamic-table').on('click', '.add-line', function (e) {
      e.preventDefault();
      counter++;
      var $this = $(this),
        table = $this.closest('table'),
        clone = table.find('tbody tr:first').clone();

      clone.find('[data-name]').attr('name', function () {
        return $(this).data('name').replace('idx', counter);
      })


      // remove error messages
      var errorFields = clone.find('.has-error');
      errorFields.find('.help-block').remove();
      errorFields.removeClass('has-error')

      clone.find('input:not([type=checkbox]),select').val(function () {
        if ($(this).data('default')) {
          return $(this).data('default');
        }
        return '';
      });

      clone.find('input[type=checkbox]').prop('checked', false);

      table.find('tbody').append(clone)
      // clone.appendTo();
      console.log(table.find('tbody'))
    })

    $('.dynamic-table').on('click', '.remove-line', function (e) {
      e.preventDefault();
      var table = $(this).closest('table'),
        trs = table.find('tbody tr');
      if (trs.length === 1) {
        trs.find('select,input:not([type=checkbox])').val('').trigger('change');
        trs.find('input[type=checkbox]').prop('checked', false)
        trs.find('.reset').text('');
      } else {
        $(this).closest('tr').remove();
        $(this).find('[type=hidden]').remove();
      }
    })

    $('form.ajax').submit(function (e) {

      e.preventDefault();

      var $this = $(this);

      $this.trigger('form:submitted');


      var submitBtn = $this.find('[type=submit]');


      $this.find('.help-block').remove();

      submitBtn.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner"></i> Saving...')


      var formData = new FormData($this[0]);

      $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
          $this.trigger('form:submitted:success');
          if (res.hasOwnProperty('next_url')) {
            window.location.href = res.next_url;
          } else if ($this.data('next-url')) {
            window.location.href = $this.data('next-url');
          } else {
            alertify.success(res.message)
          }
        },
        error: function (err) {
          $this.trigger('form:submitted:error');

          if (err.status == 422) {
            var errors = err.responseJSON['errors'];
            for (var field in errors) {

              var fieldName = field;

              if (field.indexOf('.') !== -1) {
                var parts = field.split('.'),
                  name = parts.splice(0, 1),
                  newField = name + '[' + parts.join('][') + ']';

                fieldName = newField;
              }

              console.log(fieldName)

              var input = $("[name=\"" + fieldName + "\"]");
              input.after('<span class="text-danger help-block">' + errors[field][0] + '</span>');

            }
          } else {
          }
        },
        complete: function () {
          $this.trigger('form:submitted:complete');

          if ($this.hasClass('has-datepicker')) {
            $this.find('.datepicker').val(function () {
              return $(this).val() ? moment($(this).val(), 'Y-MM-DD').format('MM/DD/YYYY') : null;
            });
          }

          submitBtn.removeAttr('disabled').text('Save');
        }
      })
    })
  })
</script>
</body>
</html>
