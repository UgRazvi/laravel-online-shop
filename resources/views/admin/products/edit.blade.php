@extends('admin.layouts.app')

@section('dyn-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Brand</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        {{-- Section : X-03 --}}
        <div class="container-fluid">

            <form action="{{ route('products.update', $product->id) }}" method="POST" id="brandForm" name="brandForm"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="title">Title</label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                    placeholder="Title" value="{{ $product->title }}">
                                                <p class="error"></p>
                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="slug">Slug</label>
                                                <input readonly type="text" name="slug" id="slug"
                                                    class="form-control" placeholder="Slug" value="{{ $product->slug }}">
                                                <p class="error"></p>
                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"
                                                    value="{{ $product->description }}"></textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Media</h2>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="product-gallery">

                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Pricing</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="price">Price</label>
                                                <input type="text" name="price" id="price" class="form-control"
                                                    placeholder="Price" value="{{ $product->price }}">
                                                <p class="error"></p>
                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="compare_price">Compare at Price</label>
                                                <input type="text" name="compare_price" id="compare_price"
                                                    class="form-control" placeholder="Compare Price"
                                                    value="{{ $product->compare_price }}">
                                                <p class="text-muted mt-3">
                                                    To show a reduced price, move the product's original price into
                                                    Compare at
                                                    price. Enter a lower value into Price.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Inventory</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sku">SKU (Stock Keeping Unit)</label>
                                                <input type="text" name="sku" id="sku" class="form-control"
                                                    placeholder="sku" value="{{ $product->sku }}">
                                                <p class="error"></p>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="barcode">Barcode</label>
                                                <input type="text" name="barcode" id="barcode" class="form-control"
                                                    placeholder="Barcode" value="{{ $product->barcode }}">
                                                <p class="error"></p>
                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <div class="custom-control custom-checkbox">
                                                    {{-- <input type="hidden" name="track_qty" id="track_qty" value="No"> --}}
                                                    <input class="custom-control-input" type="checkbox" id="track_qty"
                                                        name="track_qty" value="Yes" checked>
                                                    <label for="track_qty" class="custom-control-label">Track
                                                        Quantity</label>
                                                    <p class="error"></p>
                                                </div>

                                            </div>
                                            <div class="mb-3" id="qtyField">
                                                <input type="number" min="0" name="qty"id="qty"
                                                    class="form-control" placeholder="Qty"value="{{ $product->qty }}">
                                                <p class="error"></p>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Product status</h2>
                                    <div class="mb-3">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Block</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="h4  mb-3">Product Category</h2>
                                    <div class="mb-3">
                                        <label for="category">Product</label>
                                        <select name="category" id="category" class="form-control"
                                            value="{{ $product->category }}">
                                            <option value="">Select
                                                The Product</option>
                                            @if (!empty($categories))
                                                @foreach ($categories as $category)
                                                    <option value=" {{ $category->id }} ">
                                                        {{ $category->name }} </option>
                                                @endforeach
                                            @endif

                                        </select>
                                        <p class="error"></p>

                                    </div>
                                    <div class="mb-3">
                                        <label for="sub_category">Sub Product</label>
                                        <select name="sub_category" id="sub_category" class="form-control"
                                            value="{{ $product->sub_category }}">
                                            <option value="">Select
                                                The Sub Product</option>
                                            @if (!empty($subcategories))
                                                @foreach ($subcategories as $subProduct)
                                                    <option value=" {{ $subProduct->id }} ">
                                                        {{ $subProduct->name }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Product Brand</h2>
                                    <div class="mb-3">
                                        <select name="brand" id="brand" class="form-control">
                                            <option value="">Select The Brand
                                            </option>
                                            @if (!empty($brands))
                                                @foreach ($brands as $brand)
                                                    <option value=" {{ $brand->id }} ">
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>


                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Featured product</h2>
                                    <div class="mb-3">
                                        <select name="is_featured" id="is_featured" class="form-control">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="{{ route('products.create') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
                </div>
            </form>

        </div>
        {{-- Section : X-03 --}}
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@section('customJs')
    <script>
        // console.log("Script Is Working"); // For Debugging Purpose.

        $("#brandForm").submit(function(event) {
            // console.log("Brand Form Is Working"); // For Debugging Purpose.
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('products.update', $product->id) }}', // Change - Usman  ", $product->id"
                type: 'PUT',
                data: element.serialize(),
                dataType: 'json',
                success: function(response) {
                    // console.log("Brand Form Success Response Is Working"); // For Debugging Purpose.
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        console.log(
                            "Brand Form Success Response True Is Working"); // For Debugging Purpose.
                        window.location.href = "{{ route('products.index') }}";
                        $("#name").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                        $("$name").removeClass('is-invalid').siblings('p').removeClass(
                            'invalid-feedback').html("");
                    } else {
                        if (response['notFound' == true]) {
                            //  If We Don't have Data In Our DataBase, We've To Redirect To Index Page.
                            window.location.href = '{{ route('products.index') }}';
                            console.log("Unable To Delete, Brand Not Found.");
                        }
                        console.log(
                            "Brand Form Success Response False Is Working"
                        ); // For Debugging Purpose.
                        var errors = response['errors'];
                        if (errors['name']) {
                            // console.log("Name Error Is Working"); // For Debugging Purpose.
                            $('#name').addClass('is-invalid').siblingss('p').addClass(
                                    'invalid-feedback')
                                .html(errors['name']);
                        } else {
                            $('#name').removeClass('is-invalid').siblingss('p').removeClass(
                                'invalid-feedback').html("");
                        }
                        if (errors['slug']) {
                            // console.log("Slug Error Is Working"); // For Debugging Purpose.
                            $('#slug').addClass('is-invalid').siblingss('p').addClass(
                                    'invalid-feedback')
                                .html(errors['slug']);
                        } else {
                            $('#slug').removeClass('is-invalid').siblingss('p').removeClass(
                                'invalid-feedback').html("");
                        }
                    }

                },
                error: function(jqXHR, exception) {
                    // console.log("CSRF Token Error Is Working"); // For Debugging Purpose.
                    // console.log("Something Went Wrong");
                }
            });
        });

        $("#name").change(function() {
            var element = $(this);
            // console.log("Name Change Is Working"); // For Debugging Purpose.
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('get.slug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    // console.log("Name Change Response Is Working"); // For Debugging Purpose.
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status']) {
                        // console.log("Name Change Response If Is Working"); // For Debugging Purpose.
                        $("#slug").val(response['slug']);
                    }
                }
            });
        });

        // Dropzone
        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
                console.log(response)
            }
        });
    </script>
@endsection
