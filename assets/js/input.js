(function ($) {

  function initialize_field ($el) {
    $el.find('input[type=tel]').each(function () {

      let telInput = $(this),
        errorMsg = $(this).next();

      // Initialize intl-phone-input
      let initialCountry = telInput.attr('data-initial-country') || 'CA';
      telInput.intlTelInput({
        utilsScript: settings.url + 'assets/js/utils.js',
        initialCountry: initialCountry,
      });

      let reset = function () {
        telInput.removeClass('error');
        errorMsg.addClass('hide');
      };

      let validate = function () {
        if (telInput.val()) {
          if (telInput.intlTelInput('isValidNumber')) {
            telInput.val(telInput.intlTelInput('getNumber', intlTelInputUtils.numberFormat.NATIONAL));
          }
          else {
            errorMsg.text(settings.errors[telInput.intlTelInput('getValidationError')] || 'Invalid phone number');
            errorMsg.removeClass('hide');
            telInput.addClass('error');
          }
        }
      };

      // Validate and format phone number on blur
      telInput.on('blur', function () {
        reset();
        validate();
      });
      telInput.on('keyup change', reset);
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
