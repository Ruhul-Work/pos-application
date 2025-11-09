@extends('backend.layouts.master')
@section('content')
    <div class="row ">

        <div class="product-div col-lg-8 bg-gray " style="height: 80vh; overflow-y: auto;">
            <div class="row justify-content-between">
                {{-- welcome div --}}
                <div class="col-lg-4">
                    <h6 class="text-xl lh-1 fw-semibold">Welcome, {{ Auth::user()->name }}</h6>
                    <p class="text-sm">{{ now()->format('l, d M Y') }}</p>
                </div>
                {{-- input and buttons --}}
                <div class="col-lg-6 d-flex justify-content-end align-items-center gap-2" style="white-space: nowrap;">
                    <input class="border  px-3 fst-italic rounded bg-light form-control" type="text"
                        placeholder="search product" style="width: 240px;" id="product-search-input">

                    <button class="btn btn-dark btn-sm px-3 text-xs d-flex align-items-center justify-content-center gap-1">
                        {{-- <iconify-icon icon="flowbite:tag-outline" class="menu-icon fs-6"></iconify-icon> --}}
                        <span>View All Brands</span>
                    </button>

                    <button class="btn btn-primary btn-sm px-3 text-xs">Featured</button>
                </div>


            </div>
            {{-- categories  --}}
            <div class="g-3 overflow-x-auto d-flex mt-3 category-nav py-1" style="white-space: nowrap;">
                <button class="btn nav-btn active rounded-4 py-1 text-md" data-category-id="">All
                    Categories</button>
                @foreach ($categories as $category)
                    <button class="btn nav-btn  rounded-4 py-1 text-md"
                        data-category-id="{{ $category->id }}">{{ $category->name }}</button>
                @endforeach
               
            </div>
            {{-- products --}}
            <div class="products-div mt-3 row gap-space-between align-item-center justify-content-center" id="product-list">

            </div>

        </div>


        <div class="order-div col-lg-4 bg-white rounded-3 " style="height: 80vh; overflow-y: auto;">

            <div class="d-flex p-1 justify-content-between mt-3">
                <h1 class="text-xl lh-1 fw-semibold p-1">Order List</h1>

                <div>
                    <h6 class="text-xs   p-1 px-3 bg-dark text-white rounded-pill">#ord1247</h6>
                </div>
            </div>
            <hr class=" px-3" style="border-top: 1px dashed #000;">
            <div class="p-1 mt-3">
                <h1 class="text-lg lh-1 fw-semibold p-1">Customer Information</h1>
                <div class="d-flex gap-2">

                    <div class="col-lg-9">
                        <select class=" col-lg-4 js-s2-ajax" name="customer_id" id="customer"
                            data-url="{{ route('customer.select2') }}" data-placeholder="Select Customer">

                        </select>
                        <div class="invalid-feedback d-block category_id-error" style="display:none">
                        </div>
                        <div class="p-2">
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
                            <span>3</span></button>
                    </div>
                    <div>
                        <button class="btn btn-outline-danger btn-xs py-1 text-xs">Clear all</button>
                    </div>

                </div>
                {{-- table --}}
                <div class="p-3">
                    <table class="table table-sm table-borderless  text-gray">
                        <thead class="text-sm fw-semibold">
                            <tr class="table-light rounded-3  px-1">

                                <th scope="col">Item</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm py-1 ">
                            @for ($i = 0; $i < 4; $i++)
                                <tr>

                                    <td class="d-flex align-items-center justify-content-start gap-2 text-secondary ">
                                        <span class="order-item">IPhone 14
                                            pro</span><button class="icon"><iconify-icon icon="mdi:delete"
                                                class="menu-icon fs-7 "></button>
                                    </td>
                                    <td class=" align-items-center justify-content-center gap-2 text-secondary">
                                        <button class="bg-light rounded-4 border-0 p-1 qty-button">
                                            <iconify-icon icon="flowbite:minus-outline"
                                                class="menu-icon fs-7"></iconify-icon>
                                        </button>

                                        <span class="mx-3">1</span>

                                        <button class="bg-light rounded-4 p-1 qty-button">
                                            <iconify-icon icon="flowbite:plus-outline"
                                                class="menu-icon fs-7"></iconify-icon>
                                        </button>
                                    </td>
                                    <td>
                                        $1200
                                    </td>

                                </tr>
                            @endfor
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
                                <td class="text-secondary">$2300</td>

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
                                <td>$2360</td>
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
                <div class="d-flex justify-space-between mt-3 px-1 gap-2 mb-3">
                    <button style="padding: 10px"
                        class="btn btn-light border col-lg-6  d-flex align-items-center justify-content-center gap-2"><iconify-icon
                            icon="flowbite:printer-outline" class="menu-icon fs-5 "></iconify-icon><span>Print
                            Order</span></button>
                    <button
                        class="btn btn-danger border  col-lg-6  d-flex align-items-center justify-content-center gap-2"><iconify-icon
                            icon="flowbite:cart-outline" class="menu-icon fs-5 "></iconify-icon><span>Place
                            Order</span></button>


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
            // Initial load if needed (you may already be doing this)
            loadProducts("{{ route('product.productsList') }}");

            // Delegate click event for pagination links
            $(document).on('click', '#pagination a', function(e) {
                e.preventDefault();
                loadProducts($(this).attr('href'));
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

            // Reusable function to load products
            function loadProducts(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#product-list').html(res.html);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
        // Highlight active category button
        $(document).on('click', '.nav-btn', function(e) {
            $('.nav-btn').removeClass('active');
            $(this).addClass('active');
        });

        //customer select2
        window.S2.auto();
    </script>
@endsection
