        @if (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->count() <= 0)
            <p class="text-center"> You Dont have any items in compare list</p>
        @else
            <table class="table table-bordered mb-30">

                <tbody>

                    <tr>
                        <td class="com-title">product image</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            @php
                                $photo = explode(',', $item->model->photo);
                            @endphp
                            <td class="com-pro-img">
                                <a href="{{ route('product.detail', $item->model->slug) }}"><img
                                        src="{{ asset($photo[0]) }}" alt=""></a>
                            </td>
                        @endforeach




                    </tr>
                    <tr>
                        <td class="com-title">product Name</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td><a href="{{ route('product.detail', $item->model->slug) }}">{{ $item->name }}</a></td>
                        @endforeach

                    </tr>
                    <tr>
                        <td class="com-title">Rating</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td>
                                <div class="rating"></div>
                                @for ($i = 0; $i < 5; $i++)
                                    {{-- {{ $item->model->category['title'] }}  --}}
                                    @if (round($item->model->product_reviews->avg('rate')) > $i)
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    @else
                                        <i class="far fa-star" aria-hidden="true"></i>
                                    @endif
                                @endfor
                            </td>
                        @endforeach



                    </tr>
                    <tr>
                        <td class="com-title">Price</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td>{{ Helper::currency_converter($item->price) }}</td>
                        @endforeach

                    </tr>
                    <tr>
                        <td class="com-title">Description</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td> {!! html_entity_decode($item->model->summary) !!} </td>
                        @endforeach

                    </tr>
                    <tr>
                        <td class="com-title">Category</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td> {{ $item->model->category['title'] }} </td>
                        @endforeach

                    </tr>
                    <tr>
                        <td class="com-title">Brand</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td> {{ $item->model->brand['title'] }} </td>
                        @endforeach

                    </tr>
                    <tr>
                        <td class="com-title">Availability</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            @if ($item->model->stock > 0)
                                <td class="instock"> instock </td>
                            @else
                                <td class="instock"> Out Of Stock </td>
                            @endif
                        @endforeach

                    </tr>

                    <tr>
                        <td class="com-title">size</td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td> {{ $item->model->size }} </td>
                        @endforeach

                    </tr>
                    <tr>
                        <td class="com-title"></td>
                        @foreach (\Gloudemans\Shoppingcart\Facades\Cart::instance('compare')->content() as $item)
                            <td class="action">

                                <a href="javascript:;" data-id="{{ $item->rowId }}"
                                    class="mb-1 compare_addTocart move-to-cart" id="move_to_cart_{{ $item->rowId }}"><i
                                        class="icofont-shopping-cart"></i></a>
                                <a href="javascript:;" data-id="{{ $item->rowId }}"
                                    class="mb-1 compare_addWishlist move-to-wishlist" id=""><i
                                        class="icofont-heart"></i></a>

                                <a href="javascript:;" data-id="{{ $item->rowId }}" id=""
                                    class="mb-1 remove_from_compare delete_compare">
                                    <i class="icofont-close"></i></a>
                            </td>
                        @endforeach


                    </tr>

                </tbody>
            </table>
        @endif



        @section('scripts')
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

            <script>
                $('.move-to-cart').on('click', function(e) {
                    e.preventDefault();
                    var rowId = $(this).data('id');

                    var token = "{{ csrf_token() }}";



                    $.ajax({
                        url: "{{ route('compare.move.cart') }}",
                        type: "POST",
                        dataType: "json",

                        data: {
                            rowId: rowId,

                            _token: token,
                            _method: "POST",
                        },

                        beforeSend: function() {
                            $('#move_to_cart_' + rowId).html('<i class="fa fa-spinner fa-spin"></i>');
                        },

                        success: function(data) {
                            console.log(data);
                            ///////////this make refresh when you add product
                            $('body #header-ajax').html(data['header']);

                            if (data['status']) {
                                $('body #cart_counter').html(data['cart_count']);
                                $('body #wishlist_list').html(data['wishlist_list']);
                                $('body #compare').html(data['compare_list']);
                                $('body #header-ajax').html(data['header']);


                                swal({
                                    title: "Good job!",
                                    text: data['message'],
                                    icon: "success",
                                    button: "Aww yiss!",
                                });

                            } else {
                                swal({
                                    title: "error",
                                    text: "someting went wrong",
                                    icon: "error",
                                    button: "Aww yiss!",
                                });

                            }
                        }

                    });

                });
            </script>
            <script>
                $('.move-to-wishlist').on('click', function(e) {
                    e.preventDefault();
                    var rowId = $(this).data('id');

                    var token = "{{ csrf_token() }}";



                    $.ajax({
                        url: "{{ route('compare.move.wishlist') }}",
                        type: "POST",
                        dataType: "json",

                        data: {
                            rowId: rowId,

                            _token: token,
                            _method: "POST",
                        },



                        success: function(data) {
                            if (data['status']) {
                                $('body #cart_counter').html(data['cart_count']);
                                $('body #wishlist_list').html(data['wishlist_list']);
                                $('body #compare').html(data['compare_list']);
                                $('body #header-ajax').html(data['header']);


                                swal({
                                    title: "Good job!",
                                    text: data['message'],
                                    icon: "success",
                                    button: "Aww yiss!",
                                });

                            } else {
                                swal({
                                    title: "error",
                                    text: "someting went wrong",
                                    icon: "error",
                                    button: "Aww yiss!",
                                });

                            }
                        }

                    });

                });
            </script>
            <script>
                $('.delete_compare').on('click', function(e) {
                    e.preventDefault();
                    var rowId = $(this).data('id');

                    var token = "{{ csrf_token() }}";
                    {{-- alert(rowId); --}}

                    $.ajax({
                        url: "{{ route('compare.delete') }}",
                        type: "POST",

                        data: {
                            _token: token,
                            rowId: rowId,

                        },


                        success: function(data) {
                            if (data['status']) {
                                $('body #cart_counter').html(data['cart_count']);
                                $('body #wishlist_list').html(data['wishlist_list']);
                                $('body #compare').html(data['compare_list']);
                                $('body #header-ajax').html(data['header']);


                                swal({
                                    title: "Good job!",
                                    text: data['message'],
                                    icon: "success",
                                    button: "Aww yiss!",
                                });

                            } else {
                                swal({
                                    title: "error",
                                    text: "someting went wrong",
                                    icon: "error",
                                    button: "Aww yiss!",
                                });

                            }
                        },
                        error: function(err) {
                            alert('vgggggg');
                        }

                    });

                });
            </script>
        @endsection
