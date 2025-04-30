<!-- Modal -->
<div class="modal fade" id="showModal{{ $menu->id }}" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="showModalLabel">{{ $menu->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4 text-center">
                        <img src="{{$menu->image? asset($menu->image):asset('icons/healthy-food.png') }}" alt="{{ $menu->name }}" class="img-fluid rounded shadow-sm" style="max-height: 180px;">


                    </div>
                    <div class="col-md-8">
                        <h4 class="fw-bold mb-2">{{ $menu->name }}</h4>
                        <p class="text-muted mb-2">{{ $menu->description }}</p>
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $menu->category->title ?? 'Uncategorized' }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="fs-5 fw-semibold text-success">${{ number_format($menu->price, 2) }}</span>
                        </div>
                        <div>
                            <strong>Ingredients:</strong>
                            @if($menu->ingredients->count())
                            <ul class="list-unstyled mb-0">
                                @foreach($menu->ingredients as $ingredient)
                                <li>
                                    {{ $ingredient->name }}
                                    @if($ingredient->pivot->quantity)
                                    <small class="text-muted">({{ $ingredient->pivot->quantity }})</small>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <span class="text-muted">No ingredients listed.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>