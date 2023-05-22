


$( document ).ready(function() {

  var lang = $.i18n.lng();
  //$('#profileLocaleSelect')

  // Theme switcher
  $('#profileThemeSelect').on('change', function () {
    $( "#profileThemeSelect option:selected" ).each(function() {
      var theme = $(this).data('switch')
      mr.setPref('theme', $(this).data('switch'));
      mr.loadTheme();
    });
  });

  $('#profileLocaleSelect').on('change', function() {
    $( "#profileLocaleSelect option:selected" ).each(function() {
      var locale = $(this).val();
      console.log(locale);
      $.i18n.setLng(locale);
    });
  });

  $('#profileForm').submit(function(e) {
    e.preventDefault();

  })
});
