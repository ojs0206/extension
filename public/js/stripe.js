var stripe = Stripe('pk_test_FK1ocQTd9WzPUpvCB4cpjIri009wXSFbAn');
var elements = stripe.elements();

var style = {
    base: {
        iconColor: '#666EE8',
        color: '#31325F',
        lineHeight: '40px',
        fontWeight: 300,
        fontFamily: 'Helvetica Neue',
        fontSize: '15px',

        '::placeholder': {
            color: '#CFD7E0',
        },
    },
};

var cardNumberElement = elements.create('cardNumber', {
    style: style
});
cardNumberElement.mount('#card-number-element');

var cardExpiryElement = elements.create('cardExpiry', {
    style: style
});
cardExpiryElement.mount('#card-expiry-element');

var cardCvcElement = elements.create('cardCvc', {
    style: style
});
cardCvcElement.mount('#card-cvc-element');


function setOutcome(result) {
    var errorElement = document.querySelector('.error');
    errorElement.classList.remove('visible');

    if (result.token) {
        $('#card_token').val(result.token.id);
        $('#frm-award').submit();
    } else if (result.error) {
        errorElement.textContent = result.error.message;
        errorElement.classList.add('visible');
    }
}

var cardBrandToPfClass = {
    'visa': 'pf-visa',
    'mastercard': 'pf-mastercard-alt',
    'amex': 'pf-american-express',
    'discover': 'pf-discover',
    'diners': 'pf-diners',
    'jcb': 'pf-jcb',
    'unknown': 'pf-credit-card',
}

function setBrandIcon(brand) {
    var brandIconElement = document.getElementById('brand-icon');
    var pfClass = 'pf-credit-card';
    if (brand in cardBrandToPfClass) {
        pfClass = cardBrandToPfClass[brand];
    }
    for (var i = brandIconElement.classList.length - 1; i >= 0; i--) {
        brandIconElement.classList.remove(brandIconElement.classList[i]);
    }
    brandIconElement.classList.add('pf');
    brandIconElement.classList.add(pfClass);
}

cardNumberElement.on('change', function(event) {
    // Switch brand logo
    if (event.brand) {
        setBrandIcon(event.brand);
    }
    setOutcome(event);
});

function checkValidStripe(){
    stripe.createToken(cardNumberElement).then(setOutcome);
};
