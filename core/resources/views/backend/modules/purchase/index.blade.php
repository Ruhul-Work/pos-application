@extends('backend.layouts.master')
@section('content')
    <div class="row ">

        <div class="product-div col-lg-8 bg-gray " style="height: 80vh; overflow-y: auto;">
            <div class="row justify-content-between">
                {{-- welcome div --}}
                <div class="col-lg-4">
                    <h6 class="text-xl lh-1 fw-semibold">Welcome, {{ Auth::user()->name }}</h6>
                    <p class="text-sm">{{ now()->format('l, d M Y') }} </p>
                </div>
                {{-- input and buttons --}}
                <div class="col-lg-6 d-flex justify-content-end align-items-center gap-2">
                    <input class="border  px-3 fst-italic rounded bg-light form-control" id="product-search-input"
                        type="text" placeholder="search product" style="width: 240px;">

                    <button class="btn btn-dark btn-sm px-3 text-xs d-flex align-items-center justify-content-center gap-1">
                        {{-- <iconify-icon icon="flowbite:tag-outline" class="menu-icon fs-6"></iconify-icon> --}}
                        <span>View All Brands</span>
                    </button>

                    <button class="btn btn-primary btn-sm px-3 text-xs">Featured</button>
                </div>


            </div>
            {{-- categories  --}}
            <div class="g-3 py-1 overflow-x-auto d-flex mt-3 category-nav" style="white-space: nowrap;">
                <button class="btn nav-btn active rounded-4 py-1 text-md" data-category-id="">All
                    Categories</button>
                @foreach ($categories as $category)
                    <button class="btn nav-btn  rounded-4 py-1 text-md"
                        data-category-id="{{ $category->id }}">{{ $category->name }}</button>
                @endforeach


            </div>
            {{-- products --}}
            <div class="products-div mt-3 row gap-space-between  h-auto align-item-center justify-content-center"
                id="product-list">

                {{-- @include('backend.modules.products.product_list', ['products' => $products]) --}}

            </div>


        </div>


        <div class="order-div col-lg-4 bg-white rounded-3 " style="height: 80vh; overflow-y: auto;">

            <div class="d-flex p-1 justify-content-between mt-3">
                <h1 class="text-xl lh-1 fw-semibold p-1">Purchase List</h1>

                <div>
                    <h6 class="text-xs   p-1 px-3 bg-dark text-white rounded-pill">#ord1247</h6>
                </div>
            </div>
            <hr class=" px-3" style="border-top: 1px dashed #000;">
            <div class="p-1 mt-3">
                <h1 class="text-lg lh-1 fw-semibold p-1">Supplier's Information</h1>
                <div class="d-flex gap-2">

                    <div class="col-lg-9 d-flex gap-2">
                        <select class="form-control form-control-sm col-lg-3 js-s2-ajax" name="supplier_id" id="supplier"
                            data-url="{{ route('supplier.select2') }}" data-placeholder="Select Supplier">

                        </select>
                        <div class="invalid-feedback d-block category_id-error" style="display:none"> </div>
                        <div class="p-2 d-flex gap-2">
                            <button class="btn btn-success rounded-1 btn-md"> <iconify-icon icon="flowbite:users-outline"
                                    class="menu-icon"></iconify-icon></button>
                            <button class="btn btn-primary rounded-1 btn-md"><iconify-icon icon="mdi:qrcode-scan"
                                    class="menu-icon"></iconify-icon></button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3 mb-0 rounded-3 p-3 "
                    style="background:#FFEEE9;border:1px solid #E04F16">
                    <div class="">
                        <h1 class="text-md lh-1 fw-semibold ">James Anderson</h1>
                        <p class="text-sm lh-1">Bonus: <span class="bg-info rounded-3 fw-semibold p-1 text-white">148</span>
                            |
                            Loyality: <span class="bg-success rounded-3 fw-semibold p-1 text-white">520</span></p>
                    </div>
                    <div> <button class="btn btn-danger btn-xs py-1 text-xs ">Apply</button></div>

                </div>
                <hr class="my-3">
                {{-- order details --}}
                <div class="d-flex justify-content-between p-3">

                    <div class="d-flex gap-2">
                        <h1 class="text-md lh-1 fw-semibold mt-1 p-2">Order Details</h1>
                        <button class="btn btn-outline-secondary border btn-sm px-1 py-0 disabled">Items :
                            <span id="total_items">3</span></button>
                    </div>
                    <div>
                        <button class="btn btn-outline-danger empty-cart btn-xs py-1 text-xs">Clear all</button>
                    </div>

                </div>
                {{-- table --}}
                <div class="p-3">
                    <table class="table table-sm table-borderless  text-gray">
                        <thead class="text-sm  fw-semibold">
                            <tr class="table-light rounded-3   px-1">

                                <th scope="col" class="text-center">Item</th>
                                <th scope="col" class="text-center">Quantity</th>
                                <th scope="col" class="text-center">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm py-1 " id="purchase_items">


                        </tbody>
                    </table>


                </div>

                <div class="d-flex justify-content-between mt-3 mb-0 rounded-3 p-3 "
                    style="background:#e9e0ef;border:1px solid #8035ba">
                    <div class="">
                        <h1 class="text-md lh-1 fw-semibold ">Discount 5%</h1>
                        <p class="text-sm ">For $20 Minimum Purchase, all Items</p>
                    </div>
                    <div> <button class="btn btn-danger btn-xs py-1 text-xs">Apply</button></div>

                </div>

                <div class="p-3">
                    <table class="table table-sm table-borderless  text-gray">
                        <thead class="text-sm fw-semibold">
                            <tr class=" rounded-3  px-1">

                                <th scope="col">Payment Summary</th>


                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr>

                                <td class="text-secondary">
                                    <span>Shippping</span>
                                    <button class="bg-light rounded-4 border-0 p-1 icon">
                                        <iconify-icon icon="flowbite:edit-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>
                                </td>
                                <td class="text-secondary">$20</td>

                            </tr>
                            <tr>

                                <td class="text-secondary">
                                    <span>Discount</span>
                                    <button class="bg-light rounded-4 border-0 p-1 icon">
                                        <iconify-icon icon="flowbite:edit-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>
                                </td>
                                <td class="text-secondary">$50</td>

                            </tr>
                            <tr>

                                <td class="text-secondary">
                                    <span>Subtotal</span>
                                    <button class="bg-light rounded-4 border-0 p-1 icon">
                                        <iconify-icon icon="flowbite:edit-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>
                                </td>
                                <td class="text-secondary" id="subtotal">$2300</td>

                            </tr>


                            <tr>

                                <td>
                                    <hr class="mt-2" />
                                </td>
                                <td>
                                    <hr class="mt-2" />
                                </td>
                            </tr>
                            <tr class="fw-semibold">
                                <td>Total Amount</td>
                                <td id="total_amount">$2360</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="p-3 border border-danger bg-light rounded">
                    <h1 class="text-md lh-1 fw-semibold">Select Payment Method</h1>
                    <div class="row gap-3  mt-5 px-3 justify-content-center">
                        {{-- <div class="col-lg"><button class="btn btn-outline-dark btn-sm">Cash</button></div> --}}
                        <button class="btn col-lg-3 payment-btn btn-sm">Card</button>
                        <button class="btn col-lg-3 payment-btn btn-sm">Bkash</button>
                        <button class="btn col-lg-3 payment-btn btn-sm">Nagad</button>
                        <button class="btn col-lg-3 payment-btn btn-sm">Card</button>
                        <button class="btn col-lg-3 payment-btn btn-sm">Bkash</button>
                        <button class="btn col-lg-3 payment-btn btn-sm">Nagad</button>

                    </div>
                </div>
                <div class="d-flex  mt-3 gap-2 mb-3">
                    <button style="padding: 10px"
                        class="btn btn-light border col-lg-6   d-flex align-items-center justify-content-center gap-2"><iconify-icon
                            icon="flowbite:printer-outline" class="menu-icon fs-5 "></iconify-icon><span>Print
                            Order</span>
                    </button>
                    <button
                        class="btn btn-danger border  col-lg-6 p-1 d-flex align-items-center justify-content-center gap-2"><iconify-icon
                            icon="flowbite:cart-outline" class="menu-icon fs-5 "></iconify-icon><span>Place
                            Order</span>
                    </button>


                </div>

            </div>
        </div>

    </div>
    <div class="footer-pos d-flex justify-content-center align-items-center gap-3 mt-3 position-absoulte bg-white p-3 "
        style="position:absoulte;width:100vw">
        <div class="p-0">
            <button class="btn btn-primary btn-sm rounded-4 d-flex align-item-center justify-content-center"><iconify-icon
                    icon="flowbite:pause-outline" class="menu-icon fs-5 "></iconify-icon>Hold</button>
        </div>
        <div class="p-0">
            <button class="btn btn-danger btn-sm rounded-4 d-flex align-item-center justify-content-center"><iconify-icon
                    icon="mdi:trash-outline" class="menu-icon fs-5 "></iconify-icon>Void</button>
        </div>
        <div class="p-0">
            <button class="btn btn-success btn-sm rounded-4 d-flex align-item-center justify-content-center"><iconify-icon
                    icon="flowbite:cash-outline" class="menu-icon fs-5 "></iconify-icon>Payment</button>
        </div>
        <div class="p-0">
            <button class="btn btn-dark btn-sm rounded-4 d-flex align-item-center justify-content-center"><iconify-icon
                    icon="flowbite:cart-outline" class="menu-icon fs-5 "></iconify-icon>View Orders</button>
        </div>
        <div class="p-0">
            <button class="btn btn-warning btn-sm rounded-4 d-flex align-item-center justify-content-center"><iconify-icon
                    icon="mdi:reload" class="menu-icon fs-5 "></iconify-icon>Reset</button>
        </div>
        <div class="p-0">
            <button class="btn btn-info btn-sm rounded-4 d-flex align-item-center justify-content-center"><iconify-icon
                    icon="mdi:swap-horizontal" class="menu-icon fs-5 "></iconify-icon>Transactions</button>
        </div>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            function loadPurchaseItems() {
                let purchaseItems = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let count = 0;
                let purchaseItemsHtml = '';
                purchaseItems.forEach(function(item) {
                    count += 1;

                    purchaseItemsHtml += `
                       <tr>
                                    <td class="d-flex align-items-center text-center justify-content-start gap-2 text-secondary ">
                                        <span class="order-item">${item.name}
                                            </span><button class="icon product-delete" data-product_id="${item.id}"><iconify-icon icon="mdi:delete"
                                                class="menu-icon fs-7 "></button>
                                    </td>
                                    <td class=" align-items-center justify-content-center text-center gap-2 text-secondary">
                                        <button class="bg-light  rounded-4 border-0 p-1 qty-button btn-minus">
                                            <iconify-icon icon="flowbite:minus-outline"
                                                class="menu-icon fs-7"></iconify-icon>
                                        </button>

                                        <span class="mx-3"><input class="form-control qty-input text-center text-black px-1 " type="number" value="${item.quantity}" min="1" style="min-width:30px !important; max-width:80px !important; height:35px; display:block; "></span>

                                        <button class="bg-light rounded-4 p-1 qty-button btn-plus">
                                            <iconify-icon icon="flowbite:plus-outline"
                                                class="menu-icon fs-7"></iconify-icon>
                                        </button>
                                    </td>
                                    <td class="product-price">
                                        $ ${item.price*item.quantity}
                                    </td>

                                </tr>`;
                });
                $('#purchase_items').html(purchaseItemsHtml);
                $('#total_items').text(count);
                calculateSubtotal();

            }
            //delete individual cart item
            $(document).off('click', '.product-delete').on('click', '.product-delete', function() {
                let productId = $(this).data('product_id');
                if (productExistsInCart(productId)) {
                    deleteFromPurchaseCart(productId);
                    $(this).closest('tr').remove();
                    // loadPurchaseItems();
                } else {
                    alert('Product not found in purchase cart!');
                }

            });

            // clear cart
            $(document).off('click', '.empty-cart').on('click', '.empty-cart', function() {
                emptyCart();

            });

            //update cart on quantity change with input
            $(document).on('input', '.qty-input', function() {
                let productId = $(this).closest('tr').find('.product-delete').data('product_id');


                let newQuantity = parseInt($(this).val());
                if (isNaN(newQuantity) || newQuantity < 1) {
                    // alert('Invalid quantity!');
                    $(this).val(1);
                    return;
                }

                updateQuantityInCart(productId, newQuantity);

                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let product = cart.find(item => item.id === productId);
                if (product) {
                    let newPrice = product.price * product.quantity;
                    $(this).closest('tr').find('.product-price').html(
                        `$${newPrice}`);
                }
                calculateSubtotal();

            });

            //increase quantity of individual item with + button
            $(document).off('click', '.btn-plus').on('click', '.btn-plus', function() {
                let productId = $(this).closest('tr').find('.product-delete').data( 'product_id');

                let i = $(this).closest('tr').find('.qty-input');
                let price = $(this).closest('tr').find('.product-price');
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let product = cart.find(item => item.id === productId);

                i.val(parseInt(i.val()) + 1);

                updateQuantityInCart(productId, product.quantity + 1);
                price.html('$ '+product.price*(product.quantity+1));
              
                calculateSubtotal();

            });

            //decrease quantity of individual with - button
            $(document).off('click', '.btn-minus').on('click', '.btn-minus', function() {
                let productId = $(this).closest('tr').find('.product-delete').data(
                    'product_id');

                let i = $(this).closest('tr').find('.qty-input');
                if(i.val()>1){
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let product = cart.find(item => item.id === productId);
                
                    updateQuantityInCart(productId, product.quantity - 1);
                    i.val(parseInt(i.val()) - 1);

                    $(this).closest('tr').find('.product-price').html(
                        `$${product.price * (product.quantity-1)}`);
               
            }
                calculateSubtotal();

            });




          

            // Delegate click event for pagination links
            $(document).on('click', '.page-link-btn', function(e) {
                e.preventDefault();

                let url = $(this).data('url');
                if (!url || url === '#') return; // skip disabled links

                loadProducts(url);
                // loadPurchaseItems();
            });
            // Search functionality
            $('#product-search-input').on('input', function() {
                let query = $(this).val();
                if (query.length > 0) {
                    let url = "{{ route('product.productsSearch', ':name') }}".replace(':name', query);
                    loadProducts(url);
                } else {
                    loadProducts("{{ route('product.productsList') }}");
                }
            });
            // Category filter functionality
            $('.nav-btn').on('click', function() {
                if ($(this).data('category-id') == '') {
                    loadProducts("{{ route('product.productsList') }}");
                    return;
                }
                let categoryId = $(this).data('category-id');
                let url = "{{ route('product.productsByCategory', ':category') }}".replace(':category',
                    categoryId);
                loadProducts(url);
            });

            // Initial load if needed (you may already be doing this)
            loadProducts("{{ route('product.productsList') }}");
            loadPurchaseItems();

            function updatePrice(element, price) {
                a = $(element).closest('tr').find('.product-price');
                a.html('$ ' + price);
            }
            // Reusable function to load products
            function loadProducts(url) {
                let products = [];
                let productsHtml = '';
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        console.log(res.products.data);
                        products = res.products.data;
                        products.forEach(function(product) {

                            let a = "{{ image('') }}";
                            console.log(a);
                            let p = product.image;
                            image = a.replace('theme/frontend/assets/img/default/book.png', p);

                            productsHtml += `<div class="product-card  bg-white rounded-3 m-3 d-flex p-3 " data-product_id="${product.id}"
                                style="height: 150px; width:30%; cursor: pointer;">
                                 <img class="img-fluid rounded col-lg-6 product-img" src="${image}" alt="img">
                                     <div class="px-3">
                                         <p class="py-1 lh-sm text-lg">${product.name}<br><span
                                             class="text-xs lh-1 py-1 fw-semibold my-1"><span>${ product.name }</span></p>
                                                 <hr class="my-1 lh-1">
                                                 <h1 class="text-sm lh-1 fw-semibold p-1">$${product.price}</h1>

                                    </div>
                            </div> `;
                        })

                        $('#product-list').html(productsHtml);
                        let paginationHtml = '';
                        let links = res.products.links;
                        links.forEach(function(link) {
                            paginationHtml += `<button 
                        class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-primary'} m-1 page-link-btn"
                        data-url="${link.url ?? '#'}"
                        ${!link.url ? 'disabled' : ''}>
                        ${link.label}
                    </button>`;
                        })

                        $('#product-list').append('<div class="col-lg-12 ">' + paginationHtml +
                            '</div>');

                        $(document).off('click','.product-card').on('click','.product-card', function() {
                            let productId = $(this).data('product_id');
                            let cart = JSON.parse(localStorage.getItem('purchaseCart')) || null;
                            if (productExistsInCart(productId)) {
                                cart.forEach(function(item) {
                                    if (item.id === productId || item.parent_id === productId) {
                                       deleteFromPurchaseCart(item.id);
                                    }
                                });

                                loadPurchaseItems();
                                $(this).removeClass('active');
                               
                            } else {
                                addToPurchaseCart(productId);
                                $(this).addClass('active');
                               
                            }

                        });

                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }


                });

            }
            // cart functionality

            //check if product exists in cart with product id
            function productExistsInCart(productId) {
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let exists = false;
                // console.log(cart);  
                cart.forEach(element => {

                    if (element.parent_id == productId || element.id == productId) {
                        exists = true;
                        console.log(element.id);
                    }
                });
                return exists;
            }

            function totalItemsInCart() {
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
               $('#total_items').text(cart.length);
            }

            //add product with child items in cart
            function addToPurchaseCart(productId) {
                let url = "{{ route('product.childProductList', ':parentId') }}".replace(':parentId', productId);
                let childProducts = [];
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        let products = res.products;
                        let notEmpty;
                        let cart = [];
                        if (localStorage.getItem('purchaseCart')) {
                            cart = JSON.parse(localStorage.getItem('purchaseCart'));
                            notEmpty = JSON.parse(localStorage.getItem('purchaseCart'));
                        }
                        childProducts = products.map(function(product) {
                            product['quantity'] = 1;
                            return product;
                        });
                        localStorage.setItem('purchaseCart', JSON.stringify(cart.concat(
                            childProducts)));
                        childProducts.map(function(product) {
                            let purchaseItemsHtml = `
                       <tr>
                                    <td class="d-flex align-items-center justify-content-start gap-2 text-secondary ">
                                        <span class="order-item">${product.name}
                                            </span><button class="icon product-delete" data-product_id="${product.id}"><iconify-icon icon="mdi:delete"
                                                class="menu-icon fs-7 "></button>
                                    </td>
                                    <td class=" align-items-center text-center justify-content-center gap-2 text-secondary">
                                        <button class="bg-light  rounded-4 border-0 p-1 qty-button btn-minus">
                                            <iconify-icon icon="flowbite:minus-outline"
                                                class="menu-icon fs-7"></iconify-icon>
                                        </button>

                                        <span class="mx-3"><input class="form-control qty-input text-center text-black px-1 " type="number" value="${product.quantity}" min="1" style="min-width:30px !important; max-width:80px !important; height:35px; display:block; "></span>

                                        <button class="bg-light rounded-4 p-1 qty-button btn-plus">
                                            <iconify-icon icon="flowbite:plus-outline"
                                                class="menu-icon fs-7"></iconify-icon>
                                        </button>
                                    </td>
                                    <td class='product-price'>
                                        $${product.price*product.quantity}
                                    </td>

                                </tr>`;
                            $('#purchase_items').append(purchaseItemsHtml);
                        })
                        if (!notEmpty) {

                            loadPurchaseItems();
                        }
                        calculateSubtotal();
                        totalItemsInCart();

                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
            
            

            //delete product from cart with product id
            function deleteFromPurchaseCart(productId) {
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let updatedCart = cart.filter(function(item) {
                   
                    return item.id !== productId;
                });
                localStorage.setItem('purchaseCart', JSON.stringify(updatedCart));
                calculateSubtotal();
               
            }

            //empty product card from localstorage 
            function emptyCart() {
                localStorage.removeItem('purchaseCart');
                // alert('Purchase cart emptied successfully!');
                loadPurchaseItems();
            }

            function updateQuantityInCart(productId, newQuantity) {
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                cart.forEach(function(item) {
                    if (item.id === productId) {
                        item.quantity = newQuantity;
                    }
                });
                localStorage.setItem('purchaseCart', JSON.stringify(cart));
                // loadPurchaseItems();
            }


            function increaseQuantity(productId) {
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                cart.forEach(function(item) {
                    if (item.id === productId) {
                        item.quantity += 1;
                    }
                });
                localStorage.setItem('purchaseCart', JSON.stringify(cart));
                loadPurchaseItems();
            }

            function calculateSubtotal() {
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let total = 0;
                cart.forEach(function(item) {
                    total += item.price * item.quantity;
                });
                $('#subtotal').text(`$${total}`);
            }





        });

        // Highlight active category button
        $(document).on('click', '.nav-btn', function(e) {
            $('.nav-btn').removeClass('active');
            $(this).addClass('active');
        });
        $(document).on('click', '.product-card', function(e) {
            if($(this).hasClass('active')){

                $(this).removeClass('active');
            }else{
                $(this).addClass('active');
            }

        });

        //supplier select2
        window.S2.auto();

        //cart functionality
    </script>
@endsection
