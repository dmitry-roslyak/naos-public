<?php

namespace App\Http\Traits;

trait Utility
{
    public static function locale(array $array = [])
    {
        if (!empty($array)) { } else if (!empty($_COOKIE['lang'])) $array[0] = $_COOKIE['lang'];
        else if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $array = explode(',', str_replace(";", ',', $_SERVER['HTTP_ACCEPT_LANGUAGE']));
        }

        $lang = 'en';
        $currency = 'USD';

        for ($index = 0; $index < count($array); $index++) {
            if ($array[$index] == 'ru' || $array[$index] == 'ru-RU') {
                $lang = 'ru';
                $currency = 'RUB';
                break;
            } else if ($array[$index] == 'uk') {
                $lang = 'uk';
                $currency = 'UAH';
                break;
            }
        }
        return [
            'language' => $lang,
            'currency' => $currency
        ];
    }
    public static function data_fetch()
    {
        $user = \Auth::user();
        $locale = $user ? $user : Utility::locale();
        return [
            'langsAvailable' => \App\Lang::where('name', 'lang_name')->get(['text', 'lng'])->keyBy('lng'),
            'user' => $user,
            'catalog' => \App\Category::get()->keyBy('name'),
            'lng' => \App\Lang::where('lng', $locale['language'])->get(['name', 'text'])->mapWithKeys(function ($item) {
                return [$item['name'] => $item['text']];
            }),
            'currency' => \App\Currency::where('name', $locale['currency'])->orderBy('date', 'desc')->first()
        ];
    }
    public static function OpenGraph(\App\Product $product)
    {
        $url = url()->current();
        $image = url('/') . '/file/' . $product['img_src'];
        $availability = $product['available'] > 1 ? "instock" : "oos";
        $app_id = '1358482950908486';
        $markup = "
            <meta property='og:type' content='product' />
            <meta property='og:title' content='{$product['name']}' />
            <meta property='og:url' content='{$url}' /> 
            <meta property='og:image' content='{$image}' />
            <meta property='og:description' content='{$product['description']}' />
            <meta property='og:availability' content='{$availability}' />
            <meta property='fb:app_id' content='{$app_id}' />
        ";
        
        $date = \App\Currency::orderBy('date', 'desc')->first('date');
        if(empty($date)) return;
        $date = $date['date'];
        $currencies = \App\Currency::where('date', $date)->get();

        foreach ($currencies as $key => $currency) {
            $price = $currency['rate'] * $product['price'];
            $markup = $markup."<meta property='product:price:amount' content='{$price}'/>";
            $markup = $markup."<meta property='product:price:currency' content='{$currency['name']}'/>";
        }
        echo $markup;
    }
    public static function ld_json(\App\Product $product)
    {
        $url = url()->current();
        $image = url('/') . '/file/' . $product['img_src'];
        echo '<script type="application/ld+json">' .
            json_encode([
                "@context" => "https://schema.org/",
                "@type" => "Product",
                "name" => $product['name'],
                "image" => $image,
                "description" => $product['description'],
                "brand" => [
                    "@type" => "Thing",
                    "name" => "ACME"
                ],
                "aggregateRating" => [
                    "@type" => "AggregateRating",
                    "ratingValue" => $product['rating'],
                    "reviewCount" => $product['vote_count']
                ],
                "offers" => [
                    "@type" => "Offer",
                    "url" => $url,
                    "priceCurrency" => "USD",
                    "price" => $product['price'],
                    "priceValidUntil" => date_modify(new \DateTime(), '+1 day')->format('Y-m-d'),
                    "itemCondition" => "https://schema.org/NewCondition",
                    "availability" => $product['available'] > 1 ? "InStock" : "OutOfStock",
                    "seller" => [
                        "@type" => "Organization",
                        "name" => "NAOS"
                    ]
                ]
            ]) . '</script>';
    }
}
