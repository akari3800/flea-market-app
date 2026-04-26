@extends('common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css')}}">
@endsection

@section('content')
<div class="product-detail">
    <div class="product-detail-form">

        <div class="product-image-area">
            <img src="{{ asset('storage/' . $product->image) }}" alt="">
        </div>

        <div class="product-description-area">
            <div class="product-title">
                <div class="product-title-contents">

                    <div class="product-name-contents">
                        <h1 class="product-name">{{ $product->name }}</h1>
                    </div>

                    <div class="brand-name-contents">
                        <p class="brand-name">{{ $product->brand_name }}</p>
                    </div>

                    <div class="price-contents">
                        <span class="price">￥
                            <span class="price-number">
                                {{ number_format($product->price) }}
                            </span>
                            (税込)
                        </span>
                    </div>

                    <div class="like-comment-contents">
                        <div class="like-comment">
                            <span id="like-btn" data-id="{{ $product->id }}">
                                <img id="like-icon" src="{{  auth()->check() && $product->isLikedBy(auth()->user()) ? asset('images/like-pink.png') : asset('images/like.png') }}">
                                <span id="like-count">{{ $product->likes->count() }}</span>
                            </span>

                            <span>
                                <img src="{{ asset('images/comment.png') }}">
                                <span id="comment-count">{{ $product->comments->count() }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="purchase-button-contents">
                <div class="purchase-area">
                    <div class="purchase-button-position">
                        <form method="GET" action="{{ route('purchase.create', ['item_id' => $product->id]) }}">
                            <button class="purchase-button">購入手続きへ</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="product-description-contents">
                <div class="product-description-contents-area">
                    <h2>商品説明</h2>
                    <div class="product-description">{{ $product->description }}</div>
                </div>
            </div>

            <div class="product-info-contents">
                <div class="product-info">
                    <h3>商品の情報</h3>

                    <p class="category-label">カテゴリー</p>
                        <div class="category-names">
                            @foreach($product->categories as $category)
                                <span class="category-name">{{ $category->name }}</span>
                            @endforeach
                        </div>

                    <p class="info-label">商品の状態</p>
                    <div class="condition">{{ $product->condition->name }}</div>
                </div>
            </div>

            <div class="product-comments">

                <div class="product-comment">
                    <h3>コメント ({{ $product->comments->count() }})</h3>
                    <div class="comment-list-area" id="comment-list">
                        @foreach($product->comments->sortByDesc('created_at') as $comment)
                            <div class="comment-list">
                                <div class="user-icon">
                                    @php
                                    $image = optional($comment->user->profile)->image;
                                    @endphp

                                    @if($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="user icon">
                                    @endif
                                </div>
                                <span class="user-name">{{ $comment->user->name }}</span>

                                <div class="comment-body">{{ $comment->comment }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="/comment/{{ $product->id }}">
                    @csrf
                    <p class="product-comment-title">
                        商品へのコメント
                        @error('comment')
                            <span class="error">※{{ $message }}</span>
                        @enderror
                    </p>
                    <div class="textarea-area">
                        <textarea name="comment"></textarea>
                    </div>
                    <div class="submit-button-contents">
                        <div class="submit-button-area">
                            <div class="submit-button-position">
                                <button type="submit">コメントを送信する</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('like-btn').addEventListener('click', function () {
        let productId = this.dataset.id;

        fetch(`/like/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
            .then(res => {
                if (res.status === 401) {
                    window.location.href = '/login';
                    return;
                }

                if (res.status === 403) {
                    window.location.href = '/email/verify';
                    return;
                }

                return res.json();
            })
            .then(data => {
                if (!data) return;

                document.getElementById('like-count').innerText = data.count;

                let icon = document.getElementById('like-icon');
                icon.src = data.liked
                    ? '/images/like-pink.png'
                    : '/images/like.png';
            })
            .catch(err => console.error('LIKE ERROR:', err));
    });
</script>
@endsection