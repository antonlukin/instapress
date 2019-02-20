jQuery(document).ready(function ($) {
  if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
    return false;
  }

  var box = $('#instapress-featured-metabox');


  /**
   * Set featured image using wp.media form
   */
  function setFeatured(e) {
    e.preventDefault();

    // Set options from localize array
    var options = {};
    if (typeof instapress_featured === 'object') {
      options = instapress_featured;
    }

    // Open default wp.media image frame
    var frame = wp.media({
      multiple: false,
      title: options.title || ''
    });

    // On open frame select current attachment
    frame.on('open', function () {
      var selection = frame.state().get('selection');
      var attachment = $('#instapress-featured').val();

      selection.add(wp.media.attachment(attachment));
    });

    // On image select
    frame.on('select', function () {
      var selection = frame.state().get('selection').first().toJSON();

      // Set hidden input value
      $('#instapress-featured').val(selection.id);

      // Set featured as selection if exists
      if (typeof selection.sizes.featured !== 'undefined') {
        selection = selection.sizes.featured;
      }

      var inside = box.find('.inside');

      // Create image if not exists
      if (box.find('.featured-image').length === 0) {
        $('<img />', { class: 'featured-image' }).prependTo(inside);
      }

      box.find('.featured-image').attr('src', selection.url);
    });

    return frame.open();
  }


  /**
   * Set image on poster click
   */
  box.on('click', '.featured-image', setFeatured);


  /**
   * Set image on append button click
   */
  box.on('click', '.featured-append', setFeatured);


  /**
   * Remove featured on buttom click
   */
  box.on('click', '.featured-remove', function (e) {
    e.preventDefault();

    // Set hidden input value
    $('#instapress-featured').val('');

    // Remove image
    box.find('.featured-image').remove();
  });
});