@extends('ecommerce.layouts.master')
@section('content')
<div class="breadcrumb-area mb-30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('ecommerce.home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Blog</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-area-wrapper pt-30 pb-65">
       <div class="container-fluid">
           <div class="row">
               <div class="col-lg-12">
                   <div class="blog-wrapper-inner">
                       <div class="row">
                            @forelse($blogs as $blog)
                            <div class="col-sm-6 col-lg-4">
                                <div class="single-blogg-item mb-30">
                                    <div class="blogg-thumb">
                                        <a href="{{ route('ecommerce.blog.show', $blog->slug) }}">
                                            @if($blog->thumb)
                                                <img src="{{ asset('storage/'.$blog->thumb) }}" alt="{{ $blog->title }}">
                                            @else
                                                <img src="{{ asset('assets/img/blog/blog-placeholder.jpg') }}" alt="{{ $blog->title }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="blogg-content">
                                        <span class="post-date">{{ $blog->published_at ? $blog->published_at->format('d M Y') : '' }}</span>
                                        <h5><a href="{{ route('ecommerce.blog.show', $blog->slug) }}">{{ $blog->title }}</a></h5>
                                        <p>{{ Str::limit($blog->description, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-5">
                                <h3>No blog posts found</h3>
                                <p>Please check back later for new content.</p>
                            </div>
                            @endforelse
                       </div>
                   </div>
                   <div class="paginatoin-area text-center pt-40">
                        <div class="row">
                            <div class="col-12">
                                {{ $blogs->links('ecommerce.partials.pagination') }}
                            </div>
                        </div>
                   </div>
               </div>
           </div>
       </div>
    </div>
@endsection