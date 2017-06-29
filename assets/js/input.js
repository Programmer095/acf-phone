(function ($) {

  function initialize_field ($el) {
    $el.find('div.acf-input-wrap.phone').each(function () {

      let nationalInput = $(this).find('input[type=tel]'),
        countryInput = $(this).find('input.country'),
        e164Input = $(this).find('input.e164'),
        errorMsg = $(this).find('span.error-msg');

      // Initialize intl-phone-input
      let initialCountry = countryInput.val() || nationalInput.attr('data-initial-country') || 'CA';
      nationalInput.intlTelInput({
        utilsScript: acfPhoneSettings.url + 'assets/js/utils.js',
        initialCountry: initialCountry,
      });

      // Reset validation status and hidden field values
      let reset = function () {
        nationalInput.removeClass('error');
        countryInput.val('');
        e164Input.val('');
        errorMsg.addClass('hide');
      };

      // Validate phone number
      let validate = function () {
        if (nationalInput.val()) {
          if (nationalInput.intlTelInput('isValidNumber')) {
            // Update formatted field (national) and hidden fields (country / e164)
            nationalInput.val(nationalInput.intlTelInput('getNumber', intlTelInputUtils.numberFormat.NATIONAL));
            countryInput.val(nationalInput.intlTelInput('getSelectedCountryData').iso2.toUpperCase());
            e164Input.val(nationalInput.intlTelInput('getNumber', intlTelInputUtils.numberFormat.E164));
          }
          else {
            errorMsg.text(acfPhoneSettings.errors[nationalInput.intlTelInput('getValidationError')] || 'Invalid phone number');
            errorMsg.removeClass('hide');
            nationalInput.addClass('error');
          }
        }
      };

      // Validate and format phone number on blur
      nationalInput.on('blur', function () {
        reset();
        validate();
      });
      nationalInput.on('keyup change', reset);
    });
  }

  if (typeof acf.add_action !== 'undefined') {
    // ACF 5
    acf.add_action('ready append', function ($el) {
      acf.get_fields({type: 'phone'}, $el).each(function () {
        initialize_field($(this));
      });
    });
  } else {
    // ACF 4
    $(document).on('acf/setup_fields', function (e, postbox) {
      $(postbox).find('.field[data-field_type="phone"]').each(function () {
        initialize_field($(this));
      });
    });
  }

})(jQuery);
