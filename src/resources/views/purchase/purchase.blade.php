@extends('common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')

<form id="purchase-form" action="{{ route('purchase.create', $product->id) }}" method="GET">
    @csrf
    <div class="product-field">
        <img src="{{ asset('storage/' . $product->image) }}" class="product-image">

        <div class="product-info">
            <p class="product-name">{{ $product->name }}</p>
            <p class="product-price">￥{{ number_format($product->price) }}</p>
        </div>
    </div>

    <hr class="first-line">

    <div class="payment-field">
        <p class="section-title">
            支払い方法
            @error('payment')
                <span class="error">※{{ $message }}</span>
            @enderror
        </p>

        <div class="custom-select payment-select">
            <div class="selected" id="selected">選択してください</div>

            <ul class="options" id="options">
                <li data-value="convenience">コンビニ払い</li>
                <li data-value="credit">カード支払い</li>
            </ul>

            <input type="hidden" name="payment" id="payment-hidden">
        </div>
    </div>

    <hr class="second-line">

    <div class="address-field">
        <div class="address-title">
            配送先
            @if($errors->has('post_code') || $errors->has('address'))
                <span class="error">※配送先を入力してください</span>
            @endif
        </div>
        <a class="address-change" href="/purchase/address/{{ $product->id }}" >変更する</a>

        <div class="address-content">
            <p>〒{{ $shipping['post_code'] ?? $profile->post_code }}</p>
            <p>{{ $shipping['address'] ?? $profile->address }} {{ $shipping['building'] ?? $profile->building }}</p>
        </div>
    </div>

    <hr class="third-line">

    <div class="total-field">
        <div class="total-row">
            <p class="total-title">商品代金</p>
            <p class="total-price">￥{{ number_format($product->price) }}</p>
        </div>

        <div class="total-row">
            <p class="payment-method">支払い方法</p>
            <p class="how-to-pay" id="selected-payment">
                @php
                    $payment = session('payment');
                @endphp

                @if($payment === 'credit')
                    カード支払い
                @elseif($payment === 'convenience')
                    コンビニ払い
                @else
                    未選択
                @endif
            </p>
        </div>
    </div>
</form>

<form method="POST" action="{{ route('purchase.checkout', $product->id) }}">
    @csrf
    <input type="hidden" name="payment" id="checkout-payment" value="{{ session('payment') }}">
    <input type="hidden" name="post_code" value="{{ $shipping['post_code'] ?? $profile->post_code }}">
    <input type="hidden" name="address" value="{{ $shipping['address'] ?? $profile->address }}">
    <input type="hidden" name="building" value="{{ $shipping['building'] ?? $profile->building }}">

    <div class="purchase-button">
        <button class="button-text">購入する</button>
    </div>
</form>


<script>
document.addEventListener('DOMContentLoaded', function() {

    const selected = document.getElementById('selected');
    const options = document.getElementById('options');
    const hidden = document.getElementById('payment-hidden');
    const display = document.getElementById('selected-payment');
    const checkoutHidden = document.getElementById('checkout-payment');

    selected.addEventListener('click', () => {
        options.classList.toggle('open');

        selected.style.display = 'none';

        selected.classList.add('no-arrow');
    });

    options.querySelectorAll('li').forEach(option => {
        option.addEventListener('click', () => {

            options.querySelectorAll('li').forEach(li => li.classList.remove('active'));
            option.classList.add('active');

            hidden.value = option.dataset.value;

            checkoutHidden.value = option.dataset.value;

            selected.textContent = option.textContent;

            selected.classList.add('no-arrow');

            options.classList.remove('open');

            selected.style.display = 'block';
            const form = document.getElementById('purchase-form');
            form.submit();
        });
    });

});
</script>

@endsection