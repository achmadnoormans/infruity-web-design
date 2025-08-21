@php
    $link = Request::segment(1);
    if (Request::segment(2) != null && !is_numeric(Request::segment(2))) {
        $link .= '/' . Request::segment(2);
    }
    if (Request::segment(3) != null && !is_numeric(Request::segment(3))) {
        $link .= '/' . Request::segment(3);
    }
@endphp
<div class="menu-item pt-5">
    <!--begin:Menu content-->
    <div class="menu-content">
        <span class="menu-heading fw-bold text-uppercase fs-7">Master</span>
    </div>
    <!--end:Menu content-->
</div>
<!--begin:Menu item-->
<div data-kt-menu-trigger="click"
    class="menu-item {{ in_array(Request::segment(1), ['products']) ? 'here show' : '' }} menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-basket fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Products</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'products' ? 'active' : '' }}" href="{{ url('products') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Products List</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'products/create' ? 'active' : '' }}" href="{{ url('products/create') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Create Product</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
    </div>
    <!--end:Menu sub-->
</div>
<!--end:Menu item-->
<!--begin:Menu item-->
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'category' ? 'active' : '' }}" href="{{ url('category') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-duotone ki-chart fs-2">
                <span class="path1"></span>
            </i>
        </span>
        <span class="menu-title">Category</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'unit' ? 'active' : '' }}" href="{{ url('unit') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-data">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
            </i>
        </span>
        <span class="menu-title">Product Unit</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'supplier' ? 'active' : '' }}" href="{{ url('supplier') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-handcart">
            </i>
        </span>
        <span class="menu-title">Suppliers</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'location' ? 'active' : '' }}" href="{{ url('location') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-map fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
        </span>
        <span class="menu-title">Stock Location</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'handling' ? 'active' : '' }}" href="{{ url('handling') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-courier-express">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
                <span class="path6"></span>
                <span class="path7"></span>
            </i>
        </span>
        <span class="menu-title">Handling</span>
    </a>
    <!--end:Menu link-->
</div>
<div data-kt-menu-trigger="click"
    class="menu-item {{ in_array(Request::segment(1), ['customers']) ? 'here show' : '' }} menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-profile-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Staff</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'staff' ? 'active' : '' }}" href="{{ url('staff') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Staff List</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'staff/create' ? 'active' : '' }}" href="{{ url('staff/create') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Create Staff</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
    </div>
    <!--end:Menu sub-->
</div>
<div data-kt-menu-trigger="click"
    class="menu-item {{ in_array(Request::segment(1), ['customers']) ? 'here show' : '' }} menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-people">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Customer</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'customers' ? 'active' : '' }}" href="{{ url('customers') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Customer List</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'customers/create' ? 'active' : '' }}"
                href="{{ url('customers/create') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Create Customer</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
    </div>
    <!--end:Menu sub-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'department' ? 'active' : '' }}" href="{{ url('department') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-home">
            </i>
        </span>
        <span class="menu-title">Departments</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'position' ? 'active' : '' }}" href="{{ url('position') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Position</span>
    </a>
    <!--end:Menu link-->
</div>
<!--end:Menu item-->
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'stock-out-type' ? 'active' : '' }}" href="{{ url('stock-out-type') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Stock Out Type</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'branch' ? 'active' : '' }}" href="{{ url('branch') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Branch</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'payment-method' ? 'active' : '' }}" href="{{ url('payment-method') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Payment Method</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'account' ? 'active' : '' }}" href="{{ url('account') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Account</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item pt-5">
    <!--begin:Menu content-->
    <div class="menu-content">
        <span class="menu-heading fw-bold text-uppercase fs-7">Inventory</span>
    </div>
    <!--end:Menu content-->
</div>
<!--begin:Menu item-->
<div data-kt-menu-trigger="click"
    class="menu-item {{ in_array(Request::segment(1), ['product-stock', 'stock-out', 'stock-opname']) ? 'here show' : '' }} menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-basket fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Stock</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'product-stock' ? 'active' : '' }}" href="{{ url('product-stock') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Report Stock</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'stock-out' ? 'active' : '' }}" href="{{ url('stock-out') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Stock Out</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'stock-opname' ? 'active' : '' }}" href="{{ url('stock-opname') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Stock Opname</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
    </div>
    <!--end:Menu sub-->
</div>
<!--end:Menu item-->
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'wholesale' ? 'active' : '' }}" href="{{ url('wholesale') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Wholesale</span>
    </a>
    <!--end:Menu link-->
    <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'sortir' ? 'active' : '' }}" href="{{ url('sortir') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Sortir</span>
    </a>
    <!--end:Menu link-->
    {{-- <!--begin:Menu link-->
    <a class="menu-link {{ $link == 'production' ? 'active' : '' }}" href="{{ url('production') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Production</span>
    </a>
    <!--end:Menu link--> --}}
</div>
<!--begin:Menu item-->
<div data-kt-menu-trigger="click"
    class="menu-item {{ in_array(Request::segment(1), ['production', 'receipt', 'receipt', 'parcel']) ? 'here show' : '' }} menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-basket fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Production</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Request::segment(1) == 'receipt' ? 'active' : '' }}" href="{{ url('receipt') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Create Receipt</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Request::segment(1) == 'production' ? 'active' : '' }}"
                href="{{ url('production') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Production (Stock)</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Request::segment(1) == 'parcel' ? 'active' : '' }}" href="{{ url('parcel') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Production (Parcel)</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
    </div>
    <!--end:Menu sub-->
</div>
<!--end:Menu item-->
<div class="menu-item pt-5">
    <!--begin:Menu content-->
    <div class="menu-content">
        <span class="menu-heading fw-bold text-uppercase fs-7">Pos</span>
    </div>
    <!--end:Menu content-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ Request::segment(1) == 'pos' ? 'active' : '' }}" href="{{ url('pos') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Pos App</span>
    </a>
    <!--end:Menu link-->
    <!--begin:Menu link-->
    <a class="menu-link {{ Request::segment(1) == 'delivery-order' ? 'active' : '' }}"
        href="{{ url('delivery-order') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Delivery Order</span>
    </a>
    <!--end:Menu link-->
    <!--begin:Menu link-->
    <a class="menu-link {{ Request::segment(1) == 'setting-nota' ? 'active' : '' }}"
        href="{{ url('setting-nota') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Setting Nota</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item pt-5">
    <!--begin:Menu content-->
    <div class="menu-content">
        <span class="menu-heading fw-bold text-uppercase fs-7">Crm</span>
    </div>
    <!--end:Menu content-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ Request::segment(1) == 'crm-dashboard' ? 'active' : '' }}"
        href="{{ url('crm-dashboard') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Dashboard</span>
    </a>
    <!--end:Menu link-->
