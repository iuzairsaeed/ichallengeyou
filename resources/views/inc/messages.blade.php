<div id="toast-container" class="toast-container toast-top-right mt-2">
    @if(count($errors) > 0)
        <div class="toast toast-error" aria-live="assertive">
            {{$errors->first()}}
        </div>
    @endif

    @if(session('success'))
        <div class="toast toast-success" aria-live="assertive">
            {{session('success')}}
        </div>
    @endif

    @if(session('error'))
        <div class="toast toast-error" aria-live="assertive">
            {{session('error')}}
        </div>
    @endif
</div>
