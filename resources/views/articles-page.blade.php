@extends('layouts.app_nonavbar')

@section('title', 'SMARTfit - Fashion Stories')

@section('content')
<section class="articles-page">
    <div class="articles-container">

        <div class="articles-hero">
            <a href="{{ route('landing') }}" class="back-home-btn">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

            <div class="articles-tag">LATEST FROM BLOG & NEWS</div>
            <h1 class="articles-title">Fashion Stories</h1>
            <p class="articles-subtitle">
                Explore curated articles, body-shape styling guides, and fashion inspiration to help you dress with more confidence.
            </p>
        </div>

        <div class="articles-grid">
            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-1.jpg') }}" alt="How To Dress For Your Body Type">
                    <span class="article-category">Style Guide</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            Style Guide
                        </span>
                        <span class="article-source">Stitch Fix</span>
                    </div>
                    <h3>How To Dress For Your Body Type</h3>
                    <p class="article-excerpt">
                        A body-positive guide to identifying your shape and choosing pieces that simplify styling while building confidence.
                    </p>
                    <a href="https://www.stitchfix.com/women/blog/style-guide/find-fit-for-your-body-type/" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-2.jpg') }}" alt="Determine Your Body Shape">
                    <span class="article-category">Body Shape</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            Wardrobe Guide
                        </span>
                        <span class="article-source">The Concept Wardrobe</span>
                    </div>
                    <h3>Determine Your Body Shape(s)</h3>
                    <p class="article-excerpt">
                        Learn the main female body shapes, how to identify your own proportions, and why shape awareness helps with smarter styling choices.
                    </p>
                    <a href="https://theconceptwardrobe.com/build-a-wardrobe/determine-your-body-shapes" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-3.jpg') }}" alt="Hourglass Body Shape Guide">
                    <span class="article-category">Hourglass</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            Shape Styling
                        </span>
                        <span class="article-source">The Concept Wardrobe</span>
                    </div>
                    <h3>Hourglass Body Shape: A Comprehensive Guide</h3>
                    <p class="article-excerpt">
                        A focused guide for hourglass figures, including how to define the waist, choose flattering necklines, and balance fitted pieces.
                    </p>
                    <a href="https://theconceptwardrobe.com/build-a-wardrobe/hourglass-body-shape" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-4.jpg') }}" alt="Pear Body Shape Guide">
                    <span class="article-category">Pear Shape</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            Shape Styling
                        </span>
                        <span class="article-source">The Concept Wardrobe</span>
                    </div>
                    <h3>Pear Body Shape: A Comprehensive Guide</h3>
                    <p class="article-excerpt">
                        Covers how to recognise a pear body shape and use tops, coats, and trousers to visually balance the upper and lower body.
                    </p>
                    <a href="https://theconceptwardrobe.com/build-a-wardrobe/pear-body-shape" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-5.jpg') }}" alt="Rectangle Body Shape Guide">
                    <span class="article-category">Rectangle</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            Shape Styling
                        </span>
                        <span class="article-source">The Concept Wardrobe</span>
                    </div>
                    <h3>Rectangle Body Shape: A Comprehensive Guide</h3>
                    <p class="article-excerpt">
                        A practical overview of how to create waist definition and add visual curves with fabric, shape, and outfit structure.
                    </p>
                    <a href="https://theconceptwardrobe.com/build-a-wardrobe/how-to-dress-the-rectangle-body-shape" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-6.jpg') }}" alt="Inverted Triangle Body Shape Guide">
                    <span class="article-category">Inverted Triangle</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            Shape Styling
                        </span>
                        <span class="article-source">The Concept Wardrobe</span>
                    </div>
                    <h3>Inverted Triangle Body Shape: A Comprehensive Guide</h3>
                    <p class="article-excerpt">
                        Explains how to balance broader shoulders with the lower body and choose pieces that soften the upper silhouette.
                    </p>
                    <a href="https://theconceptwardrobe.com/build-a-wardrobe/inverted-triangle-body-shape" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-7.jpg') }}" alt="Triangle Body Shape Guide">
                    <span class="article-category">Triangle</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            June 26, 2025
                        </span>
                        <span class="article-source">Stitch Fix</span>
                    </div>
                    <h3>How to Dress for a Triangle Body Shape</h3>
                    <p class="article-excerpt">
                        A recent guide with stylist-approved tips on flattering fits, essential pieces, and outfit tricks for triangle shapes.
                    </p>
                    <a href="https://www.stitchfix.com/women/blog/style-guide/how-to-dress-a-triangle-body-shape/" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-8.jpg') }}" alt="Styling an Hourglass Figure">
                    <span class="article-category">Styling Tips</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            October 18, 2024
                        </span>
                        <span class="article-source">Stitch Fix</span>
                    </div>
                    <h3>Styling an Hourglass Figure: 5 Easy Tips</h3>
                    <p class="article-excerpt">
                        A concise styling article focused on easy, wearable tips for highlighting an hourglass silhouette without overcomplicating outfits.
                    </p>
                    <a href="https://www.stitchfix.com/women/blog/styling-hourglass-figure/" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>

            <article class="article-card">
                <div class="article-image">
                    <img src="{{ asset('images/blog-9.jpg') }}" alt="What Body Shape Are You Guide">
                    <span class="article-category">Body Calculator</span>
                </div>
                <div class="article-content">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fa-solid fa-calendar-days"></i>
                            Recent Guide
                        </span>
                        <span class="article-source">Sumissura</span>
                    </div>
                    <h3>What Body Shape Are You? Body Type Calculator</h3>
                    <p class="article-excerpt">
                        A recent guide that walks through common women’s body types and how to dress with comfort and confidence once you know your shape.
                    </p>
                    <a href="https://www.sumissura.com/en-us/blog/what-is-my-body-shape" target="_blank" rel="noopener noreferrer" class="article-link">
                        Read Article <span>→</span>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/articles-page.css') }}">
@endpush