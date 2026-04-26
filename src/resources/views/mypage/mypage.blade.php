@extends('common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css')}}">
@endsection

@section('content')
<div class="mypage-page">

    <div class="profile-area">
        <div class="profile-icon">
            <img src="{{ !empty($profile->image) ? asset('storage/' . $profile->image) : asset('images/default.png') }}" alt="">
        </div>

        <h1 class="mypage-title">{{ $user->name }}</h1>

        <a class="mypage-edit" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <nav class="sub-tabs">
        <ul class="sub-tabs__list">
            <li class="sub-tabs__item sub-tabs__item-sell {{ request('tab', 'sell') === 'sell' ? 'is-active' : '' }}">
                <a href="?tab=sell&keyword={{ request('keyword') }}">出品した商品</a>
            </li>

            <li class="sub-tabs__item sub-tabs__item-buy {{ request('tab') === 'buy' ? 'is-active' : '' }}">
                <a href="?tab=buy&keyword={{ request('keyword') }}">購入した商品</a>
            </li>
        </ul>
    </nav>

    <div class="product-list">
        @foreach($products as $product)
            <a href="/item/{{ $product->id }}" class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}">

                @if($product->is_sold)
                    <span class="sold-label">Sold</span>
                @endif

                <p>{{ $product->name }}</p>
            </a>
        @endforeach
    </div>
</div>

@endsection