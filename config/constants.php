<?php

return [
    "xpath" => "//button[span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'basket')] 
                    or span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'bag')] 
                    or span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'buy')] 
                    or span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'purchase')] 
                    or span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'cart')]]
                    | //button[@aria-label='add to basket']
                    | //button[@data-tn='item-actions-purchase-item']",
                    // | //button[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'basket')
                    //     or contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'bag')
                    //     or contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'buy')
                    //     or contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'purchase')
                    //     or contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'cart')] 
                    //     or contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'basket')] 
    "attributes" => ["cy-basketaddbutton"],
    "furnitureTypes" => [   'rug', 'bedding', 'bed', 'sheet', 'duvet', 'table', 'sofa', 'chair', 'sideboard', 
                            'cabinet', 'lamp', 'light', 'mattress', 'pillow', 'mirror', 'cushion'
                        ],
    "titleXPaths" => [
        1 => "//h1[@class='product-detail__title']",
    ],

    "priceXPaths" => [
        1 => "//dd[@class='product-detail__prices']"
    ]
];