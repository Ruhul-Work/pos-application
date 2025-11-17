@extends('backend.layouts.master')
@section('content')
    <div class="row ">

        <div class="product-div col-lg-7 bg-gray " style="height: 80vh; overflow-y: auto;">
            <div class="row justify-content-between">
                {{-- welcome div --}}
                <div class="col-lg-4 mt-1">
                    <h6 class="text-xl lh-1 fw-semibold">Welcome, {{ Auth::user()->name }}</h6>
                    <p class="text-sm">{{ now()->format('l, d M Y') }} </p>
                </div>
                {{-- input and buttons --}}
                <div class="col-lg-6 d-flex justify-content-end align-items-center gap-2">
                    <input class="border  px-3 fst-italic rounded bg-light form-control" id="product-search-input"
                        type="text" placeholder="search product" style="width: 240px;">

                    <button class="btn btn-dark btn-sm px-3 text-xs d-flex align-items-center justify-content-center gap-1">
                        {{-- <iconify-icon icon="flowbite:tag-outline" class="menu-icon fs-6"></iconify-icon> --}}
                        <span>All Brands</span>
                    </button>

                    {{-- <button class="btn btn-primary btn-sm px-3 text-xs">Featured</button> --}}
                </div>


            </div>
            {{-- categories  --}}
            <div class="g-3 py-1 overflow-x-auto d-flex mt-3 category-nav" style="white-space: nowrap;">
                <button class="btn nav-btn active rounded-4 py-1 text-md" data-category-id="">All
                    Categories
                </button>
                @foreach ($categories as $category)
                    <button class="btn nav-btn  rounded-4 py-1 text-md"data-category-id="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
            {{-- products --}}
            <div class="products-div mt-3 row gap-space-between h-auto align-item-center justify-content-center"
                id="product-list">

                {{-- @include('backend.modules.products.product_list', ['products' => $products]) --}}


            </div>


        </div>


        <div class="order-div col-lg-5 bg-white rounded-3 " style="height: 80vh; overflow-y: auto;">

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
                        <div class="mt-1">
                            <select class="form-control form-control-sm col-lg-3 js-s2-ajax" name="supplier_id"
                                id="supplier" data-url="{{ route('supplier.select2') }}"
                                data-placeholder="Select Supplier">
                                <option id="recent" value="" selected></option>

                            </select>
                            <div class="invalid-feedback d-block category_id-error" style="display:none"> </div>
                        </div>
                        <div class="p- my-1 d-flex gap-2">
                            <button class="btn btn-success rounded-1 btn-sm AjaxModal"
                                data-ajax-modal="{{ route('supplier.createModal') }}" data-size="lg"
                                data-onload="CategoryIndex.onLoad" data-onsuccess="CategoryIndex.onSaved"> <iconify-icon
                                    icon="flowbite:users-outline" class="menu-icon"></iconify-icon></button>
                            <button class="btn btn-primary rounded-1 btn-sm"><iconify-icon icon="mdi:qrcode-scan"
                                    class="menu-icon"></iconify-icon></button>
                        </div>

                    </div>
                   
                </div>

                <div class="row">
                <div class="mt-1 col-lg-7 ">
                    <label for="warehouse_id" class="form-label">Warehouse</label>
                    <select class="form-control form-control-sm col-lg-3 js-s2-ajax" name="warehouse_id" id="warehouse"
                        data-url="{{ route('inventory.warehouses.select2') }}" data-placeholder="Select warehouse">
                        <option id="recent" value="" selected></option>

                    </select>
                    <div class="invalid-feedback d-block category_id-error" style="display:none"> </div>
                </div>
                <div class="col-lg-5 py-1 px-3">
                      <label for="warehouse_id" class="form-label">Order Status</label>
                    <select name="status" id="" class="form-control form-control-sm">
                        <option value="" selected>Draft</option>
                        <option value="">Received</option>
                    </select>
                </div>

                <div class="col-lg-6">
                    <label for="" class="form-label">Purchase Date</label>
                    <input type="date" lang="en-GB" class="form-control form-control-sm"    name="purchase_date">
                </div>
                <div class="col-lg-6">
                    <label for="" class="form-label">Reference</label>
                    <input type="text" class="form-control form-control-sm"  name="reference" placeholder="Ruhul Amin">
                </div>
                <div class="col-lg-6 mt-1">
                    <label for="" class="form-label">Invoice</label>
                    <input type="file" class="form-control form-control-sm p-1"  name="invoice">
                </div>

                </div>


                <hr class="my-3">
                {{-- order details --}}
                <div class="d-flex justify-content-between p-3">

                    <div class="d-flex gap-2">
                        <h1 class="text-md lh-1 fw-semibold mt-1 p-2">Order Details</h1>
                        <button class="btn btn-outline-dark  border btn-sm px-1 py-0 ">Items :
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
                            <tr class="table-light rounded-3 px-1">

                                <th scope="col" class="text-center">Item</th>
                                <th scope="col" class="text-center">Quantity</th>
                                <th scope="col" class="text-center">Price</th>
                                <th scope="col" class="text-center">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm " id="purchase_items">


                        </tbody>
                    </table>


                </div>

                {{-- <div class="d-flex justify-content-between mt-3 mb-0 rounded-3 p-3 "
                    style="background:#e9e0ef;border:1px solid #8035ba">
                    <div class="">
                        <h1 class="text-md lh-1 fw-semibold ">Discount 5%</h1>
                        <p class="text-sm ">For $20 Minimum Purchase, all Items</p>
                    </div>
                    <div> <button class="btn btn-danger btn-xs py-1 text-xs">Apply</button></div>

                </div> --}}

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
                                    <span>Subtotal</span>
                                    
                                </td>
                                <td class="text-secondary" id="subtotal">$2300</td>

                            </tr>
                            <tr>

                                <td class="text-secondary">
                                    <span>Shippping</span>
                                    
                                    <!-- Modal -->
                                  
                                </td>
                                <td class="text-secondary "><input type="number"  id="shipping" min="0" class="border-1 rounded py-1 "
                                        value="0" style="width:120px; height:35px"></td>

                            </tr>
                          
                            <tr>

                                <td class="text-secondary">
                                    <span>Discount</span>
                                    <button type="button" class="bg-light rounded-4 border-0 p-1 icon"
                                        data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                        <iconify-icon icon="flowbite:edit-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-lg" id="exampleModalCenterTitle">Discount
                                                    </h5>
                                                    <button type="button" class="btn-close " data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <label for="" class="form-label">Discount Type</label>
                                                    <select class="form-control " name="discount_type"
                                                        id="discount-type">
                                                        <option value="flat">Flat</option>
                                                        <option value="percentage" selected>Percentage</option>
                                                    </select>
                                                    <label for="" class="form-label mt-3">Amount</label>
                                                    <input type="number" min="0" class="form-control "
                                                        id="discount-input">
                                                </div>
                                                <div class="modal-footer ">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" data-bs-toggle="modal"
                                                        class="btn btn-dark btn-sm" id="discount-save">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td> <input type="number" min="0" value="0" disabled id="discount"
                                        class="text-danger " style="width: 80px"></td>

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
                                <td id="total_amount" class="">$2360</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                {{-- <div class="p-3 border border-danger bg-light rounded">
                    <h1 class="text-md lh-1 fw-semibold">Select Payment Method</h1>
                    <div class="row gap-3  mt-5 px-3 justify-content-center">
                       
                        <button type="button" class=" btn col-lg-3 payment-btn btn-sm payment-modal" id="btn-cash" data-bs-toggle="modal"
                            data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap" data-payment_mode="cash">Cash</button>
                        <button type="button" class=" btn col-lg-3 payment-btn payment-modal btn-sm" id="btn-cash" data-bs-toggle="modal"
                            data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap" data-payment_mode="bkash">Bkash</button>
                        <button type="button" class=" btn col-lg-3 payment-btn payment-modal btn-sm" id="btn-cash" data-bs-toggle="modal"
                            data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap" data-payment_mode="card">Card</button>
                       

                    </div>
                </div> --}}


                <div class="d-flex justify-content-center mt-3  mb-3">
                    {{-- <button style="padding: 10px"
                        class="btn btn-light border col-lg-6   d-flex align-items-center justify-content-center gap-2"><iconify-icon
                            icon="flowbite:printer-outline" class="menu-icon fs-5 "></iconify-icon><span>Print
                            Order</span>
                    </button> --}}
                    <button data-bs-toggle="modal"
                            data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap"
                        class="btn btn-danger payment-modal border p-3  col-lg-10  d-flex align-items-center justify-content-center "><iconify-icon
                            icon="flowbite:cart-outline" class="menu-icon fs-5 "></iconify-icon><span>Purchase</span>
                    </button>
                    
                </div>
                    {{-- checkout form modal --}}
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Checkout</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="row">
                                                <div class="mb-3 col-lg-4">
                                                    <label for="total_amount" class="col-form-label">Total
                                                        Amount</label>
                                                    <input type="number" name="total_amount" disabled class="form-control form-control-sm"
                                                        id="checkout_total_amount" value="0">
                                                </div>
                                                <div class="mb-3 col-lg-4">
                                                    <label for="paid_amount" class="col-form-label">Paid
                                                        Amount</label>
                                                    <input type="number" name="paid_amount" min="0" class="form-control form-control-sm"
                                                        id="paid_amount" value="0">
                                                </div>
                                                <div class="mb-3 col-lg-4">
                                                    <label for="due_amount" class="col-form-label">Due Amount</label>
                                                    <input type="number" name="due_amount" disabled class="form-control form-control-sm"
                                                        id="due_amount" value="0">
                                                </div>
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="payment_type" class="col-form-label">Payment Type</label>
                                                <select name="payment_type" id=""
                                                    class="form-control form-control-sm">
                                                    <option value="" selected>Cash</option>
                                                    <option value="">Card</option>
                                                    <option value="">Bkash</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="payment_receiver" class="col-form-label">Payment
                                                    Receiver</label>
                                                <input type="text" class="form-control form-control-sm" name="payment_receiver"
                                                    id="recipient-name">
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="payment_note" class="col-form-label">Payment Note</label>
                                                <textarea rows="2" name="payment_note" class="col-md-12 rounded-3" id="recipient-name"></textarea>
                                            </div>
                                           
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger btn-sm rounded-4"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-dark btn-sm rounded-4">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

            </div>
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
                                    <td class="">
                                         <input type="number" name="item_price" class="item_price" value="${item.cost_price}" style="width:70px;">
                                    </td>
                                    <td class="product-price">
                                         ${item.cost_price*item.quantity}
                                    </td>

                                </tr>`;
                });
                $('#purchase_items').html(purchaseItemsHtml);
                $('#total_items').text(count);
                calculateSubtotal();
                // calculateDiscount();
                // calculateTotalAmount();

            } //loadPurchaseItems function ends here
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

            //update price
            $(document).on('input', '.item_price', function() {

                let newPrice = $(this).val();
                let productId = $(this).closest('tr').find('.product-delete').data('product_id');
                let product = updateProductPrice(productId, newPrice);
                $(this).closest('tr').find('.product-price').html(`$${product.quantity*newPrice}`);

            })

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
                let productId = $(this).closest('tr').find('.product-delete').data('product_id');

                let i = $(this).closest('tr').find('.qty-input');
                let price = $(this).closest('tr').find('.product-price');
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let product = cart.find(item => item.id === productId);

                i.val(parseInt(i.val()) + 1);

                updateQuantityInCart(productId, product.quantity + 1);
                price.html('$ ' + product.cost_price * (product.quantity + 1));

                calculateSubtotal();

            });

            //decrease quantity of individual with - button
            $(document).off('click', '.btn-minus').on('click', '.btn-minus', function() {
                let productId = $(this).closest('tr').find('.product-delete').data(
                    'product_id');

                let i = $(this).closest('tr').find('.qty-input');
                if (i.val() > 1) {
                    let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                    let product = cart.find(item => item.id === productId);

                    updateQuantityInCart(productId, product.quantity - 1);
                    i.val(parseInt(i.val()) - 1);

                    $(this).closest('tr').find('.product-price').html(
                        `$${product.cost_price * (product.quantity-1)}`);

                }
                calculateSubtotal();


            });

            $(document).on('click','.payment-modal',function(){
                let payment = $(this).data('payment_method');
                let total = calculateSubtotal();
                $('#checkout_total_amount').val(total);

            })
            $(document).on('input','#paid_amount', function(){
                let paid =parseFloat($(this).val()) ;
                let total =parseFloat($('#checkout_total_amount').val()) ;
                $('#due_amount').val(total-paid);
            })






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
                                style="height: 100px; width:20%; cursor: pointer;">
                                 <img class="w-50 h-100 rounded product-img my-1" src="${image}" alt="img">
                                     <div class="px-3">
                                         <p class="py- lh-sm text-sm">${product.name}</p>
                                                 <hr class="">
                                                 <h1 class="text-sm lh-1 fw-semibold p-1">${product.cost_price}</h1>

                                    </div>
                            </div> `;
                        })

                        $('#product-list').html(productsHtml);
                        //     let paginationHtml = '';
                        //     let links = res.products.links;
                        //     links.forEach(function(link) {
                        //         paginationHtml += `<button 
                    //     class="btn btn-sm rounded-5 ${link.active ? 'btn-primary' : 'btn-outline-primary'} m-1 page-link-btn"
                    //     data-url="${link.url ?? '#'}"
                    //     ${!link.url ? 'disabled' : ''}>
                    //     ${link.label}
                    // </button>`;
                        //     })

                        //     $('#product-list').append('<div class="col-lg-12 text-end ">' + paginationHtml +
                        //         '</div>');

                        $(document).off('click', '.product-card').on('click', '.product-card',
                            function() {
                                let productId = $(this).data('product_id');
                                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || null;
                                if (productExistsInCart(productId)) {
                                    cart.forEach(function(item) {
                                        if (item.id === productId || item.parent_id ===
                                            productId) {
                                            deleteFromPurchaseCart(item.id);
                                        }
                                    });
                                    loadPurchaseItems();
                                    console.log('removed from cart');

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

            } //loadProducts function ends here
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

            //calculation total individual items in cart 
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
                                     <td class="">
                                         <input type="number" name="item_price" class="item_price" value="${product.cost_price}" style="width:70px;">
                                    </td>
                                    <td class='product-price'>
                                        ${product.cost_price*product.quantity}
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
            } //addtToPurchaseCart function ends here 

            //modify product price in cart
            function updateProductPrice(productId, price) {
                let cart = JSON.parse(localStorage.getItem('purchaseCart'));
                let product;
                let newCart = cart.map(function(p) {
                    if (p.id === productId) {
                        p.price = price;
                        product = p;

                    }
                    return p;
                })

                localStorage.setItem('purchaseCart', JSON.stringify(newCart));
                calculateSubtotal();
                return product;
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
                localStorage.setItem('discount', JSON.stringify({
                    'type': 'flat',
                    'value': 0.00
                }));
                localStorage.setItem('shippingCharge', 0);
                // alert('Purchase cart emptied successfully!');
                loadPurchaseItems();
                $('.product-card').removeClass('active');
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




            function calculateSubtotal() {
                let cart = JSON.parse(localStorage.getItem('purchaseCart')) || [];
                let discount = JSON.parse(localStorage.getItem('discount')) || 0;
                let shipping = parseFloat(localStorage.getItem('shippingCharge')) || 0;
                let subtotal = 0;
                let total = 0;

                cart.forEach(function(item) {
                    subtotal += (item.cost_price * item.quantity);
                });
                if (discount) {
                    if (discount.type === 'percentage') {

                        let d = (subtotal * discount.value / 100);
                        $('#discount').val(`${d.toFixed(2)}`);
                        total = subtotal + shipping - d;
                    } else {
                        total = subtotal + shipping - discount.value;
                        $('#discount').val(`${discount.value.toFixed(2)}`);
                    }

                }
                $('#shipping').val(shipping);
                $('#subtotal').text(subtotal.toFixed(2));
                $('#total_amount').text(total.toFixed(2));

                return total;

            }

            //discount calculation
            $('#discount-save').on('click', function() {
                let discountType = $('#discount-type').val();
                let discountAmount = parseFloat($('#discount-input').val()) || 0;

                localStorage.setItem('discount', JSON.stringify({
                    'type': discountType,
                    'value': discountAmount
                }));


                calculateSubtotal();

            });
            $('#shipping').on('input', function() {
                let shippingCharge = parseFloat($(this).val()) || 0;
                // $('#shipping').val(shippingCharge);
                localStorage.setItem('shippingCharge', shippingCharge);

                calculateSubtotal();

            });





        }); //document.ready function ends here

        // Highlight active category button
        $(document).on('click', '.nav-btn', function(e) {
            $('.nav-btn').removeClass('active');
            $(this).addClass('active');
        });
        $(document).on('click', '.product-card', function(e) {
            if ($(this).hasClass('active')) {

                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }

        });


        //supplier select2
        window.S2.auto();

        function loadSupplier() {
            let a = ' {{ route('supplier.recent') }}';
            let recent_supplier;
            $.ajax({
                url: a,
                type: 'GET',
                success: function(res) {
                    recent_supplier = res.supplier;
                    console.log(recent_supplier);
                    $('#supplier')
                        .append(new Option(res.supplier.name, res.supplier.id, true, true))
                        .trigger('change');
                },

                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            })

        }

        // $('#exampleModalCenter').on('shown.bs.modal', function() {
        //     $('#myInput').trigger('focus');
        // });
        // $('#exampleModalCenter1').on('shown.bs.modal', function() {
        //     $('#myInput').trigger('focus');
        // });
    </script>
@endsection
