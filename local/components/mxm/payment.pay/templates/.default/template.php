<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>

<script>
    $( document ).ready(function() {
        var receipt = {
            "Items": [//товарные позиции
                <? foreach($arResult['ORDER_ITEMS'] as $arItem): ?>
                {
                    "label": "<?=$arItem['LABEL']?>", //наименование товара
                    "price": <?=$arItem['PRICE']?>, //цена
                    "quantity": <?= $arItem['QUANTITY'] ?>, //количество
                    "amount": <?=$arItem['AMOUNT']?>, //сумма
                    "vat": <?=$arItem['VAT']?>, //ставка НДС
                    "method": 0,
                    "object": 0,
                    "measurementUnit": "<?=$arItem['UNIT']?>" //единица измерения
                }
                <? endforeach ?>
            ],
            "calculationPlace": "maximatv.ru", //место осуществления расчёта, по умолчанию берется значение из кассы
            "email": "<?=$arResult['USER']['EMAIL']?>", //e-mail покупателя, если нужно отправить письмо с чеком
            "amounts":
                {
                    "electronic": <?=$arResult['PRICE']?>, // Сумма оплаты электронными деньгами
                    "advancePayment": 0.00, // Сумма из предоплаты (зачетом аванса) (2 знака после запятой)
                    "credit": 0.00, // Сумма постоплатой(в кредит) (2 знака после запятой)
                    "provision": 0.00 // Сумма оплаты встречным предоставлением (сертификаты, др. мат.ценности) (2 знака после запятой)
                }
        };
        var data = {
            "cloudPayments": {
                "customerReceipt": receipt //онлайн-чек
            }
        };
        var widget = new cp.CloudPayments();
        widget.charge({
                publicId: '<?=$arParams['publicId']?>',
                description: '<?=$arResult['DESCRIPTION']?>',
                amount: <?=$arResult['PRICE']?>,
                currency: 'RUB',
                invoiceId: '<?=$arResult['ORDER_ID']?>',
                accountId: '<?=$arResult['USER']['ID']?>',
                skin: "classic",
                data: data
            },
            function (options) { // success
                fbq('track', 'Purchase', {content_name: '<?=$arResult["DESCRIPTION"]?>', value: <?=$arResult['PRICE']?>, currency: 'RUB'});
                setTimeout("window.location.href = '<?=$arResult['BACKURL']?>' + '?successful_subscription=Y'", 1000);
            },
            function (reason, options) { // fail
                window.location.href = '<?=$arResult['BACKURL']?>';
            });
    });
</script>