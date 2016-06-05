<div class="row">
    <div class="col-md-12">
        <form action="{{$shopSettings['form_url']}}" method="post">
            <input name="shopId" value="{{$shopSettings['shopId']}}" type="hidden"/>
            <input name="scid" value="{{$shopSettings['scid']}}" type="hidden"/>
            <input name="sum" type="text">
            <input name="customerNumber" value="{{Auth::user()->id}}" type="hidden"/>
            <select name="type" id="" oninput="document.getElementById('paymentType').value = this.value;">
                <option value="AC">Оплата с банковской карты</option>
                <option value="PC">Оплата из кошелька в Яндекс.Деньгах</option>
            </select>
            <input name="paymentType" value="AC" type="hidden" id="paymentType"/>
            <input name="orderNumber" value="{{uniqid()}}" type="hidden"/>
            <input name="cps_phone" value="79110000000" type="hidden"/>
            <input name="cps_email" value="user@domain.com" type="hidden"/>
            <input type="submit" value="Заплатить"/>
        </form>
    </div>
</div>
