{{-- Check If Global Variable "Session" contains "Error" --}}
@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        {{ Session::get('error') }}
        {{-- If Global Variable "Session" contains "Error" Then Code Above It Will Execute And Show Error Logs --}}
    </div>
@endif

{{-- Check If Global Variable "Session" contains "Error" --}}
@if (Session::has('succes'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <h4><i class="icon fa fa-check"></i> Success!</h4>
        {{ Session::get('success') }}
        {{-- If Global Variable "Session" contains "Success" Then Code Above It Will Execute And Show Success Logs --}}
    </div>
@endif
