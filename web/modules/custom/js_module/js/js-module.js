(function ($,Drupal) {
  Drupal.behaviors.jsModule = {
    attach: function (context) {
      $('.text-content.clearfix.field.field--name-body.field--type-text-with-summary.field--label-hidden.field__item', context).each(function () {
        var $element = $(this);
        var fullContent = $element.html();
        var shortContent = fullContent.split(' ').slice(0, 50).join(' ');

        $element.data('full-content', fullContent);
        $element.data('short-content', shortContent);
        $element.html(shortContent);

        var readMoreButton = $('<a class="read-more" href="#">Read More</a>');
        var showLessButton = $('<a class="show-less" href="#">Show Less</a>');

        $element.after(readMoreButton);
        readMoreButton.on('click', function (e) {
          e.preventDefault();
          $element.html(fullContent);
          $(this).hide();
          $element.after(showLessButton);
        });

        showLessButton.on('click', function (e) {
          e.preventDefault();
          $element.html(shortContent);
          $(this).hide();
          readMoreButton.show();
        });
      });
    }
  };
})(jQuery,Drupal);
