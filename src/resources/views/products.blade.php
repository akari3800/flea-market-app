@extends('common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/products.css')}}">
@endsection

@section('content')

@php
$isVerifiedUser = Auth::check() && Auth::user()->hasVerifiedEmail();
$activeTab = request('tab') ?? ($isVerifiedUser ? 'mylist' : 'recommend');
@endphp

<nav class="top-tabs">
    <ul class="top-tabs__list">
        <li class="top-tabs__item top-tabs__item-recommend {{ $activeTab === 'recommend' ? 'is-active' : '' }}">
            <a href="{{ route('products.index', ['tab' => 'recommend', 'keyword' => request('keyword')]) }}">おすすめ</a>
        </li>

        <li class="top-tabs__item top-tabs__item-mylist {{ $activeTab === 'mylist' ? 'is-active' : '' }}">
            <a href="{{ route('products.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}">マイリスト</a>
        </li>
    </ul>
</nav>
<hr class="line">

<div class="product-list">
    @foreach($products as $product)
        <a href="{{ route('item.show', $product->id) }}" class="product-card">
            <img src="{{ asset('storage/' . $product->image )}}" alt="">

            @if($product->is_sold)
                <span class="sold-label">Sold</span>
            @endif

            <p>{{ $product->name }}</p>
        </a>
    @endforeach
</div>
@endsection
