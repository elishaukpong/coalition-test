<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>
    <body>
    <!-- As a heading -->
    <nav class="navbar navbar-light bg-light">
        <span class="navbar-brand mb-0 h1">Navbar</span>
    </nav>
       <div class="container">
           <div class="row mt-5 pt-5">

               <div class="col-6 mx-auto">
                   <h2>Add Product to Store Listing</h2>

                   <form action="{{route('store.add-product')}}" method="POST" id="submit-form">

                       @csrf

                       <div class="form-group">
                           <label for="Product">Product Name</label>
                           <input type="text" class="form-control" placeholder="Product Name" name="product_name" id="product_name">
                           <small  class="form-text text-muted">What is the name of the product you want to add</small>
                       </div>

                       <div class="form-group">
                           <label for="Product">Quantity in Stock</label>
                           <input type="text" class="form-control" placeholder="Quantity in Stock" id="quantity_in_stock" name="quantity_in_stock">
                           <small class="form-text text-muted">Whats the count of the product in the store?</small>
                       </div>

                       <div class="form-group">
                           <label for="Product">Price per Item</label>
                           <input type="text" class="form-control" placeholder="Price per Item" id="price_per_item" name="price_per_item">
                           <small class="form-text text-muted">At what price should one unit be sold?</small>
                       </div>

                       <button type="submit" class="btn btn-primary" id="form-handler">ADD ITEM</button>
                   </form>
               </div>

           </div>

           <div class="row mt-5">
               <div class="col-12">
                   <h2>Product Store Listing</h2>
                   <table class="table table-striped">
                       <thead>
                       <tr>
                           <th scope="col">Product Name</th>
                           <th scope="col">Quantity in Stock</th>
                           <th scope="col">Price per Item</th>
                           <th scope="col">Date Submitted</th>
                           <th scope="col">Total Value Number</th>
                       </tr>
                       </thead>
                       <tbody id="lists">
                       @php $overallTotalValueCount = 0 @endphp
                       @foreach($products as $product)
                           @php
                               $totalValueCount = $product->quantity_in_stock * $product->price_per_item;

                                $overallTotalValueCount += $totalValueCount;
                           @endphp
                           <tr>
                               <td>{{$product->product_name}}</td>
                               <td>{{$product->quantity_in_stock}}</td>
                               <td>{{$product->price_per_item}}</td>
                               <td>{{$product->time}}</td>
                               <td>{{number_format($totalValueCount)}}</td>
                           </tr>
                           @endforeach

                       </tbody>
                       <tfoot>
                           <tr>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td>Sum of Values</td>
                               <td id="total" data-value="{{$overallTotalValueCount}}">{{number_format($overallTotalValueCount)}}</td>
                           </tr>
                       </tfoot>

                   </table>
               </div>
           </div>
       </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

    <script>
        $(document).ready(function() {
            $('#form-handler').on('click', function(e){
                e.preventDefault();

                $.ajax({
                    url: $('#submit-form').attr('action'),
                    type:$('#submit-form').attr('method'),
                    data:{
                        "_token": "{{ csrf_token() }}",
                        product_name:$('#product_name').val(),
                        quantity_in_stock:$('#quantity_in_stock').val(),
                        price_per_item:$('#price_per_item').val(),
                    },
                    success:function(response){
                        $tableRow = `
                             <tr>
                                <td>${response.product_name}</td>
                               <td>${response.quantity_in_stock}</td>
                               <td>${response.price_per_item}</td>
                               <td>${response.time}</td>
                               <td>${new Intl.NumberFormat().format(response.quantity_in_stock * response.price_per_item)}</td>
                            </tr>
                        `;

                        $('#lists').append($tableRow);

                        var newTotal = $('#total').data('value') + (response.quantity_in_stock * response.price_per_item);
                        $('#total').text(new Intl.NumberFormat().format(newTotal));
                        $('#total').data('value', newTotal);


                        $('#product_name').val('');
                        $('#quantity_in_stock').val('');
                        $('#price_per_item').val('');
                    },
                });
            });
        });
    </script>
</html>
