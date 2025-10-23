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

            @permgroup(['usermanage.users', 'rbac.permissions', 'rbac.role'])
                <li class="sidebar-menu-group-title">User Management</li>
                @permgroup(['usermanage.users']) {{-- prefix ভিত্তিক হেডিং অটো-শো/হাইড --}}
                    {{-- @if (can_route('usermanage.users.index')) you can use this system if you want @prem and @if both system are work --}}

                    @perm('usermanage.users.index')
                        <li class="{{ Route::is('usermanage.users.index') ? 'active' : '' }}">
                            <a href="{{ route('usermanage.users.index') }}">
                                <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                <span>Users</span>
                            </a>

                            {{-- <ul class="sidebar-submenu">

                                @perm('usermanage.users.index')

                                    <li>
                                        <a class="{{ Route::is('usermanage.users.index') ? 'active' : '' }}"
                                            href="{{ route('usermanage.users.index') }}">
                                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List
                                        </a>
                                    </li>
                                @endperm

                                @perm('usermanage.users.create')
                                    <li>
                                        <a class="{{ Route::is('usermanage.users.create') ? 'active' : '' }}"
                                            href="{{ route('usermanage.users.create') }}">
                                            <i class="ri-circle-fill circle-icon text-success-main w-auto"></i> Add User
                                        </a>
                                    </li>
                                @endperm



                               </ul> --}}
                        </li>
                    @endperm
                @endpermgroup

                {{-- Role management menu --}}
                @permgroup(['rbac.role'])
                    <li class=" {{ Route::is('rbac.role.list') ? 'active' : '' }}">
                        <a href="{{ route('rbac.role.list') }}">
                            <i class="ri-user-settings-line text-xl me-14 d-flex w-auto"></i>
                            <span>Role</span>
                        </a>
                    </li>
                @endpermgroup

                {{-- Permission management menu --}}
                @permgroup(['rbac.permissions'])
                    @perm('rbac.permissions.index')
                        <li class=" {{ Route::is('rbac.permissions.index') ? 'active' : '' }}">
                            <a href="{{ route('rbac.permissions.index') }}">
                                <i class="ri-shield-keyhole-line me-1"></i>
                                <span>Permission</span>
                            </a>
                        </li>
                    @endperm
                @endpermgroup

                {{-- Firewall management menu --}}
                @permgroup(['security.firewall'])
                    @perm('security.firewall.index')
                        <li class="{{ Route::is('security.firewall.index') ? 'active' : '' }}">
                            <a href="{{ route('security.firewall.index') }}">
                                <i class="ri-fire-line me-1"></i>
                                <span>Firewall</span>
                            </a>

                        </li>
                    @endperm
                @endpermgroup


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
            {{-- branch management module end --}}

            {{-- organization settings module start --}}

            @permgroup(['org.btypes'])
                <li class="sidebar-menu-group-title">Organization</li>
                <li class="dropdown {{ Route::is('org.btypes.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="mdi:shape" class="menu-icon"></iconify-icon>
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
                </li>


                {{-- Location managemnt module start --}}

                @permgroup(['org.branches'])
                    <li class="sidebar-menu-group-title">Location Management</li>
                    @permgroup(['org.branches'])
                        @perm('org.branches.index')
                            <li class=" dropdown ">
                                <a href="javascript:void(0)">
                                    <i class="ri-map-pin-line"></i>
                                    <span>Countries</span>
                                </a>

                                <ul class="sidebar-submenu">

                                    @perm('org.branches.index')
                                        <li class="mb-8">
                                            <a class="{{ Route::is('country.countries.index') ? 'active' : '' }}"
                                                href="{{ route('country.countries.index') }}">
                                                <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                                <span>Country List</span>
                                            </a>
                                        </li>
                                        <li class="mb-8">
                                            <a class="{{ Route::is('division.divisions.index') ? 'active' : '' }}"
                                                href="{{ route('division.divisions.index') }}">
                                                <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                                <span>Division List</span>
                                            </a>
                                        </li>
                                        <li class="mb-8">
                                            <a class="{{ Route::is('district.districts.index') ? 'active' : '' }}"
                                                href="{{ route('district.districts.index') }}">
                                                <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                                <span>District List</span>
                                            </a>
                                        </li>
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
                        @endperm
                    @endpermgroup
                @endpermgroup

                {{-- location managemnt module end --}}

                {{-- category managemnt module start --}}
              
              @permgroup(['product.products'])
                    <li class="sidebar-menu-group-title">Item Management</li>
                  @permgroup(['product.products']) 
                         @perm('product.products.index') 
                            <li class=" dropdown ">
                                <a href="javascript:void(0)">
                                    <i class="ri-box-3-line"></i>
                                    <span>Category</span>
                                </a>

                                <ul class="sidebar-submenu">

                                  
                                     <li class="mb-8">
                                            <a class="{{ Route::is('category-type.category-types.index') ? 'active' : '' }}"
                                                href="{{ route('category-type.category-types.index') }}">
                                                <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                                <span>Category Type</span>
                                            </a>
                                        </li>

                                        <li class="mb-8">
                                            <a class="{{ Route::is('category.categories.index') ? 'active' : '' }}"
                                                href="{{ route('category.categories.index') }}">
                                                <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                                <span>Category List</span>
                                            </a>
                                        </li>
                                        <li class="mb-8">
                                            <a class="{{ Route::is('subcategory.subcategories.index') ? 'active' : '' }}"
                                                href="{{ route('subcategory.subcategories.index') }}">
                                                <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                                                <span>Subcategory List</span>
                                            </a>
                                        </li>
                                   
                                </ul>
                            <li class="mb-8">
                                <a class="{{ Route::is('brand.brands.index') ? 'active' : '' }}"
                                    href="{{ route('brand.brands.index') }}">
                                    <iconify-icon icon="ri-price-tag-3-line" class="menu-icon"></iconify-icon>
                                    <span>Brand List</span>
                                </a>
                            </li>
                            </li>

                            <li class="{{ Route::is('units.index') ? 'active' : '' }}">
                                <a href="{{ route('units.index') }}">
                                    <i class="ri-weight-line"></i>
                                    <span>Unit</span>
                                </a>
                           </li>

                            <li class="{{ Route::is('color.colors.index') ? 'active' : '' }}">
                                <a href="{{ route('color.colors.index') }}">
                                    <i class="ri-palette-line"></i>
                                    <span>Color</span>
                                </a>
                           </li>

                            </li>

                            <li class="{{ Route::is('sizes.index') ? 'active' : '' }}">
                                <a href="{{ route('sizes.index') }}">
                                    <i class="ri-ruler-line text-xl me-14 d-flex w-auto"></i>
                                    <span>Sizes</span>
                                </a>
                            </li>

                             <li class="{{ Route::is('product-type.product-types.index') ? 'active' : '' }}">
                                <a href="{{ route('product-type.product-types.index') }}">
                                    <i class="ri-function-line text-xl me-14 d-flex w-auto"></i>
                                    <span>Product Type</span>
                                </a>
                            </li>
                             <li class="{{ Route::is('paper_quality.index') ? 'active' : '' }}">
                                <a href="{{ route('paper_quality.index') }}">
                                    <i class="ri-book-open-line text-xl me-14 d-flex w-auto"></i>
                                    <span>Paper Quality</span>
                                </a>
                            </li>
                                 
                                  
                             <li class="{{ Route::is('product.products.index') ? 'active' : '' }}">
                                <a href="{{ route('product.products.index') }}">
                                    <i class="ri-shopping-bag-3-line text-xl me-14 d-flex w-auto"></i>
                                    <span>Product</span>
                                </a>
                            </li>
                      
                            
                        @endperm
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
                                @perm('inventory.openingStock.index')
                                    <li class="{{ Route::is('inventory.openingStock.index') ? 'active' : '' }}">
                                    <a href="{{ route('inventory.openingStock.index') }}">
                                        <i class="ri-upload-2-line"></i>
                                        <span>Opening Stock</span>
                                    </a>
                                    </li>
                                @endperm
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

            

        @endpermgroup

        </ul>


    </div>
</aside>
