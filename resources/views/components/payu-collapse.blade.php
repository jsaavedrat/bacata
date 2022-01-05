<label class="mt-3">Detalles Tarjeta</label>

<div class="form-group form-row">
    <div class="col-4">
        <input class="form-control" name="payu_card" type="text" placeholder="Numero de Tarjeta">
    </div>

    <div class="col-2">
        <input class="form-control" name="payu_cvc" type="text" placeholder="CVC">
    </div>

    <div class="col-2">
        <input class="form-control" name="payu_month" type="text" placeholder="MM">
    </div>

    <div class="col-2">
        <input class="form-control" name="payu_year" type="text" placeholder="AAAA">
    </div>

    <div class="col-2">
        <select class="custom-select" name="payu_network">
            <option selected>Seleccionar</option>
            <option value="visa">VISA</option>
            <option value="amex">AMEX</option>
            <option value="diners">DINERS</option>
            <option value="mastercard">MASTERCARD</option>
        </select>
    </div>
</div>



<div class="form-group form-row">
    <div class="col-6">
        <input class="form-control" name="payu_name" type="text" placeholder="Nombre">
    </div>
    <div class="col-6">
        <input class="form-control" name="payu_email" type="email" placeholder="email@ejemplo.com" >
    </div>
</div>


{{--  <div class="form-group form-row">
    <div class="col">
        <small class="form-text text-mute"  role="alert" >Your payment will be converted to {{ strtoupper(config('services.payu.base_currency')) }}</small>
    </div>
</div>  --}}
