@extends('admin.layouts.app')

@section('dyn-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('subcategories.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="{{ route('subcategories.store') }}" method="POST" name="subCategoryForm" id="subCategoryForm">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if (!empty($categories))
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control"
                                        placeholder="Slug">
                                    <p></p>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" name="image_id" value="" id="image_id">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active
                                        </option>
                                        <option value="0">Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@section('customJs')
    <script>
        // console.log("Script Is Working"); // For Debugging Purpose.

        $("#subCategoryForm").submit(function(event) {
            // console.log("Sub Category Form Is Working"); // For Debugging Purpose.
            event.preventDefault();
            var element = $("#subCategoryForm");
            $("button[type=submit]").prop['disabled', true];
            $.ajax({
                url: '{{ route('subcategories.store') }}',
                type: 'post',
                data: element.serialize(),
                dataType: 'json',
                success: function(response) {
                    // console.log("Sub Category Form Success Response Is Working"); // For Debugging Purpose.
                    $("button[type=submit]").prop['disabled', false];
                    if (response["status"] == true) {
                        console.log(
                            "Sub Category Form Success Response True Is Working"
                        ); // For Debugging Purpose.
                        window.location.href = "{{ route('subcategories.index') }}";
                        $("#name").removeClass('is-invalid').sibling('p').removeClass(
                            'invalid-feedback').html("");
                        $("$name").removeClass('is-invalid').sibling('p').removeClass(
                            'invalid-feedback').html("");

                    } else {
                        console.log(
                            "Sub Category Form Success Response False Is Working"
                        ); // For Debugging Purpose.
                        var errors = response['errors'];
                        if (errors['category']) {
                            console.log("Category Error Is Working"); // For Debugging Purpose.
                            $('#category').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['category']);
                        } else {
                            $('#category').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                        if (errors['name']) {
                            console.log("Name Error Is Working"); // For Debugging Purpose.
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['name']);
                        } else {
                            $('#name').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                        if (errors['slug']) {
                            console.log("Slug Error Is Working"); // For Debugging Purpose.
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['slug']);
                        } else {
                            $('#slug').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                    }

                },
                error: function(jqXHR, exception) {
                    console.log("CSRF Token Error Is Working"); // For Debugging Purpose.
                    console.log("Something Went Wrong");
                }
            });
        });


        // Code : To Generate Automatic Name To Slug
        $("#name").change(function() {
            var element = $(this);
            // console.log("Name Change Is Working"); // For Debugging Purpose.
            $("button[type=submit]").prop['disabled', true];
            $.ajax({
                url: '{{ route('get.slug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    // console.log("Name Change Response Is Working"); // For Debugging Purpose.
                    $("button[type=submit]").prop['disabled', false];
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
                    if (this.files.length > 5) {
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
