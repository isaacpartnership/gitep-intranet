console.log('log me');

wp.media.controller.VcSingleImage = wp.media.controller.FeaturedImage.extend({
  defaults: _.defaults({
    id: "vc_single-image",
    filterable: "all",
    library: wp.media.query({
      uploadedTo: wp.media.view.settings.post.id
    }),
    multiple: !1,
    toolbar: "vc_single-image",
    priority: 60,
    syncSelection: !1
  }, wp.media.controller.Library.prototype.defaults),
  updateSelection: function() {
    var attachments, selection = this.get("selection"),
      ids = wp.media.vc_editor.getData();
    this.get("library");
    void 0 !== ids && "" !== ids && -1 !== ids && (attachments = _.map(ids.toString().split(/,/), function(id) {
      var attachment = wp.media.model.Attachment.get(id);
      return attachment.fetch(), attachment
    })), selection.reset(attachments)
  }
});
jQuery(document).on("click", ".add_docs", function(event) {
  console.log('logged');
  event.preventDefault();
  var $this = jQuery(this);
  if (wp.media.vc_editor.$vc_editor_element = jQuery(this), "true" === $this.attr("use-single")) return void wp.media.VcSingleImage.frame(this).open("vc_editor");
  $this.blur(), media.vc_editor.open("visual-composer")
});
