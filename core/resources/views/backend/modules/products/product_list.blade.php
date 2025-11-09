 @foreach ($products as $product)
     <div class="product-card  bg-white rounded-3 m-3 d-flex p-3 " data-product_id="{{$product->id}}" style="height: 150px; width:30%; cursor: pointer;" >
         <img class="img-fluid rounded col-lg-6 product-img" src="{{ image($product->image) }}" alt="img">
         <div class="px-3">
             <p class="py-1 lh-sm text-lg">{{ $product->name }} <br><span
                     class="text-xs lh-1 py-1 fw-semibold my-1">{{ $product->category->name }}</span></p>
             <hr class="my-1 lh-1">
             <h1 class="text-sm lh-1 fw-semibold p-1">${{ $product->price }}</h1>

         </div>
     </div>
 @endforeach
 <div id="pagination" class=" m-3">
     {{ $products->links('pagination::bootstrap-5') }}
 </div>
