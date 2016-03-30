/**
 * @file
 */

(function($, Drupal, window, document, undefined) {

  Drupal.behaviors.chiib = {
    attach: function(context, settings) {
      $('.ch-sources select').bind('change', function() {
        window.location = $(this).val();
      });
    }
  }

})(jQuery, Drupal, this, this.document);