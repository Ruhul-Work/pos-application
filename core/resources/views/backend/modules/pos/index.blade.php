@extends('backend.layouts.master')
@section('content')
    <div class="row ">

        <div class="product-div col-lg-7 bg-gray " style="height: 80vh; overflow-y: auto;">
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

                    <button class="btn btn-primary btn-sm px-3 text-xs">Featured</button>
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
                <h1 class="text-xl lh-1 fw-semibold p-1">Order List</h1>

                <div>
                    <h6 class="text-xs   p-1 px-3 bg-dark text-white rounded-pill">#ord1247</h6>
                </div>
            </div>
            <hr class=" px-3" style="border-top: 1px dashed #000;">
            <div class="p-1 mt-3">
                <h1 class="text-lg lh-1 fw-semibold p-1">Customer's Information</h1>
                <div class="d-flex gap-2">

                    <div class="col-lg-9 d-flex gap-2">
                        <div class="mt-1">
                            <select class="form-control form-control-sm col-lg-3 js-s2-ajax" name="customer_id"
                                id="customer" data-url="{{ route('customer.select2') }}"
                                data-placeholder="Select Customer">
                                <option id="recent" value="customer" selected>Walk In Customer</option>

                            </select>
                            <div class="invalid-feedback d-block category_id-error" style="display:none"> </div>
                        </div>
                        <div class="p- my-1 d-flex gap-2">
                            <button class="btn btn-success rounded-1 btn-sm" data-bs-toggle="modal"
                                data-bs-target="#customerModal"> <iconify-icon icon="flowbite:users-outline"
                                    class="menu-icon"></iconify-icon>
                            </button>
                            {{-- modal --}}
                            <div class="modal fade" id="customerModal" tabindex="-1"
                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-lg" id="exampleModalCenterTitle">Add Customer
                                            </h5>
                                            <button type="button" class="btn-close " data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="customer_form">
                                            <div class="modal-body p-3">
                                                <label for="" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="customer_name"
                                                    name="name">
                                                <label for="" class="form-label mt-3">Email</label>
                                                <input type="email" min="0" class="form-control"
                                                    id="customer_email" name="email">
                                                <label for="" class="form-label">Phone</label>
                                                <input type="text" class="form-control" id="customer_name"
                                                    name="phone">
                                                <label for="" class="form-label mt-3">Adress</label>
                                                <input type="text" min="0" class="form-control"
                                                    id="customer_email" name="address">
                                            </div>
                                            <div class="modal-footer ">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" data-bs-toggle="modal" class="btn btn-dark btn-sm"
                                                    id="customer_submit">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- modal ends --}}
                            <button class="btn btn-primary rounded-1 btn-sm"><iconify-icon icon="mdi:qrcode-scan"
                                    class="menu-icon"></iconify-icon></button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3 mb-0 rounded-3 p-3 "
                    style="background:#FFEEE9;border:1px solid #E04F16">
                    <div class="">
                        <h1 class="text-md lh-1 fw-semibold ">James Anderson</h1>
                        <p class="text-sm lh-1">Bonus: <span
                                class="bg-info rounded-3 fw-semibold p-1 text-white">148</span>
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
                                <th scope="col" class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm " id="cart_items">


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
                                    <span>Subtotal</span>

                                </td>
                                <td class="text-secondary" id="subtotal">$2300</td>

                            </tr>

                            <tr>
                                <td class="text-secondary">
                                    <span>Shippping</span>
                                    <button class="bg-light rounded-4 border-0 p-1 icon" data-bs-toggle="modal"
                                        data-bs-target="#shippingModal">
                                        <iconify-icon icon="flowbite:edit-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="shippingModal" tabindex="-1"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-lg" id="exampleModalCenterTitle">Shipping
                                                        Charge</h5>
                                                    <button type="button" class="btn-close " data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">

                                                    <label for="" class="form-label ">Amount</label>
                                                    <input type="number" min="0" class="form-control"
                                                        id="shipping-input">
                                                </div>
                                                <div class="modal-footer ">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" data-bs-toggle="modal"
                                                        class="btn btn-dark btn-sm" id="shipping-save">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-secondary"><input type="number" disabled id="shipping" min="0"
                                        value="0" style="width: 80px"></td>

                            </tr>
                            <tr>

                                <td class="text-secondary">
                                    <span>Coupon</span>
                                    <button class="bg-light rounded-4 border-0 p-1 icon" data-bs-toggle="modal"
                                        data-bs-target="#couponModal">
                                        <iconify-icon icon="flowbite:edit-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="couponModal" tabindex="-1"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-lg" id="exampleModalCenterTitle">Coupon
                                                    </h5>
                                                    <button type="button" class="btn-close " data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">

                                                    <label for="" class="form-label ">Available Coupon</label>
                                                    <select name="coupon" id="" class=" form-control">
                                                        <option value="none" selected>Select</option>
                                                        <option value="coupon1">Coupon 1 - 10% off</option>
                                                        <option value="coupon2">Coupon 2 - $20 off</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer ">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" data-bs-toggle="modal"
                                                        class="btn btn-dark btn-sm">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-secondary"><input type="number" disabled id="coupon" min="0"
                                        value="0" style="width: 80px"></td>

                            </tr>
                            <tr>

                                <td class="text-secondary">
                                    <span>Discount</span>
                                    <button type="button" class="bg-light rounded-4 border-0 p-1 icon"
                                        data-bs-toggle="modal" data-bs-target="#discountModal">
                                        <iconify-icon icon="flowbite:edit-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="discountModal" tabindex="-1"
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
                                                    <input type="number" min="0" class="form-control"
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
                                        class="text-danger" style="width: 80px"></td>

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
                        {{-- <button class="btn col-lg-3 payment-btn btn-sm">Cash</button> --}}
                        <button type="button" class=" btn col-lg-3 payment-btn payment-modal-btn btn-sm" data-bs-toggle="modal"
                            data-bs-target="#paymentModal" data-bs-whatever="@getbootstrap" data-payment_method="cash">Cash</button>
                            {{-- payment modal starts here --}}
                        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Checkout Form</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="row">
                                                <div class="mb-3 col-lg-4">
                                                    <label for="checkout_amount" class="col-form-label">Total Amount</label>
                                                    <input type="number" min="0" class="form-control form-control-sm"
                                                        id="checkout_total_amount" disabled value="0">
                                                </div>
                                                <div class="mb-3 col-lg-4">
                                                    <label for="received_amount" class="col-form-label">Paying
                                                        Amount</label>
                                                    <input type="number" min="0" class="form-control form-control-sm"
                                                        id="paid_amount" value="0">
                                                </div>
                                                <div class="mb-3 col-lg-4">
                                                    <label for="received_amount" class="col-form-label">Change</label>
                                                    <input type="number"  disabled min="0" class="form-control form-control-sm"
                                                        id="change_amount" value="$ 0">
                                                </div>
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="received_amount" class="col-form-label">Payment Type</label>
                                                <select name="payment_method" id="payment_method"
                                                    class="form-control form-control-sm">
                                                    <option value="cash" selected>Cash</option>
                                                    <option value="card">Card</option>
                                                    <option value="bkash">Bkash</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="received_amount" class="col-form-label">Payment
                                                    Receiver</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="recipient-name">
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="received_amount" class="col-form-label">Payment Note</label>
                                                <textarea rows="2" class="col-md-12 rounded-3" id="recipient-name"></textarea>
                                            </div>
                                            <div class="mb-3 ">
                                                <label for="received_amount" class="col-form-label">Sale Note</label>
                                                <textarea type="text" rows="2" class="col-md-12 rounded-3" id="recipient-name"></textarea>
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
                        <button class="btn col-lg-3 payment-btn btn-sm payment-modal-btn" data-bs-toggle="modal"
                            data-bs-target="#paymentModal" data-payment_method="bkash">Bkash</button>
                        <button class="btn col-lg-3 payment-btn btn-sm payment-modal-btn" data-bs-toggle="modal"
                            data-bs-target="#paymentModal" data-payment_method="card">Card</button>

                    </div>
                </div>
                <div class="d-flex mt-3 gap-2 mb-3">
                    <button style="padding: 10px"
                        class="btn btn-light border col-lg-6   d-flex align-items-center justify-content-center gap-2"><iconify-icon
                            icon="flowbite:printer-outline payment-modal-btn" class="menu-icon fs-5 "></iconify-icon><span>Print
                            Order</span>
                    </button>
                    <button  data-bs-toggle="modal"
                            data-bs-target="#paymentModal"
                        class="btn btn-danger border payment-modal-btn   col-lg-6 p-1 d-flex align-items-center justify-content-center gap-2"><iconify-icon
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

            <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body justify-content-center ">
                            <div class="d-flex justify-content-center">
                                <iconify-icon icon="mdi:check-circle" class="menu-icon fs-5 text-success " width="40"
                                    height="40"></iconify-icon>
                            </div>

                            <h1 class="text-xl lh-1 text-dark my-3">Payment Completed</h1>
                            <p class="text-sm lh-1 text-secondary">Do you want to Print Receipt for the Completed Order</p>

                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-dark btn-sm rounded-4" data-bs-target="#exampleModalToggle2"
                                data-bs-toggle="modal">Print Recipt</button>
                            <button class="btn btn-warning btn-sm rounded-4" data-bs-target="#exampleModalToggle2"
                                data-bs-toggle="modal">Next Order</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModalToggle2" aria-hidden="true"
                aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body text-dark p-0 px-3">
                            <img src="https://dreamspos.dreamstechnologies.com/html/template/assets/img/logo.svg"
                                class="mt-0" alt="img" style="height: 30px">
                            <h1 class="text-xl mt-3">DK International Private Ltd.</h1>
                            <p class="lh-base text-sm mt-1 text-secondary fw-normal">
                                {{-- <span>Address: Kualalampur, Malayshia</span><br> --}}
                                <span>Phone Number: 01598929775</span><br>
                                <span>Email Address: dk@email.com</span><br>
                            </p>
                            <hr class="my-1">
                            <h1 class="text-md  ">Payment Invoice</h1>
                            <div class="row lh-1 text-sm mt-1 text-start px-3 text-secondary fw-normal ">
                                <p class="col-lg-6">Name: John Doe</p>
                                <p class="col-lg-6">Invoice No: #05418485</p><br>
                                <p class="col-lg-6">Customer Id: 45612</p>
                                <p class="col-lg-6">Date: {{ now() }}</p>
                            </div>
                            <hr>
                            <div class="px-5 ">
                                <table class="table text-start table-borderless text-sm table-sm ">
                                    <thead>
                                        <tr class="py-1">
                                            <th class=""># Item</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class=" fw-normal">
                                        @for ($i = 1; $i < 10; $i++)
                                            <tr class="px-1  lh-lg text-start">
                                                <td class="text-secondary">{{ $i }}. Formal dress shirt</td>
                                                <td class="text-secondary">
                                                    1600
                                                </td>
                                                <td class="text-secondary">
                                                    2
                                                </td>
                                                <td class="text-secondary">
                                                    3200
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <hr>

                            <div class="row px-5 fw-semibold mt-3">
                                <div class="d-flex justify-content-between px-3">
                                    <h1 class="text-md">Subtotal</h1>
                                    <h1 class="text-md px-3">3200</h1>
                                </div>
                                <div class="d-flex justify-content-between px-3 text-danger">
                                    <h1 class="text-md">Discount</h1>
                                    <h1 class="text-md  px-3">- 320</h1>
                                </div>
                                <div class="d-flex justify-content-between px-3">
                                    <h1 class="text-md">Tax</h1>
                                    <h1 class="text-md  px-3">50</h1>
                                </div>

                            </div>
                            <hr class="my-3">

                            <p class="lh-base">**VAT against this challan is payable through central registration.
                                Thank you for your business!</p>
                            <hr>

                            <p>Thank You For Shopping With Us. Please Come Again</p>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-dark btn-sm rounded-4" data-bs-target="#exampleModalToggle"
                                data-bs-toggle="modal">Print Recipt</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-success btn-sm  rounded-4 d-flex align-item-center justify-content-center"
                data-bs-target="#exampleModalToggle" data-bs-toggle="modal">
                <iconify-icon icon="flowbite:cash-outline" class="menu-icon fs-5 "></iconify-icon>Payment</button>
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

        <!-- Modal -->


    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            function loadCartItems() {
                let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
                let count = 0;
                let cartItemsHtml = '';
                cartItems.forEach(function(item) {
                    count += 1;

                    cartItemsHtml += `
                       <tr>
                                    <td class="d-flex align-items-center text-center justify-content-start gap-2 text-secondary ">
                                        <span class="order-item">${stringShortner(item.name,20)}
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
                                        ${item.price}
                                    </td>
                                    <td class="product-price">
                                        ${item.price*item.quantity}
                                    </td>

                                </tr>`;
                });
                $('#cart_items').html(cartItemsHtml);
                $('#total_items').text(count);
                calculateSubtotal();
                // calculateDiscount();
                // calculateTotalAmount();

            } //loadCartItems function ends here
            //delete individual cart item
            $(document).off('click', '.product-delete').on('click', '.product-delete', function() {
                let productId = $(this).data('product_id');
                if (productExistsInCart(productId)) {
                    deleteFromCart(productId);
                    $(this).closest('tr').remove();
                    // loadCartItems();
                } else {
                    alert('Product not found in cart!');
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

                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                let product = cart.find(item => item.id === productId);
                if (product) {
                    let newPrice = product.price * product.quantity;
                    $(this).closest('tr').find('.product-price').html(
                        `${newPrice}`);
                }
                calculateSubtotal();

            });

            //increase quantity of individual item with + button
            $(document).off('click', '.btn-plus').on('click', '.btn-plus', function() {
                let productId = $(this).closest('tr').find('.product-delete').data('product_id');

                let i = $(this).closest('tr').find('.qty-input');
                let price = $(this).closest('tr').find('.product-price');
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                let product = cart.find(item => item.id === productId);

                i.val(parseInt(i.val()) + 1);

                updateQuantityInCart(productId, product.quantity + 1);
                price.html(product.price * (product.quantity + 1));

                calculateSubtotal();

            });

            //decrease quantity of individual with - button
            $(document).off('click', '.btn-minus').on('click', '.btn-minus', function() {
                let productId = $(this).closest('tr').find('.product-delete').data(
                    'product_id');

                let i = $(this).closest('tr').find('.qty-input');
                if (i.val() > 1) {
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
                    let product = cart.find(item => item.id === productId);

                    updateQuantityInCart(productId, product.quantity - 1);
                    i.val(parseInt(i.val()) - 1);

                    $(this).closest('tr').find('.product-price').html(
                        `${product.price * (product.quantity-1)}`);

                }
                calculateSubtotal();


            });






            // Delegate click event for pagination links
            $(document).on('click', '.page-link-btn', function(e) {
                e.preventDefault();

                let url = $(this).data('url');
                if (!url || url === '#') return; // skip disabled links

                loadProducts(url);
                // loadCartItems();
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
            loadCartItems();

            function updatePrice(element, price) {
                a = $(element).closest('tr').find('.product-price');
                a.html(price);
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
                                style="height: 110px; width:20%; cursor: pointer;">
                               <img class="img-fluid h-75 rounded col-lg-5  product-img" src="${image}" alt="img">
                                     <div class="px-3 flex-grow-1">
                                         <p class="lh-sm text-sm fw-semibold">${stringShortner(product.name,15)}</p>
                                               
                                                 <h1 class="text-sm lh-1 fw-semibold px-1 mt-1">${product.price}</h1>

                                    </div>
                            </div> `;
                        })

                        $('#product-list').html(productsHtml);
                        let paginationHtml = '';
                        let links = res.products.links;
                        links.forEach(function(link) {
                            paginationHtml += `<button 
                        class="btn btn-sm rounded-5 ${link.active ? 'btn-primary' : 'btn-outline-primary'} m-1 page-link-btn"
                        data-url="${link.url ?? '#'}"
                        ${!link.url ? 'disabled' : ''}>
                        ${link.label}
                    </button>`;
                        })

                        $('#product-list').append('<div class="col-lg-12 text-end ">' + paginationHtml +
                            '</div>');

                        $(document).off('click', '.product-card').on('click', '.product-card',
                            function() {
                                let productId = $(this).data('product_id');
                                let cart = JSON.parse(localStorage.getItem('cart')) || null;
                                if (productExistsInCart(productId)) {
                                    cart.forEach(function(item) {
                                        if (item.id === productId || item.parent_id ===
                                            productId) {
                                            deleteFromCart(item.id);
                                        }
                                    });
                                    loadCartItems();
                                    // console.log('removed from cart');

                                    $(this).removeClass('active');

                                } else {
                                    addToCart(productId);
                                    $(this).addClass('active');

                                }

                            });

                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }


                });

            } //loadProducts function ends here


            //check if product exists in cart with product id
            function productExistsInCart(productId) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
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
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                $('#total_items').text(cart.length);
            }

            //add product with child items in cart
            function addToCart(productId) {
                let url = "{{ route('product.childProductList', ':parentId') }}".replace(':parentId', productId);
                let childProducts = [];
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        let products = res.products;
                        let notEmpty;
                        let cart = [];
                        if (localStorage.getItem('cart')) {
                            cart = JSON.parse(localStorage.getItem('cart'));
                            notEmpty = JSON.parse(localStorage.getItem('cart'));
                        }
                        childProducts = products.map(function(product) {
                            product['quantity'] = 1;
                            return product;
                        });
                        localStorage.setItem('cart', JSON.stringify(cart.concat(
                            childProducts)));
                        childProducts.map(function(product) {
                            let cartItemsHtml = `
                       <tr>
                                    <td class="d-flex align-items-center justify-content-start gap-2 text-secondary ">
                                        <span class="order-item">${stringShortner(product.name,20)}
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
                                    <td class=''>
                                        ${product.price}
                                    </td>
                                    <td class='product-price'>
                                        ${product.price*product.quantity}
                                    </td>

                                </tr>`;
                            $('#cart_items').append(cartItemsHtml);
                        })
                        if (!notEmpty) {

                            loadCartItems();
                        }
                        calculateSubtotal();
                        totalItemsInCart();

                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            } //addtToCart function ends here 



            //delete product from cart with product id
            function deleteFromCart(productId) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                let updatedCart = cart.filter(function(item) {

                    return item.id !== productId;
                });
                localStorage.setItem('cart', JSON.stringify(updatedCart));
                calculateSubtotal();

            }

            //empty product card from localstorage 
            function emptyCart() {
                localStorage.removeItem('cart');
                localStorage.setItem('discountPos', JSON.stringify({
                    'type': 'flat',
                    'value': 0.00
                }));
                localStorage.setItem('shippingChargePos', 0);
                // alert('cart emptied successfully!');
                loadCartItems();
                $('.product-card').removeClass('active');
            }

            function updateQuantityInCart(productId, newQuantity) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart.forEach(function(item) {
                    if (item.id === productId) {
                        item.quantity = newQuantity;
                    }
                });
                localStorage.setItem('cart', JSON.stringify(cart));
                // loadCartItems();
            }




            function calculateSubtotal() {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                let discount = JSON.parse(localStorage.getItem('discountPos')) || 0;
                let shipping = parseFloat(localStorage.getItem('shippingChargePos')) || 0;
                let subtotal = 0;
                let total = 0;

                cart.forEach(function(item) {
                    subtotal += (item.price * item.quantity);
                });
                if (discount.type === 'percentage') {

                    let d = (subtotal * discount.value / 100);
                    $('#discount').val(`${d.toFixed(2)}`);
                    total = subtotal + shipping - d;
                } else {
                    total = subtotal + shipping - discount.value;
                    $('#discount').val(`${discount.value.toFixed(2)}`);
                }
                $('#shipping').val(`${shipping.toFixed(2)}`);
                $('#subtotal').text(subtotal.toFixed(2));
                $('#total_amount').text(total.toFixed(2));

                return total;



            }

            //discount calculation
            $('#discount-save').on('click', function() {
                let discountType = $('#discount-type').val();
                let discountAmount = parseFloat($('#discount-input').val()) || 0;

                localStorage.setItem('discountPos', JSON.stringify({
                    'type': discountType,
                    'value': discountAmount
                }));


                calculateSubtotal();

            });
            $('#shipping-save').on('click', function() {
                let shippingCharge = parseFloat($('#shipping-input').val()) || 0;
                $('#shipping').val(shippingCharge);
                localStorage.setItem('shippingChargePos', shippingCharge);

                calculateSubtotal();

            });

            //checkout form functionality
             $(document).on('click','.payment-modal-btn',function(){
                let payment = $(this).data('payment_method')??'cash';
                $('#payment_method').val(payment);
                let total = parseFloat(calculateSubtotal()) ;
                $('#checkout_total_amount').val(total.toFixed(2));
                 $('#change_amount').val(0);

            })
            $(document).on('input','#paid_amount', function(){
                let paid =parseFloat($(this).val()) ;
                let total =parseFloat($('#checkout_total_amount').val()) ;
                $('#change_amount').val((total-paid).toFixed(2));
            })





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


        //customer select2
        window.S2.auto();

        function stringShortner(name, length) {
            if (name.length > length)
                return name.slice(0, length) + '...';
            else return name;
        }

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

        $('#exampleModalCenter').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus');
        });
        $('#exampleModalCenter1').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus');
        });

        $('#customer_form').on('submit', function(e) {
            e.preventDefault(); // stop normal form submit

            let formData = $(this).serialize(); // collects all fields

            $.ajax({
                url: '{{ route('customer.store') }}',
                type: 'POST',
                data: formData,
                success: function(res) {
                    $('#customer')
                        .append(new Option(res.name, res.id, true, true))
                        .trigger('change');
                    
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
       
    </script>
@endsection
