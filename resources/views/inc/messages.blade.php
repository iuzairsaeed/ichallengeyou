<div id="toast-container" class="toast-container toast-top-right">
    @if(count($errors) > 0)
        <script>
			toastr.error('{{$errors->first()}}', 'Message', toasterAnimationObject);
		</script>
    @endif

    @if(session('success'))
        <script>
			toastr.success('{{session('success')}}', 'Message', toasterAnimationObject);
		</script>
    @endif

    @if(session('error'))
        <script>
			toastr.error('{{session('error')}}', 'Message', toasterAnimationObject);
		</script>
    @endif
</div>
