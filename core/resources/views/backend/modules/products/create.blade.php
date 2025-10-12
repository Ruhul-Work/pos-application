@extends('backend.layouts.master')

@section('meta')
    <title>Create new Product </title>
@endsection

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h6 class="fw-semibold mb-0">Product Management</h6>
            <p class="fw-semibold mb-0">Create new Product</p>
        </div>

        <ul class="d-flex align-items-center gap-2 mb-0">
            <li class="fw-medium">
                <a href="{{ route('backend.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Product</li>
        </ul>

        {{-- Button on a new line (right aligned) --}}
        <div class="text-end w-100 ">
            <a href="{{ route('product.products.index') }}" class="btn btn-secondary bg-dark "><i data-feather="arrow-left"
                    class="me-2"></i>Back to
                Products</a>
        </div>
    </div>

    <form id="product_form" method="post">
        @csrf

        <div class="card ">
            <div class="card-body  ">
                <div class="accordion-card-one accordion mb-6" id="accordionExample">
                    <div class="accordion-item">
                        <div class="accordion-header" id="headingOne">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                aria-controls="collapseOne">
                                <div class="addproduct-icon">
                                    <h6><i data-feather="info" class="add-info"></i><span>Basic Information</span></h6>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down"
                                            class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">

                                <div class="row">


                                    <div class="col-lg-5 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Name <span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}">

                                        </div>

                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="mb-3 ">
                                            <label class="form-label">Slug<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" id="slug" name="slug">
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-16">
                                        <label class="form-label text-sm mb-8">Category Type</label>
                                        <select name="category_type_id" id="category_type"
                                            class="form-control js-category-select"></select>
                                        <div class="invalid-feedback d-block category_id-error" style="display:none">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-16">
                                        <label class="form-label text-sm mb-8">Category</label>
                                        <select name="category_id" id="category"
                                            class="form-control js-category-select"></select>
                                        <div class="invalid-feedback d-block category_id-error" style="display:none">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-16">
                                        <label class="form-label text-sm mb-8">Sub-Category</label>
                                        <select name="subcategory_id" id="subcategory"
                                            class="form-control js-category-select"></select>
                                        <div class="invalid-feedback d-block category_id-error" style="display:none">
                                        </div>
                                    </div>



                                    {{-- <div class="row"> --}}
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label text-sm mb-8">Product Type</label>
                                        <select name="product_type_id" id="product_type"
                                            class="form-control js-product-type-select"></select>
                                        <div class="invalid-feedback d-block product_type_id-error" style="display:none">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label text-sm mb-8">Unit</label>
                                        <select name="unit_id" id="unit"
                                            class="form-control js-product-type-select"></select>
                                        <div class="invalid-feedback d-block brand_type_id-error" style="display:none">
                                        </div>
                                    </div>



                                    {{-- <div class="row"> --}}
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label text-sm mb-8">Brand</label>
                                        <select name="brand_id" id="brand"
                                            class="form-control js-product-type-select"></select>
                                        <div class="invalid-feedback d-block brand_type_id-error" style="display:none">
                                        </div>
                                    </div>

                                    {{-- <div class="row"> --}}
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label text-sm mb-8">Color</label>
                                        <select name="color_id" id="color"
                                            class="form-control js-color-type-select"></select>
                                        <div class="invalid-feedback d-block color_type_id-error" style="display:none">
                                        </div>
                                    </div>





                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 ">
                                            <label class="form-label">Purchase Price<span
                                                    class="star-sign">*</span></label>
                                            <input type="number" class="form-control" name="cost_price">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 add-product">
                                            <label class="form-label">MRP Price<span class="star-sign">*</span></label>
                                            <input type="number" class="form-control" name="mrp" id="mrp">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                        <label class="form-label">Discount</label>
                                        <div class="input-group">
                                            <select name="discount_type" class="form-select" id="discount_type">
                                                <option value="0">Percentage(%)</option>
                                                <option value="1">Flat</option>
                                            </select>
                                            <input type="text" name="discount_value" class="form-control"
                                                placeholder="Enter discount" id="discount_value">
                                        </div>
                                    </div>



                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Sale Price<span class="star-sign">*</span></label>
                                            <input type="number" class="form-control" name="price" id="sale_price"
                                                value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 ">
                                            <label class="form-label">Materials<span class="star-sign">*</span></label>
                                            <input type="text" class="form-control" name="material">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <label class="form-label text-sm mb-8">Size</label>
                                        <select name="size_id" id="size"
                                            class="form-control js-color-type-select"></select>
                                        <div class="invalid-feedback d-block color_type_id-error" style="display:none">
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-6 col-12 ">
                                        <div class="">
                                            <label class="form-label ">Description</label>
                                            <textarea rows="4" cols="5" class="form-control h-100" name="description"
                                                placeholder="Enter text here">{{ old('description') }}</textarea>

                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-6 col-12 ">
                                        <div class="">
                                            <label class="form-label ">Short Description</label>
                                            <textarea rows="4" cols="5" class="form-control h-100" name="short_description"
                                                placeholder="Enter text here">{{ old('short_description') }}</textarea>

                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="my-3">Image Section</div>
                                    <div class="col-lg-3 col-sm-6 col-12 ">
                                        <div class="mb-3">
                                            <label class="form-label">Image<span class="star-sign">*</span></label>

                                            <div class="form-group">
                                                <div class="row" id="image">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3 ">
                                            <label class="form-label">Thumbnail Image<span
                                                    class="star-sign">*</span></label>

                                            <div class="form-group">
                                                <div class="row" id="thumbnail_image">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- size chart image --}}
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Measurement Chart</label>

                                            <div class="form-group">
                                                <div class="row" id="size_chart_image">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mb-8">
                                            <label class="form-label text-sm mb-8">Active?</label>
                                            <div class="form-switch switch-purple d-flex align-items-center gap-3">
                                                <input type="hidden" name="is_active" value="0">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    value="1" id="categoryIsActive" checked>
                                                <label class="form-check-label" for="categoryIsActive">Enable this
                                                    category</label>
                                            </div>
                                            <div class="invalid-feedback d-block is_active-error" style="display:none">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-card-one accordion " id="accordionExample2">
                    <div class="accordion-item ">
                        <div class="accordion-header" id="headingTwo">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                aria-controls="collapseTwo">
                                <div class="text-editor add-list">
                                    <div class="addproduct-icon list icon">
                                        <h6><i data-feather="life-buoy" class="add-info"></i><span>Meta Section</span>
                                        </h6>
                                        <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                class="chevron-down-add"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample2">
                            <div class="accordion-body">

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div class="row">


                                            <div class="col-lg-6 col-sm-6 col-12">
                                                <div class=" ">
                                                    <label class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control" name="meta_title"
                                                        value="{{ old('meta_title') }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-sm-6 col-12 ">
                                                <div class="">
                                                    <label class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control" name="meta_keywords"
                                                        value="{{ old('meta_title') }}">
                                                </div>
                                            </div>




                                        </div>
                                        <div class="row mt-3 justify-content-between">
                                            <div class="col-lg-3 col-sm-6 col-12 mt-8">
                                                <label class="form-label"> Meta Image</label>
                                                <div class="form-group">
                                                    <div class="row" id="meta_image">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-12 mt-2">
                                                <div class=" list ">
                                                    <label class="form-label "> Meta Description</label>
                                                    <textarea rows="8" cols="5" class="form-control h-100" name="meta_description"
                                                        placeholder="Enter text here">{{ old('meta_description') }}</textarea>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-12 mt-3">
            <div class="btn-addproduct mb-4">
                <button type="submit" class="btn btn-primary ">Save All</button>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <style>
        .star-sign {
            color: red;
            font-weight: bold;
        }
    </style>
    <script src="{{ asset('theme/admin/assets/plugins/fileupload/spartan-multi-image-picker-min.js') }}"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {

           
            function calculatePrice() {
                const discountType = $('#discount_type').val(); // string
                const mrp = parseFloat($('#mrp').val()) || 0;
                const discount = parseFloat($('#discount_value').val()) || 0;

                let salePrice = 0;

                if (discountType === '0') { // percentage
                    salePrice = mrp - (mrp * discount / 100);
                } else { // flat amount
                    salePrice = mrp - discount;
                }

                $('#sale_price').val(salePrice.toFixed(2));
            };
    
            $('#mrp,#discount_type,#discount_value').on('input', calculatePrice);


            $('#category_type').select2({
                placeholder: 'Search category type...',
                allowClear: true,
                width: '100%',
                // important for AJAX search
                ajax: {
                    url: "{{ route('category.types.select2') }}", // Laravel route
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search query
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });

        });
        $('#category').select2({
            placeholder: 'Search category...',
            allowClear: true,
            width: '100%',

            // important for AJAX search
            ajax: {
                url: "{{ route('category.cat.select2') }}", // Laravel route
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        type: $('#category_type').val() // search query
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        $('#subcategory').select2({
            placeholder: 'Select Subcategory...',
            allowClear: true,
            width: '100%',

            // important for AJAX search
            ajax: {
                url: "{{ route('subcategory.select2') }}", // Laravel route
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        type: $('#category').val() // search query
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        $('#product_type').select2({
            placeholder: 'Select Product Type...',
            allowClear: true,
            width: '100%',

            // important for AJAX search
            ajax: {
                url: "{{ route('product-type.select2') }}", // Laravel route
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term

                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        $('#unit').select2({
            placeholder: 'Select Unit...',
            allowClear: true,
            width: '100%',

            // important for AJAX search
            ajax: {
                url: "{{ route('units.select2') }}", // Laravel route
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term

                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });
        $('#brand').select2({
            placeholder: 'Select Brand...',
            allowClear: true,
            width: '100%',

            // important for AJAX search
            ajax: {
                url: "{{ route('brand.select2') }}", // Laravel route
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term

                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        $('#color').select2({
            placeholder: 'Select Color...',
            allowClear: true,
            width: '100%',

            // important for AJAX search
            ajax: {
                url: "{{ route('color.select2') }}", // Laravel route
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term

                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });
        $('#size').select2({
            width: '100%',
            placeholder: 'Select Size...',
            allowClear: true,


            // important for AJAX search
            ajax: {
                url: "{{ route('sizes.select2') }}", // Laravel route
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term

                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });






        // Event listener for name field
        $('#name').on('input', function() {
            var name = $(this).val();
            var slug = name ? slugify(name) : null; // Generate slug only if name is not empty
            $('#slug').val(slug);
        });

        $('#product_form').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            var formData = new FormData(this);

            // Make AJAX request
            $.ajax({
                url: '{{ route('product.products.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                '{{ route('product.products.index') }}';
                        }
                    });
                },
                error: function(xhr, status, error) {
                    // Parse the JSON response from the server
                    try {
                        var responseObj = JSON.parse(xhr.responseText);
                        var errorMessages = responseObj.errors ? Object.values(responseObj
                            .errors).flat() : [responseObj.message];
                        var errorMessageHTML = '<ul>' + errorMessages.map(errorMessage =>
                            '<li>' + errorMessage + '</li>').join('') + '</ul>';

                        // Show error messages using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessageHTML,
                        });
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        // Show default error message using SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing your request. Please try again later.',
                        });
                    }
                }

            });
        });


        $("#image").spartanMultiImagePicker({
            fieldName: 'image',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col',
            maxFileSize: '',
            dropFileLabel: "Drop Here",
            onExtensionErr: function(index, file) {
                console.log(index, file, 'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr: function(index, file) {
                console.log(index, file, 'file size too big');
                alert('File size too big max:250KB');
            }
        });

        $("#thumbnail_image").spartanMultiImagePicker({
            fieldName: 'thumbnail_image',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col',
            maxFileSize: '',
            dropFileLabel: "Drop Here",
            onExtensionErr: function(index, file) {
                console.log(index, file, 'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr: function(index, file) {
                console.log(index, file, 'file size too big');
                alert('File size too big max:250KB');
            }
        });

        $("#size_chart_image").spartanMultiImagePicker({
            fieldName: 'size_chart_image',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col',
            maxFileSize: '',
            dropFileLabel: "Drop Here",
            onExtensionErr: function(index, file) {
                console.log(index, file, 'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr: function(index, file) {
                console.log(index, file, 'file size too big');
                alert('File size too big max:250KB');
            }
        });

        $("#meta_image").spartanMultiImagePicker({
            fieldName: 'meta_image',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col',
            maxFileSize: '',
            dropFileLabel: "Drop Here",
            onExtensionErr: function(index, file) {
                console.log(index, file, 'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr: function(index, file) {
                console.log(index, file, 'file size too big');
                alert('File size too big max:250KB');
            }
        });
    </script>
@endsection
