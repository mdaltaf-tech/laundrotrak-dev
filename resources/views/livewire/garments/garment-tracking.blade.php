<div class="dashboard-main-body">
    <div class="card h-100 p-0">
        <div class="tw-py-1.5 tw-px-3 bg-base d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <form class="navbar-search">
                    <input type="text" class="bg-base tw-px-3 tw-py-1.5 w-auto" placeholder="Search Tag / Order / Customer / Phone" id="searchInput" wire:model.live.debounce.300ms="search">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
        </div>
        <div class="tw-p-0">
            @php
                $orderIds = collect($articles)
                    ->map(function ($article) {
                        return is_array($article)
                            ? ($article['order_id'] ?? null)
                            : ($article->order_id ?? null);
                    })
                    ->filter()
                    ->unique()
                    ->values();

                $orderId = $orderIds->count() === 1
                    ? $orderIds->first()
                    : null;
            @endphp
            @if($orderId)
                <div class="tw-px-3 tw-py-2 tw-flex tw-justify-end">
                    <a
                        href="{{ route('orders.print-all-tags', ['order' => $orderId]) }}"
                        target="_blank"
                        onclick="return confirm('Print all garment tags for this order?');"
                        class="btn rounded-pill btn-info-100 text-info-600 radius-8 tw-inline-flex tw-items-center tw-gap-1 tw-whitespace-nowrap tw-text-xs tw-py-1.5 tw-px-3"
                    >
                        <span>Print All Tags</span>
                    </a>
                </div>
            @endif
            @if(count($articles)>0)
                @foreach($articles as $article)
                    <div class="card garment-card garment-status-{{ $article->status }} mb-3">
                        <div class="row align-items-center tw-text-xs">
                            {{-- Left section --}}
                            <div class="col-md-6">
                                <div class="tw-flex tw-items-center tw-gap-2">
                                    <span class="fw-semibold text-primary-light tw-text-xs">
                                        {{ $article->tag_number }}
                                    </span>
                                    @if($article->status==2)

                                        <span class="badge fw-medium tw-text-xs radius-4 tw-px-1.5 tw-py-0.5 text-neutral-100 bg-success garment-tag-badge text-white">
                                            Pickup Ready
                                        </span>

                                    @elseif($article->status==3)

                                        <span class="badge fw-medium tw-text-xs radius-4 tw-px-1.5 tw-py-0.5 text-neutral-100 bg-neutral-800 garment-tag-badge">
                                            Delivered
                                        </span>

                                    @endif
                                    @if($article->order?->tags_printed_at)
                                        <span class="badge bg-success-subtle text-success-emphasis ms-1 tw-text-xs">
                                            Printed
                                        </span>
                                    @endif
                                </div>
                                <div class="text-neutral-600 tw-text-xs">
                                    <span class="tw-font-medium">
                                        {{ $article->article_name }}
                                    </span>

                                    |

                                    {{ $article->service_name }}

                                </div>
                                <div class="text-neutral-600 tw-text-xs tw-mt-1">
                                    {{ $article->order->customer_name ?? '--' }}
                                    <span class="tw-mx-1 text-neutral-400">|</span>
                                    {{ $article->order->order_number }}
                                </div>
                            </div>
                            {{-- Timeline --}}
                            <div class="col-md-2 pe-4">
                                <div class="text-neutral-600 timeline-data">
                                    <div>
                                        <span class="text-neutral-500">Received :</span>
                                        <span class="tw-font-medium text-primary-light mt-1">
                                            {{ date('d/m/y h:i A',strtotime($article->created_at)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500">Processing :</span>
                                        <span class="tw-font-medium text-primary-light mt-1">
                                            {{ $article->processing_at ?
                                            date('d/m/y h:i A',strtotime($article->processing_at))
                                            :'--'}}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-neutral-500">Ready :</span>
                                        <span class="tw-font-medium text-primary-light mt-1">
                                            {{ $article->ready_at ?
                                            date('d/m/y h:i A',strtotime($article->ready_at))
                                            :'--'}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {{-- Actions --}}
                            <div class="col-md-4  text-end">

                                <div class="status-container tw-flex tw-flex-nowrap tw-gap-1 tw-justify-end">
                                    <span class="badge fw-medium radius-4 tw-text-xs tw-px-2 tw-py-1 {{ $article->status >=0 ? 'bg-success text-white':'bg-light text-dark' }}">
                                        ✓ Received
                                    </span>
                                    <span class="badge fw-medium radius-4 tw-text-xs tw-px-2 tw-py-1 {{ $article->status >=1 ? 'bg-warning text-dark':'bg-light text-dark' }}">
                                        {{ $article->status >=1 ? '✓' : '○' }} Processing
                                    </span>
                                    <span class="badge fw-medium radius-4 tw-text-xs tw-px-2 tw-py-1 {{ $article->status >=2 ? 'bg-info text-white':'bg-light text-dark' }}">
                                        {{ $article->status >=2 ? '✓' : '○' }} Ready
                                    </span>
                                    <span class="badge fw-medium radius-4 tw-text-xs tw-px-2 tw-py-1 {{ $article->status >=3 ? 'bg-dark text-white':'bg-light text-dark' }}">
                                        {{ $article->status >=3 ? '✓' : '○' }} Delivered
                                    </span>
                                </div>
                                <a
                                href="{{ route('orders.articles.print-tag', [
                                    'order' => $article->order_id,
                                    'article' => $article->id,
                                ]) }}"
                                target="_blank"
                                class="btn rounded-pill btn-info-100 text-info-600 radius-8 tw-inline-flex tw-items-center tw-gap-1 tw-whitespace-nowrap tw-text-xs tw-py-1 tw-px-2 garment-action-btn"
                                title="Print tag: {{ $article->tag_number }}"
                            >
                                <span class="tw-whitespace-nowrap">Print Tag</span>
                            </a>
                                @if($article->status==0)

                                    <button
                                    wire:click="updateStatus({{ $article->id }},1)"
                                    class="btn rounded-pill btn-warning-100 text-warning-600 radius-8 tw-text-xs tw-py-1 tw-px-2 garment-action-btn">

                                        Start Processing

                                    </button>

                                @elseif($article->status==1)

                                    <button
                                    wire:click="updateStatus({{ $article->id }},2)"
                                    class="btn rounded-pill btn-primary-100 text-primary-600 radius-8 tw-text-xs tw-py-1 tw-px-2 garment-action-btn">

                                        Mark Ready

                                    </button>

                                @elseif($article->status==2)
                                    <button
                                        wire:click="updateStatus({{ $article->id }},3)"
                                        class="btn rounded-pill btn-success-100 text-success-600 radius-8 tw-text-xs tw-py-1 tw-px-2 garment-action-btn">
                                        Deliver
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif(!empty($search))
                <div class="alert alert-warning">
                    No garment found
                </div>
            @endif
        </div>
    </div>
</div>


@script
    <script>
        $wire.on('focus-search', () => {
            setTimeout(() => {
                document.getElementById('searchInput').focus();
            }, 100);
        });
    </script>
@endscript
