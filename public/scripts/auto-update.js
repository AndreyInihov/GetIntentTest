function UpdateCurrencies() {
    $.ajax({
        url: '/get-currencies',
        type: 'POST',
        success: function(Response) {
            $('#wrapper').html('');
            $.each(Response, function(index, currency) {
                $('#wrapper').append('<div class="currency-container"><span class="currency-date">'+currency.Datetime+'</span><span class="currency-value">'+currency.Name+': '+currency.Value+'</span></div>');
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Ошибка обновления курса валют');
        }
    });
}

UpdateCurrencies();
setTimeout(UpdateCurrencies, 60000);
