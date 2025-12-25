<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('home') }}" class="sidebar-logo">
            <img src="{{ asset('theme/admin/assets/images/logo1.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('theme/admin/assets/images/logo1.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('theme/admin/assets/images/bintel.png') }}" alt="site logo" class="logo-icon">
        </a>

    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">

                    <li>
                        <a href="{{ route('backend.dashboard') }}">
                            <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> POS (Admin)
                        </a>
                    </li>


                </ul>
            </li>
            {{-- user management module start --}}

            @permgroup(['usermanage.users', 'rbac.permissions', 'rbac.role', 'security.firewall'])

                <li class="sidebar-menu-group-title">User Management</li>

                {{-- Dropdown parent --}}
                <li
                    class="dropdown {{ Route::is('usermanage.users.*') || Route::is('rbac.*') || Route::is('security.firewall.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="-toggle d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                            <span>User</span>
                        </div>
                    </a>

                    {{-- Submenu items --}}
                    <ul class="sidebar-submenu">

                        {{-- Users --}}
                        @perm('usermanage.users.index')
                            <li class="{{ Route::is('usermanage.users.index') ? 'active' : '' }}">
                                <a href="{{ route('usermanage.users.index') }}">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                        @endperm

                        {{-- Roles --}}
                        @perm('rbac.role.list')
                            <li class="{{ Route::is('rbac.role.list') ? 'active' : '' }}">
                                <a href="{{ route('rbac.role.list') }}">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    <span>Roles</span>
                                </a>
                            </li>
                        @endperm

                        {{-- Permissions --}}
                        @perm('rbac.permissions.index')
                            <li class="{{ Route::is('rbac.permissions.index') ? 'active' : '' }}">
                                <a href="{{ route('rbac.permissions.index') }}">
                                    <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                    <span>Permissions</span>
                                </a>
                            </li>
                        @endperm

                        {{-- Firewall --}}
                        @perm('security.firewall.index')
                            <li class="{{ Route::is('security.firewall.index') ? 'active' : '' }}">
                                <a href="{{ route('security.firewall.index') }}">
                                    <i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                    <span>Firewall</span>
                                </a>
                            </li>
                        @endperm

                    </ul>
                </li>
            @endpermgroup


            {{-- branch management module start --}}
            @permgroup(['org.branches'])
                <li class="sidebar-menu-group-title">Branch Management</li>
                @permgroup(['org.branches'])
                    @perm('org.branches.index')
                        <li class=" dropdown ">
                            <a href="javascript:void(0)">
                                <i class="ri-store-3-line"></i>
                                <span>Branch</span>
                            </a>

                            <ul class="sidebar-submenu">

                                @perm('org.branches.index')
                                    <li>
                                        <a class="{{ Route::is('org.branches.index') ? 'active' : '' }}"
                                            href="{{ route('org.branches.index') }}">
                                            <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                            <span>Branch List</span>
                                        </a>
                                    </li>
                                @endperm
                            </ul>
                        </li>
                    @endperm
                @endpermgroup
            @endpermgroup

            @permgroup(['purchase', 'pos', 'paymentTypes', 'expenseCategories', 'expenses'])
                <li class="sidebar-menu-group-title">Sale Management</li>

                @perm('pos.index')
                    <li>
                        <a class="{{ Route::is('pos.index') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                            <iconify-icon icon="mdi:point-of-sale" class="menu-icon"></iconify-icon>
                            <span>POS</span>
                        </a>
                    </li>
                @endperm


                @perm('purchase.index')
                    <li>
                        <a class="{{ Route::is('purchase.index') ? 'active' : '' }}" href="{{ route('purchase.index') }}">
                            <iconify-icon icon="tabler:shopping-cart" class="menu-icon"></iconify-icon>
                            <span>Purchase</span>
                        </a>
                    </li>
                @endperm
                @perm('purchase.create')
                    <li>
                        <a class="{{ Route::is('purchase.orders.create') ? 'active' : '' }}" href="{{ route('purchase.orders.create') }}">
                            <iconify-icon icon="tabler:shopping-cart" class="menu-icon"></iconify-icon>
                            <span>Add Purchase</span>
                        </a>
                    </li>
                @endperm



                @perm('paymentTypes.index')
                    <li>
                        <a class="{{ Route::is('paymentTypes.index') ? 'active' : '' }}"
                            href="{{ route('paymentTypes.index') }}">
                            <iconify-icon icon="mdi:credit-card-outline" class="menu-icon"></iconify-icon>
                            <span>Payment Type</span>
                        </a>
                    </li>
                @endperm


                @perm('expenseCategories.index')
                    <li>
                        <a class="{{ Route::is('expenseCategories.index') ? 'active' : '' }}"
                            href="{{ route('expenseCategories.index') }}">
                            <iconify-icon icon="mdi:finance" class="menu-icon"></iconify-icon>
                            <span>Expense Categories</span>
                        </a>
                    </li>
                @endperm
                @perm('expenses.index')
                    <li>
                        <a class="{{ Route::is('expenses.index') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                            <iconify-icon icon="mdi:cash-multiple" class="menu-icon"></iconify-icon>
                            <span>Expense
                            </span>
                        </a>
                    </li>
                @endperm



            @endpermgroup

            @permgroup(['org.btypes', 'company_setting'])
                <li class="sidebar-menu-group-title">Organization</li>
                @permgroup(['org.btypes'])
                    <li class="dropdown {{ Route::is('org.btypes.*') ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:shape-outline" class="menu-icon"></iconify-icon>
                            <span>Business Types</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @perm('org.btypes.index')
                                <li>
                                    <a class="{{ Route::is('org.btypes.index') ? 'active' : '' }}"
                                        href="{{ route('org.btypes.index') }}">
                                        <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Types List
                                    </a>
                                </li>
                            @endperm
                        </ul>
                    @endpermgroup
                    @perm('company_setting.index')
                    <li>
                        <a class="{{ Route::is('company_setting.index') ? 'active' : '' }}"
                            href="{{ route('company_setting.index') }}">
                            <iconify-icon icon="mdi:cog" class="menu-icon"></iconify-icon> <span>Company Setting</span>
                        </a>
                    </li>
                @endperm
                </li>

            @endpermgroup
            {{-- Location managemnt module start --}}

            @permgroup(['country', 'division', 'district', 'upazila'])
                <li class="sidebar-menu-group-title">Location Management</li>
                @permgroup(['country', 'division', 'district', 'upazila'])
                    <li class=" dropdown ">
                        <a href="javascript:void(0)">
                            <i class="ri-map-pin-line"></i>
                            <span>Countries</span>
                        </a>

                        <ul class="sidebar-submenu">

                            @perm('country.countries.index')
                                <li class="mb-8">
                                    <a class="{{ Route::is('country.countries.index') ? 'active' : '' }}"
                                        href="{{ route('country.countries.index') }}">
                                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                        <span>Country List</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('division.divisions.index')
                                <li class="mb-8">
                                    <a class="{{ Route::is('division.divisions.index') ? 'active' : '' }}"
                                        href="{{ route('division.divisions.index') }}">
                                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                        <span>Division List</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('district.districts.index')
                                <li class="mb-8">
                                    <a class="{{ Route::is('district.districts.index') ? 'active' : '' }}"
                                        href="{{ route('district.districts.index') }}">
                                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                        <span>District List</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('upazila.upazilas.index')
                                <li class="mb-8">
                                    <a class="{{ Route::is('upazila.upazilas.index') ? 'active' : '' }}"
                                        href="{{ route('upazila.upazilas.index') }}">
                                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                        <span>Upazila List</span>
                                    </a>
                                </li>
                            @endperm

                        </ul>
                    </li>
                @endpermgroup
            @endpermgroup

            {{-- location managemnt module end --}}

            {{-- category managemnt module start --}}

            @permgroup([
                'product.products',
                'customer',
                'supplier',
                'category-type.category-types',
                'category.categories',
                'subcategory.subcategories',
                'brand.brands',
                'color.colors',
                'units',
                'paper_quality',
                'sizes'
            ])
                <li class="sidebar-menu-group-title">Item Management</li>
                @permgroup(['customer', 'supplier'])

                    <li class="{{ Route::is('coupon.index') ? 'active' : '' }}">
                        <a href="{{ route('coupon.index') }}">
                             <iconify-icon icon="mdi:ticket-percent-outline" class="menu-icon"></iconify-icon>
                            <span>Coupon</span>
                        </a>
                    </li>


                    <li class="dropdown {{ Route::is('org.btypes.*') ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:account-circle-outline" class="menu-icon"></iconify-icon>
                            <span>Contact</span>
                        </a>
                        <ul class="sidebar-submenu">
                            @perm('supplier.index')
                                <li class="{{ Route::is('supplier.index') ? 'active' : '' }}">
                                    <a href="{{ route('supplier.index') }}">
                                        <i class="ri-truck-line text-xl me-14 d-flex w-auto"></i>
                                        <span>Suppliers</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('customer.index')
                                <li class="{{ Route::is('customer.index') ? 'active' : '' }}">
                                    <a href="{{ route('customer.index') }}">
                                        <i class="ri-group-line text-xl me-14 d-flex w-auto"></i>
                                        <span>Customers</span>
                                    </a>
                                </li>
                            @endperm
                        </ul>
                    </li>
                @endpermgroup

                @permgroup([
                    'product',
                    'category-type',
                    'category',
                    'subcategory',
                    'brand',
                    'color',
                    'units',
                    'product-type',
                    'paper_quality',
                    'sizes'
                ])
                    <li class="dropdown {{ Route::is('org.btypes.*') ? 'active' : '' }}">
                        <a href="javascript:void(0)">
                            <iconify-icon icon="mdi:package-variant-closed" class="menu-icon"></iconify-icon>
                            <span>Product</span>
                        </a>

                        <ul class="sidebar-submenu">

                            @perm('product.products.index')
                                <li class="{{ Route::is('product.products.index') ? 'active' : '' }}">
                                    <a href="{{ route('product.products.index') }}">
                                        <i class="ri-shopping-bag-3-line text-xl me-14 d-flex w-auto"></i>
                                        <span>All Products</span>
                                    </a>
                                </li>
                            @endperm

                            @permgroup(['category-type', 'category', 'subcategory'])
                                <li class="dropdown">
                                    <a href="javascript:void(0)">
                                        <iconify-icon icon="mdi:package-variant-closed" class="menu-icon"></iconify-icon>
                                        <span>Categories</span>
                                    </a>
                                    <ul class="sidebar-submenu">
                                        @perm('category-type')
                                            <li class="mb-8">
                                                <a class="{{ Route::is('category-type.category-types.index') ? 'active' : '' }}"
                                                    href="{{ route('category-type.category-types.index') }}">
                                                    <iconify-icon icon="flowbite:users-group-outline"
                                                        class="menu-icon"></iconify-icon>
                                                    <span>Category Type</span>
                                                </a>
                                            </li>
                                        @endperm
                                        @perm('category')
                                            <li class="mb-8">
                                                <a class="{{ Route::is('category.categories.index') ? 'active' : '' }}"
                                                    href="{{ route('category.categories.index') }}">
                                                    <iconify-icon icon="flowbite:users-group-outline"
                                                        class="menu-icon"></iconify-icon>
                                                    <span>Category List</span>
                                                </a>
                                            </li>
                                        @endperm
                                        @perm('subcategory')
                                            <li class="mb-8">
                                                <a class="{{ Route::is('subcategory.subcategories.index') ? 'active' : '' }}"
                                                    href="{{ route('subcategory.subcategories.index') }}">
                                                    <iconify-icon icon="flowbite:users-group-outline"
                                                        class="menu-icon"></iconify-icon>
                                                    <span>Subcategory List</span>
                                                </a>
                                            </li>
                                        @endperm
                                    </ul>
                                </li>
                            @endpermgroup
                            @perm('color.colors.index')
                                <li class="{{ Route::is('color.colors.index') ? 'active' : '' }}">
                                    <a href="{{ route('color.colors.index') }}">
                                        <i class="ri-palette-line"></i>
                                        <span>Color</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('product.products.barcode')
                                <li class="{{ Route::is('product.products.barcode') ? 'active' : '' }}">
                                    <a href="{{ route('product.products.barcode') }}">
                                        <i class="ri-barcode-line"></i>
                                        <span>Barcode / Label Print</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('sizes.index')
                                <li class="{{ Route::is('sizes.index') ? 'active' : '' }}">
                                    <a href="{{ route('sizes.index') }}">
                                        <i class="ri-ruler-line text-xl me-14 d-flex w-auto"></i>
                                        <span>Sizes</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('paper_quality.index')
                                <li class="{{ Route::is('paper_quality.index') ? 'active' : '' }}">
                                    <a href="{{ route('paper_quality.index') }}">
                                        <i class="ri-book-open-line text-xl me-14 d-flex w-auto"></i>
                                        <span>Paper Quality</span>
                                    </a>
                                </li>
                            @endperm
                            @perm('brand.brands.index')
                                <li class="mb-8">
                                    <a class="{{ Route::is('brand.brands.index') ? 'active' : '' }}"
                                        href="{{ route('brand.brands.index') }}">
                                        <iconify-icon icon="ri-price-tag-3-line" class="menu-icon"></iconify-icon>
                                        <span>Brand List</span>
                                    </a>
                                </li>
                            @endperm
                    </li>

                    @perm('units.index')
                        <li class="{{ Route::is('units.index') ? 'active' : '' }}">
                            <a href="{{ route('units.index') }}">
                                <i class="ri-weight-line"></i>
                                <span>Unit</span>
                            </a>
                        </li>
                    @endperm

                    @perm('product-type.product-types.index')
                        <li class="{{ Route::is('product-type.product-types.index') ? 'active' : '' }}">
                            <a href="{{ route('product-type.product-types.index') }}">
                                <i class="ri-function-line text-xl me-14 d-flex w-auto"></i>
                                <span>Product Type</span>
                            </a>
                        </li>
                    @endperm
                </ul>
                </li>





            @endpermgroup
        @endpermgroup
        {{-- category managemnt module end --}}

        {{-- Stock/warehouse managemnt module start --}}

        {{-- @permgroup(['org.branches'])
                <li class="sidebar-menu-group-title">Stock Management</li>
                @permgroup(['org.branches'])
                    @perm('org.branches.index')
                        <li class=" dropdown ">
                            <a href="javascript:void(0)">
                                <i class="ri-store-3-line"></i>
                                <span>Stock</span>
                            </a>

                            <ul class="sidebar-submenu">

                                @perm('org.branches.index')
                                    <li class="{{ Route::is('warehouses.*') ? 'active' : '' }}">
                                    <a href="{{ route('warehouses.index') }}">
                                        <i class="ri-building-4-line me-1"></i>
                                        <span>Warehouses</span>
                                    </a>
                                    </li>

                                @endperm
                            </ul>
                        </li>
                    @endperm
                @endpermgroup
            @endpermgroup --}}
        {{-- stock management module end --}}



        {{-- inventory module start --}}

        @permgroup(['inventory'])
            <li class="sidebar-menu-group-title">Inventory</li>
            @permgroup(['inventory'])
                <li class=" dropdown ">
                    <a href="javascript:void(0)">
                        <i class="ri-stock-line"></i>
                        <span>Stock</span>
                    </a>

                    <ul class="sidebar-submenu">
                        {{-- warehouse --}}
                        @perm('inventory.warehouses.index')
                            <li class="{{ Route::is('inventory.warehouses.index') ? 'active' : '' }}">
                                <a href="{{ route('inventory.warehouses.index') }}">
                                    <i class="ri-building-4-line me-1"></i>
                                    <span>Warehouses</span>
                                </a>
                            </li>
                        @endperm
                        {{-- opening stock --}}
                        {{-- @perm('inventory.openingStock.index')
                            <li class="{{ Route::is('inventory.openingStock.index') ? 'active' : '' }}">
                                <a href="{{ route('inventory.openingStock.index') }}">
                                    <i class="ri-upload-2-line"></i>
                                    <span>Opening Stock</span>
                                </a>
                            </li>
                        @endperm --}}
                        {{-- stock adjustment --}}
                        @perm('inventory.adjustments.index')
                            <li class="{{ Route::is('inventory.adjustments.index') ? 'active' : '' }}">
                                <a href="{{ route('inventory.adjustments.index') }}">
                                    <i class="ri-equalizer-line"></i>
                                    <span>Adjustments</span>
                                </a>
                            </li>
                        @endperm
                        {{-- stock transfer --}}
                        @perm('inventory.transfers.index')
                            <li class="{{ Route::is('inventory.transfers.index') ? 'active' : '' }}">
                                <a href="{{ route('inventory.transfers.index') }}">
                                    <i class="ri-arrow-left-right-line"></i>
                                    <span>Transfers</span>
                                </a>
                            </li>
                        @endperm
                    </ul>
                </li>
            @endpermgroup

        @endpermgroup





        </ul>


    </div>
</aside>
