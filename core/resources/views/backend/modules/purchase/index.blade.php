@extends('backend.layouts.master')
@section('content')
    <div class="row ">

        <div class="product-div col-lg-8 bg-gray " style="height: 100vh; overflow-y: auto;">
            <div class="row justify-content-between">
                {{-- welcome div --}}
                <div class="col-lg-4">
                    <h6 class="text-xl lh-1 fw-semibold">Welcome, Ruhul Amin</h6>
                    <p class="text-sm">December 24, 2024</p>
                </div>
                {{-- input and buttons --}}
                <div class="col-lg-6 d-flex justify-content-end align-items-center gap-2">
                    <input class="border  px-3 rounded bg-light form-control" type="text" placeholder="search product"
                        style="width: 150px;">

                    <button class="btn btn-dark btn-sm px-3 text-xs d-flex align-items-center justify-content-center gap-1">
                        {{-- <iconify-icon icon="flowbite:tag-outline" class="menu-icon fs-6"></iconify-icon> --}}
                        <span>View All Brands</span>
                    </button>

                    <button class="btn btn-primary btn-sm px-3 text-xs">Featured</button>
                </div>


            </div>
            {{-- categories  --}}
            <div class="g-3">
                <button class="btn btn-dark rounded-5 py-1 text-sm btn-sm ">All Categories</button>
                <button class="btn nav-btn  rounded-4 py-1 text-sm btn- ">Headphone</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Shoes</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Mobile</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Headphone</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Shoes</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Mobile</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Headphone</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Shoes</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Mobile</button>
                <button class="btn nav-btn rounded-4 py-1 text-sm btn-sm ">Shoes</button>


            </div>
            <div class="products-div mt-3 row gap-space-between  h-auto align-item-center justify-content-center">
                {{-- @for ($i = 0; $i < 6; $i++)
                <div class="product-card col-lg-3 bg-white rounded-3 p-3 border">
                    <img class="img-fluid rounded-3" src="https://dreamspos.dreamstechnologies.com/html/template/assets/img/products/pos-product-01.png" alt="img">
                    <p class="mt-4 lh-1">Mobiles</p>
                    <h6 class="text-md lh-1 fw-semibold">IPhone 14 64GB</h6>
                    <hr class="my-3">
                    <h1 class="text-md lh-1 fw-semibold">$1200</h1>

                </div>
                @endfor --}}

                @for ($i = 10; $i < 28; $i++)
                    <div class="product-card  bg-white rounded-3 m-3 d-flex p-3 " style="height: 150px; width:30%">
                        <img class="img-fluid rounded col-lg-6 product-img"
                            src="https://dreamspos.dreamstechnologies.com/html/template/assets/img/products/pos-product-{{ $i }}.jpg"
                            alt="img">
                        <div class="px-3">
                            <p class="py-1 lh-1">Mobiles<span class="text-md lh-1 py-1 fw-semibold my-1">IPhone 14
                                    64GB</span></p>
                            <hr class="my-1 lh-1">
                            <h1 class="text-sm lh-1 fw-semibold p-1">$1200</h1>

                        </div>

                    </div>
                @endfor


            </div>


        </div>


        <div class="order-div col-lg-4 bg-white rounded-3 " style="height: 100vh; overflow-y: auto;">

            <div class="d-flex p-1 justify-content-between mt-3">
                <h1 class="text-xl lh-1 fw-semibold p-1">Purchase List</h1>

                <div>
                    <h6 class="text-xs   p-1 px-3 bg-dark text-white rounded-pill">#ord1247</h6>
                </div>
            </div>
            <hr class=" px-3" style="border-top: 1px dashed #000;">
            <div class="p-1 mt-3">
                <h1 class="text-lg lh-1 fw-semibold p-1">Purchase Issued By</h1>
                <div class="d-flex gap-2">

                    <div class="col-lg-9">
                        <select class="form-control form-control-sm col-lg-3 " name="" id="">
                            <option value="">Admin</option>
                            <option value="">Jahid Hasan</option>
                            <option value="">Tanvir Hasan</option>
                        </select>
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
                    <p class="text-sm lh-1">Bonus: <span class="bg-info rounded-3 fw-semibold p-1 text-white">148</span> |
                        Loyality: <span class="bg-success rounded-3 fw-semibold p-1 text-white">520</span></p>
                </div>
                <div> <button class="btn btn-danger btn-xs py-1 text-xs ">Apply</button></div>

            </div>
            <hr class="my-3">
            {{-- order details --}}
            <div class="d-flex justify-content-between p-3">

                <div class="d-flex gap-2">
                    <h1 class="text-md lh-1 fw-semibold mt-1 p-2">Purchase Details</h1>
                    <button class="btn btn-outline-secondary border btn-sm px-1 py-0 disabled">Items :
                        <span>3</span></button>
                </div>
                <div>
                    <button class="btn btn-danger btn-xs py-1 text-xs">Clear all</button>
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
                                        <iconify-icon icon="flowbite:minus-outline" class="menu-icon fs-7"></iconify-icon>
                                    </button>

                                    <span class="mx-3">1</span>

                                    <button class="bg-light rounded-4 p-1 qty-button">
                                        <iconify-icon icon="flowbite:plus-outline" class="menu-icon fs-7"></iconify-icon>
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
            <div class="d-flex  mt-3 px-3 gap-2 mb-3">
                <button
                    class="btn btn-light border btn-sm col-lg-6 p-1 d-flex align-items-center justify-content-center gap-2"><iconify-icon
                        icon="flowbite:printer-outline" class="menu-icon fs-5 "></iconify-icon><span>Print
                        Order</span></button>
                <button
                    class="btn btn-dark border btn-sm col-lg-6 p-1 d-flex align-items-center justify-content-center gap-2"><iconify-icon
                        icon="flowbite:cart-outline" class="menu-icon fs-5 "></iconify-icon><span>Place
                        Order</span></button>


            </div>


        </div>

    </div>
@endsection
