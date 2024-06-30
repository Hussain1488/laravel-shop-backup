<meta property="og:title" content="{{ $fitting->product->meta_title ?: $fitting->product->title }}" />
<meta property="og:type" content="product" />
<meta property="og:url" content="{{ route('front.products.show', ['product' => $fitting->product]) }}" />
<meta name="description" content="{{ $fitting->product->meta_description ?: $fitting->product->short_description }}">
<meta name="keywords" content="{{ $fitting->product->getTags }}">
<meta name="product_id" content="{{ $fitting->product->id }}">

<link rel="canonical" href="{{ route('front.products.show', ['product' => $fitting->product]) }}" />

@if ($fitting->product->image)
    <meta property="og:image" content="{{ asset($fitting->product->image) }}">
    <meta property="og:image:width" content="600"/>
    <meta property="og:image:height" content="600"/>
@endif

@if ($fitting->product->addableToCart())
    <meta property="product:availability" content="in stock">
    <meta property="product:price:amount" content="355000">
    <meta property="product:price:currency" content="IRR">
@else
    <meta property="product:availability" content="out of stock">
@endif

<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $fitting->product->meta_title ?: $fitting->product->title }}",
        "alternateName": "{{ $fitting->product->title_en }}",
        "image": [
            "{{ asset($fitting->product->image) }}"
            @if ($fitting->product->gallery()->count())
                ,
                @foreach ($fitting->product->gallery as $gallery)
                    "{{ asset($gallery->image) }}" {{ !$loop->last ? ',' : '' }}
                @endforeach
            @endif
        ],

        @if ($fitting->product->brand)
        "brand": {
            "@type": "Brand",
            "name": "{{ $fitting->product->brand->name }}"
        },
        @endif

        "offers": {
            "@type": "Offer",
            "url": "{{ route('front.products.show', ['product' => $fitting->product]) }}",
            "priceCurrency": "IRR",
            "price": "{{ $fitting->product->getLowestPrice(true) }}"
        },

        "description": "{{ $fitting->product->meta_description ?: $fitting->product->short_description }}"
    }
</script>
