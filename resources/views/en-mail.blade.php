
<div style="position:relative;left:50%;margin-left:-300px;width:600px">
     <h4 style="color:cornflowerblue;text-align:center;">
        Dear, {{ $user }} !
    </h4>
    <h4 style="background-color:cornflowerblue;padding:12px 0;width:100%;color:white;text-align:center;">
        Maybe, this proposal could be interasting for you!
    </h4>
    @foreach ($products as $item)
    <table style="width:100%;border:1px solid lightgray;margin:8px 0;border-radius:4px">
        <tr>
            <td><img src="https://cdn.f1ne.ws/userfiles/carey/122920_sm.jpg" style="height:96px;width:auto"> </td>
            <td style="position:relative;height:100%;width:70%">
                <div style="height:100%"> 
                    <a href="{{ $_SERVER['APP_URL'].'#/detail/'.$item['id'] }}" 
                                >{{ $item['name'] }}
                    </a>
                    <div style="position:absolute;bottom:4px;right:-8px"> 
                        <nobr>@if ($item['old_price'])
                            <s style="color:white;background-color:gray;padding:8px;border-radius:8px 0 0 0">
                                {{ $item['old_price'] }}
                            </s>@endif
                            <a href="{{ $_SERVER['APP_URL'].'#/cart/['.$item['id'].']' }}" style="color:white;background-color:cornflowerblue;padding:8px">
                            {{ $item['price'] }}
                                @if ($currency == 'RUB' || $currency == 'UAH')
                                    {{ $currency }}
                                @else
                                    $
                                @endif
                            </a>
                        </nobr>
                    </div>
                    <div style="position:absolute;top:2px;right:-4px">
                        @if ($item['type']==1)
                        <span style="color:white;background-color:cornflowerblue;padding:5px;border-radius:0 0 0 8px">
                            <nobr>Price down</nobr>
                        </span>
                        @elseif ($item['type']==2)
                        <span style="color:white;background-color:tomato;padding:5px;border-radius:0 0 0 8px">
                            <nobr>Offer</nobr>
                        </span>
                        @else
                        <span style="color:white;background-color:limegreen;padding:5px;border-radius:0 0 0 8px">
                            <nobr>In stock</nobr>
                        </span>
                        @endif
                    </div>
                </div>
            </td>
        </tr>     
    </table>
    @endforeach
    
    <div style="margin-top:20px"> 
        <a href=
        "{{ $_SERVER['APP_URL'] }}<?php 
        $tmp =[];
        foreach($products as $val)
        {
            array_push($tmp,$val['id']);
        }
        echo '/#/cart/'.json_encode( $tmp );?>" 
            style="position:relative;padding:12px;left:50%;margin-left:-60px;background-color:cornflowerblue;color:white">
            Add to cart!
        </a>
    </div> 
    <h4 style="background-color:cornflowerblue;padding:12px 0;width:100%;text-align:center">
        <a href="{{ $_SERVER['APP_URL'] }}" style="color:white;">Webstore NAOS</a>
    </h4>
</div>
                <!-- <img src="{{ $order['img_src'] ">  -->