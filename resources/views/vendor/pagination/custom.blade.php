<!-- pagination.custom.blade.php -->

<ul class="pagination justify-content-center">
    @foreach ($paginator as $item)
        <li class="page-item {{ $item->isCurrentPage() ? 'active' : '' }}">
            <a href="{{ $item->url() }}" class="page-link">{{ $item->label() }}</a>
        </li>
    @endforeach
</ul>
