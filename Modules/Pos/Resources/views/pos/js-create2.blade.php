@section('script')
    @include('pos::pos.js.js-header')
    @include('pos::pos.js.js-buah')
    @include('pos::pos.js.js-parcel')
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            window.mainCartInstance = Alpine.data('posApp', posApp);
            window.parcelFormInstance = Alpine.data('parcelForm', parcelForm);
        });
    </script>
@endsection
