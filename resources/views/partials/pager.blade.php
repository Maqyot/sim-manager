@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="mt-3">
        <ul class="pagination justify-content-center mb-0">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}">&laquo; Prev</a>
            </li>
            <li class="page-item disabled"><span class="page-link">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span></li>
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}">Next &raquo;</a>
            </li>
        </ul>
    </nav>
@endif
