@extends('template.root')

@section('content')
    <style>
        .wheel-wrapper {
            height: 180px;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            position: relative;
            background: linear-gradient(to bottom, #fafafa, #fff);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .wheel-scroll {
            height: 100%;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            padding: 0;
            margin: 0;
        }

        .wheel-item {
            height: 48px;
            line-height: 48px;
            text-align: center;
            font-size: 16px;
            color: #6c757d;
            scroll-snap-align: center;
            list-style: none;
            transition: all 0.2s ease;
        }

        .wheel-item.active {
            font-size: 18px;
            font-weight: 600;
            color: #0d6efd;
        }

        .wheel-highlight {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 48px;
            width: 100%;
            border-top: 2px solid #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background: rgba(13, 110, 253, 0.05);
            pointer-events: none;
            z-index: 10;
        }

        /* Efek gradient atas bawah */
        .wheel-wrapper::before,
        .wheel-wrapper::after {
            content: "";
            position: absolute;
            left: 0;
            width: 100%;
            height: 40px;
            z-index: 5;
            pointer-events: none;
        }

        .wheel-wrapper::before {
            top: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0));
        }

        .wheel-wrapper::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0));
        }

        .wheel-container {
            height: 180px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .wheel-list {
            scroll-snap-type: y mandatory;
            overflow-y: auto;
            height: 100%;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .wheel-item {
            height: 48px;
            line-height: 48px;
            text-align: center;
            font-size: 16px;
            color: #6c757d;
            scroll-snap-align: center;
            transition: all 0.2s ease;
        }

        .wheel-item.active {
            font-size: 20px;
            font-weight: bold;
            color: #0d6efd;
        }

        .wheel-overlay {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 48px;
            width: 100%;
            border-top: 2px solid #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background: rgba(13, 110, 253, 0.05);
            z-index: 5;
            pointer-events: none;
            border-radius: 8px;
        }

        /* Gradient blur top & bottom */
        .wheel-container::before,
        .wheel-container::after {
            content: "";
            position: absolute;
            left: 0;
            width: 100%;
            height: 48px;
            z-index: 4;
            pointer-events: none;
        }

        .wheel-container::before {
            top: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), transparent);
        }

        .wheel-container::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(255, 255, 255, 0.8), transparent);
        }
    </style>


    <form id="add_product_form" class="form d-flex flex-column flex-lg-row"
        action="{{ isset($data) ? url(Request::segment(1) . '/' . $data->id) : url(Request::segment(1)) }}" method="POST"
        enctype="multipart/form-data" data-kt-redirect="">
        @if (isset($data))
            @method('PUT')
        @endif
        @csrf
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
            <div x-data="wheelPicker()" x-init="init()" class="my-5 mx-auto" style="max-width: 200px;">
                <label class="form-label fw-bold mb-2">Pilih Skala</label>
                <div class="wheel-container position-relative">
                    <!-- Middle Highlight -->
                    <div class="wheel-overlay"></div>

                    <!-- Scrollable Picker -->
                    <ul x-ref="scrollArea" @scroll="onScroll" class="wheel-list">
                        <template x-for="(item, i) in paddedOptions" :key="i">
                            <li class="wheel-item" :class="{ 'active': selectedIndex === i }" x-text="item ?? ''"></li>
                        </template>
                    </ul>

                    <input type="hidden" name="skala" :value="selectedValue">
                </div>
            </div>


            <!--end::Order details-->
            <div class="d-flex justify-content-end">
                <!--begin::Button-->
                <a href="{{ url(Request::segment(1)) }}" id="kt_ecommerce_edit_order_cancel"
                    class="btn btn-light me-5">Cancel</a>
                <!--end::Button-->
                <!--begin::Button-->
                <button type="submit" id="kt_ecommerce_edit_order_submit" class="btn btn-primary">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Button-->
            </div>
        </div>
        <!--end::Main column-->
    </form>
@section('script')
    <script>
        function wheelPicker() {
            return {
                options: [1000, 100, 10, 1, 0.1, 0.01, 0.001],
                paddedOptions: [],
                selectedIndex: 0,
                selectedValue: 1,
                scrollTimeout: null,

                init() {
                    this.paddedOptions = [null, null, ...this.options, null, null];
                    this.$nextTick(() => {
                        this.$refs.scrollArea.scrollTop = 2 * 48;
                        this.updateSelection();
                    });
                },

                onScroll() {
                    clearTimeout(this.scrollTimeout);
                    this.scrollTimeout = setTimeout(() => this.snapScroll(), 100);
                },

                snapScroll() {
                    const itemHeight = 48;
                    const scrollTop = this.$refs.scrollArea.scrollTop;
                    const index = Math.round(scrollTop / itemHeight);
                    this.$refs.scrollArea.scrollTo({
                        top: index * itemHeight,
                        behavior: 'smooth'
                    });

                    this.selectedIndex = index;
                    const value = this.paddedOptions[index];
                    if (value !== null) this.selectedValue = value;
                },

                updateSelection() {
                    const index = Math.round(this.$refs.scrollArea.scrollTop / 48);
                    this.selectedIndex = index;
                    const value = this.paddedOptions[index];
                    if (value !== null) this.selectedValue = value;
                }
            };
        }
    </script>
@endsection
@endsection