</div>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link {{ Request::segment(1) == 'customer-report' ? 'active' : '' }}"
        href="{{ url('customer-report') }}">
        <span class="menu-icon">
            <i class="ki-duotone ki-security-user">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Customer</span>
    </a>
    <!--end:Menu link-->
</div>
<div data-kt-menu-trigger="click"
    class="menu-item {{ in_array(Request::segment(1), ['deposito', 'deposito']) ? 'here show' : '' }} menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-basket fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Deposito</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Request::segment(1) == 'deposito' ? 'active' : '' }}" href="{{ url('deposito') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-security-user">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title">Create Deposito</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'customer-deposito' ? 'active' : '' }}" href="{{ url('customer-deposito') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Customer Deposito</span>
            </a>
            <!--end:Menu link-->
        </div>
    </div>
    <!--end:Menu sub-->
</div>
<div data-kt-menu-trigger="click"
    class="menu-item {{ in_array(Request::segment(1), ['point-schedule', 'setting-exp']) ? 'here show' : '' }} menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-basket fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Loyalty Scheme</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Request::segment(1) == 'tier' ? 'active' : '' }}" href="{{ url('tier') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-security-user">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title">Tier</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--begin:Menu item-->
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ $link == 'point-schedule' ? 'active' : '' }}" href="{{ url('point-schedule') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Setting Scheme</span>
            </a>
            <!--end:Menu link-->
        </div>
        <!--end:Menu item-->
        <!--begin:Menu item-->
        {{-- <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ Request::segment(1) == 'setting-exp' ? 'active' : '' }}"
                href="{{ url('setting-exp') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-security-user">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title">Setting Exp</span>
            </a>
            <!--end:Menu link-->
        </div> --}}
        <!--end:Menu item-->
    </div>
    <!--end:Menu sub-->
</div>
