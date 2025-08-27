<div class="book-card">
    <a href="{{ route('books.show', $book->book_id) }}">
        <img src="{{ $book->cover_image_url ?: 'https://via.placeholder.com/200x250?text=No+Cover' }}" 
             alt="{{ $book->title }}" 
             class="book-cover">
    </a>
    <div class="book-info">
        <h6 class="book-title">
            <a href="{{ route('books.show', $book->book_id) }}" class="text-decoration-none">
                {{ Str::limit($book->title, 30) }}
            </a>
        </h6>
        <p class="book-author">{{ $book->writers->pluck('name')->join(', ') ?: 'Unknown Author' }}</p>
        
        @if($book->rating)
        <div class="book-rating mb-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $book->rating ? 'text-warning' : 'text-muted' }}"></i>
            @endfor
            <small>({{ number_format($book->rating, 1) }})</small>
        </div>
        @endif
        
        <p class="book-price">৳{{ number_format($book->price) }}</p>
        
        @if($book->quantity > 0)
            <form action="{{ route('add-to-cart') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </button>
            </form>
        @else
            <button class="btn btn-secondary btn-sm w-100" disabled>
                Out of Stock
            </button>
        @endif
    </div>
</div>
