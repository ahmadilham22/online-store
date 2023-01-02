@extends('layouts.app')

@section('title')
    Store Category Page
@endsection

@push('prepend-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    {{-- <link rel="stylesheet" href="owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="owlcarousel/owl.theme.default.min.css"> --}}
@endpush

@section('content')
    <div class="page-content page-categories">
        <section class="store-trend-categories">
            <div class="container">
                <div class="row">
                    <div class="col-12" data-aos="fade-up">
                        <h5>All Categories</h5>
                    </div>
                </div>
                <div class="row owl-carousel category-carousel owl-theme">
                    <?php $i = 0; ?>
                    @forelse ($categories as $category)
                        <div class="col-6 col-md-3 col-lg-2" data-aos="fade-up" data-aos-delay="{{ $i += 100 }}">
                            <a class="component-categories d-block"
                                href="{{ route('categories.detail', $category->slug) }}">
                                <div class="categories-image">
                                    <img src="{{ Storage::url($category->photo) }}" alt="Gadgets Categories"
                                        class="w-100" />
                                </div>
                                <p class="categories-text">
                                    {{ $category->name }}
                                </p>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5" data-aos="fade-up" data-aos-delay="100">
                            No Categories Found
                        </div>
                    @endforelse

                </div>
            </div>
        </section>
        <section class="store-new-products">
            <div class="container">
                <div class="row">
                    <div class="col-12" data-aos="fade-up">
                        <h5>All Products</h5>
                    </div>
                </div>
                <div class="row">
                    <?php $i = 0; ?>
                    @forelse ($products as $product)
                        <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i += 100 }}">
                            <a class="component-products d-block" href="{{ route('detail', $product->slug) }}">
                                <div class="products-thumbnail">
                                    <div class="products-image"
                                        style="
                                     @if ($product->galleries->count()) background-image: url('{{ Storage::url($product->galleries->first()->photos) }}');
                                        @else
                                            background-color: #eee; @endif
                    ">
                                    </div>
                                </div>
                                <div class="products-text">
                                    {{ $product->name }}
                                </div>
                                <div class="products-price">
                                    Rp. {{ number_format($product->price, 2, ',', '.') }}
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5" data-aos="fade-up" data-aos-delay="100">
                            No Products Found
                        </div>
                    @endforelse
                </div>
                <div class="row">
                    <div class="col-12 mt-4 d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('addon-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>
    <script>
        // $('.category-carousel').owlCarousel({
        //     loop:true,
        //     margin:10,
        //     responsiveClass:true,
        //     dots: false,
        //     // autoplay:true,
        //     // autoplayTimeout:3000,
        //     responsive:{
        //         0:{
        //             items:1
        //         },
        //         600:{
        //             items:3
        //         },
        //         1000:{
        //             items:6
        //         }
        //     }
        // })
    </script>
@endpush
