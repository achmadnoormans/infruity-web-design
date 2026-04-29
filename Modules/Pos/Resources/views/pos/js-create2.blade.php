@section('script')
    @include('pos::pos.js.js-allowed')
    @include('pos::pos.js.js-header')
    @include('pos::pos.js.js-buah')
    @include('pos::pos.js.js-parcel')
    @include('pos::pos.js.js-jus')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            if (window.bootstrap && bootstrap.Modal && bootstrap.Modal.Default) {
                bootstrap.Modal.Default.backdrop = 'static';
                bootstrap.Modal.Default.keyboard = false;
            }
        });

        document.addEventListener('alpine:init', () => {
            window.mainCartInstance = Alpine.data('posApp', posApp);
            window.parcelFormInstance = Alpine.data('parcelForm', parcelForm);
            window.jusFormInstance = Alpine.data('jusForm', jusForm);
        });
    </script>
@endsection
