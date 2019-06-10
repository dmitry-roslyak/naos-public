<div>
     <h4 style="color:cornflowerblue;text-align:center;">
        @lang('mail.welcome'), {{ $user }} !
    </h4>
    <h4 style="background-color:cornflowerblue;padding:12px 0;width:100%;color:white;text-align:center;">
        @lang('mail.welcome2') !
    </h4>
    <table>
        @foreach ($products as $item)
        <tr class="product">
            <td class="img-cell"><img src="{{ url('/').$item['img_src'] }}"></td>
            <td style="position:relative">
                <a href="{{ url('/').'/detail/'.$item['id'] }}" >
                    {{ $item['name'] }}
                </a>
                <div class="status-bar">
                    @if ($item['type']==1)
                    <span>@lang('mail.priceHasLowered')</span>
                    @elseif ($item['type']==2)
                    <span style="background-color:tomato">@lang('mail.offer')</span>
                    @else
                    <span style="background-color:limegreen">@lang('mail.inStock')</span>
                    @endif
                </div>
                <div class="price-bar"> 
                    @if ($item['old_price'])<s>{{$item['old_price']}}</s>@endif<a href="{{ url('/').'/cart/['.$item['id'].']' }}">
                    {{ $item['price'] }}
                        @if ($currency == 'RUB')
                            Руб.
                        @elseif ($currency == 'UAH')
                            Грн.
                        @else
                            $
                        @endif
                    </a>
                </div>
            </td>
        </tr>     
        @endforeach
    </table>
    <div class="buy-btn"> 
        <a href="{{ url('/') }}<?php 
                $tmp =[];
                foreach($products as $val)
                {
                    array_push($tmp,$val['id']);
                }
                echo '/cart/'.json_encode( $tmp );
            ?>" >
            @lang('mail.buyNow') !
        </a>
    </div> 
    <h4 style="background-color:cornflowerblue;padding:12px 0;width:100%;text-align:center">
        <a href="{{ url('/') }}" style="color:white;">@lang('mail.onlineStore') NAOS</a>
    </h4>
</div>