<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <a href="{{ route('taxes.index') }}" class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'taxes.index' ? ' active' : '' }}">{{ __('Taxes') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('product-category.index') }}" class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'product-category.index' ? 'active' : '' }}">{{ __('Category') }}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('product-unit.index') }}" class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'product-unit.index' ? ' active' : '' }}">{{ __('Unit') }}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('attributes.all') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'attributes.all' || Request::route()->getName() == 'attributevalue.all')   ? 'active' : '' }}   ">{{__('Attributes')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    </div>
</div>
