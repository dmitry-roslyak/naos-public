"use strict";
if(window.location.hash.indexOf("detail") > 0) {
    console.log(window.location.hash.indexOf("detail"))
    var array = window.location.hash.split('/')
    var id = array[array.length-1];

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'prod_by_id?id='+id, true);
    xhr.send();
    xhr.onreadystatechange = function() { // (3)
    // xhr.onload = function() { // (3)
        if (xhr.readyState != 4) return;
        if (xhr.status != 200) {
            console.log(xhr)
        } else {
            newSchema(JSON.parse(xhr.responseText))
        }
    }
}
function newSchema(item){
    var prodSchema = document.createElement('script');
    prodSchema.setAttribute('type','application/ld+json');
    var obj = {
        "@context": "http://schema.org/",
        "@type": "Product",
        name: item.name,
        image: window.location.hostname+'/file/'+item.img_src,
        description: item.description,
        brand: {
            "@type": "Thing",
            name: "ACME"
        },
        aggregateRating: {
            "@type": "AggregateRating",
            ratingValue: item.rating,
            reviewCount: item.vote_count
        },
        offers: {
            "@type": "Offer",
            priceCurrency: window.lng.currency,
            price: item.price,
            priceValidUntil: new Date(),
            itemCondition: "http://schema.org/UsedCondition",
            availability: item.available>1?"InStock":"OutOfStock",
            seller: {
                "@type": "Organization",
                name: "NAOS"
            }
        }
    };
    prodSchema.innerText=JSON.stringify(obj);
    document.getElementsByTagName('head')[0].appendChild(prodSchema);
}