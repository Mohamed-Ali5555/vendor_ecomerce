<!doctype html>
<html lang="en">

<head>
      @include('frontend.layouts.head')


</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner-grow" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <header class="header_area" id="header-ajax">

           @include('frontend.layouts.header')
               </header>

           <div class="container">
                  <div class="row">
              <div class="col-md-12">
                @include('backend.layouts.notification')
              </div>
            </div></div>

           @yield('content')



        @include('frontend.layouts.footer')


      @include('frontend.layouts.script')

      {{-- ///  put hear link of seetalert put it here --}}

      {{-- ///  this is script of search in header dont forget it --}}

      <script>
          $(document).ready(function (){
            var path="{{route('autosearch')}}";
            $('#search-text').autocomplete({
              source:function (request,response) {
                $.ajax({
                  url:path,
                  dataType:"json",
                  data:{
                    term:request.term
                  },
                  success:function (data) {
                    response(data);
                  }
                });
              },
              minLength:1,
            });
          })
      </script>
        {{-- ///////////////////////////////////////////////////////////function on  header currency/////////////// --}}

        {{-- <script>
           function currency_change(currency_code){
           $.ajax({
               type:'POST',
               url:'',
               data:{
                currency_code:currency_code,
                _token:'{{csrf_token()}}',
               },
               success: function(response){
                if(response['status']){
                  Location.reload();
                  {{-- alert(currency_code); --}}
                {{-- }
                else{
                  alert('server error');
                }
               }

           });

           }
        </script> --}} 


        <script>
          function currency_change(currency_code){
            $.ajax({
              type:'POST',
              url:'{{route('currency.load')}}',
              data:{
                currency_code:currency_code,
                _token: '{{csrf_token()}}',
              },
              success:function (response){
                if(response['status']){
                  location.reload();
                }else{
                  alert('server error');
                }
              }

            })
          }
        </script>




 {{-- ///////  add to wishlist //// --}}
    <script>
    $(document).on('click','.add_to_compare',function(e){
      e.preventDefault();
      var product_id=$(this).data('id');
      {{-- alert(product_id); --}}
      var token = "{{csrf_token()}}";



      $.ajax({
          url: "{{ route('compare.store') }}",
          type: "POST",
          dataType: "json",

          data: {
              product_id: product_id,
              _token: token,
              _method: "POST",
          },
        beforeSend:function(){
          $('#add_to_compare_'+product_id).html('<i class="fas fa-spinner fa spin"></i>');
        },
        complete:function(){
          $('#add_to_compare_'+product_id).html('<i class="fa fa-exchange"></i> ');
        },
        success:function(data){
          console.log(data);

          if(data['status']){
            $('body #header-ajax').html(data['header']);
            $('body #compare_counter').html(data['compare_count']);

            swal({
                title: "Good job!",
                text: data['message'],
                icon: "success",
                button: "Aww yiss!",
            });
          }else if(data['percent']){
              $('body #header-ajax').html(data['header']);
                  $('body #compare_counter').html(data['compare_count']);
                  swal({
                      title: "Opps!",
                      text: data['message'],
                      icon: "warning",
                      button: "ok!",
                  });
          }else{
                  swal({
                      title: "Sorry!",
                      text: data['message'],
                      icon: "error",
                      button: "Aww yiss!",
                  });
          }

        }
      });
    });
   
                     
    </script>

        

</body>

</html>