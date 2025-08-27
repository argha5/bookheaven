@extends('layouts.app')

@section('title', 'BookHeaven - Your Literary Paradise')

@section('content')
<div class="container-fluid">
    <!-- Carousel Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('assets/images/1.png') }}" class="d-block w-100" alt="Book Rental Promotion" style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/audiobook-poster.png') }}" class="d-block w-100" alt="Audiobook Collection" style="height: 400px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/be-a-writer.png') }}" class="d-block w-100" alt="Become a Writer" style="height: 400px; object-fit: cover;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-lg-3 mb-4">
            <!-- Writers Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 text-primary">Popular Writers</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @foreach($writers as $writer)
                    <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                        <img src="{{ $writer->image_url ?: 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80' }}" 
                             alt="{{ $writer->name }}" 
                             class="rounded-circle me-3" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <a href="{{ route('books.by-writer', $writer->writer_id) }}" class="text-decoration-none">
                                <div class="fw-bold">{{ $writer->name }}</div>
                            </a>
                            <small class="text-muted">{{ $writer->bio ? Str::limit($writer->bio, 30) : 'Writer' }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Genres Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 text-primary">Browse Genres</h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @foreach($genres as $genre)
                    <div class="mb-2">
                        <a href="{{ route('books.by-genre', $genre->genre_id) }}" class="text-decoration-none d-block py-1">
                            <i class="fas fa-book me-2"></i>{{ $genre->name }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Promo Cards -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             class="card-img-top" alt="Subscription Plans" style="height: 150px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Subscribe Plans</h5>
                            <p class="card-text">Get unlimited access to thousands of books with our subscription plans.</p>
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Subscribe Now
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                             class="card-img-top" alt="Upcoming Events" style="height: 150px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Upcoming Events</h5>
                            <p class="card-text">Join our book clubs, author meetups, and literary festivals.</p>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-calendar"></i> View Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Section -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0 text-primary">Featured Books</h4>
                </div>
                <div class="card-body">
                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-3" id="bookTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-books" type="button">All Books</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="popular-tab" data-bs-toggle="tab" data-bs-target="#popular-books" type="button">Popular</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="top-rated-tab" data-bs-toggle="tab" data-bs-target="#top-rated-books" type="button">Top Rated</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent-books" type="button">Recent</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sale-tab" data-bs-toggle="tab" data-bs-target="#sale-books" type="button">On Sale</button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="bookTabContent">
                        <!-- All Books -->
                        <div class="tab-pane fade show active" id="all-books">
                            <div class="row">
                                @foreach($allBooks as $book)
                                <div class="col-md-3 col-sm-6 mb-4">
                                    @include('partials.book-card', ['book' => $book])
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Popular Books -->
                        <div class="tab-pane fade" id="popular-books">
                            <div class="row">
                                @foreach($popularBooks as $book)
                                <div class="col-md-3 col-sm-6 mb-4">
                                    @include('partials.book-card', ['book' => $book])
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Top Rated Books -->
                        <div class="tab-pane fade" id="top-rated-books">
                            <div class="row">
                                @foreach($topRatedBooks as $book)
                                <div class="col-md-3 col-sm-6 mb-4">
                                    @include('partials.book-card', ['book' => $book])
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recent Books -->
                        <div class="tab-pane fade" id="recent-books">
                            <div class="row">
                                @foreach($recentBooks as $book)
                                <div class="col-md-3 col-sm-6 mb-4">
                                    @include('partials.book-card', ['book' => $book])
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sale Books -->
                        <div class="tab-pane fade" id="sale-books">
                            <div class="row">
                                @foreach($saleBooks as $book)
                                <div class="col-md-3 col-sm-6 mb-4">
                                    @include('partials.book-card', ['book' => $book])
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
