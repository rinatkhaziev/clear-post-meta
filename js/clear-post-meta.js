(document.querySelectorAll('.clear-post-meta-target') || []).forEach(function (el) {
  el.addEventListener('click', CPMClearPostMeta)
})

/**
 * @param Event e
 */
function CPMClearPostMeta(e) {
  e.preventDefault()
  var btn = e.currentTarget
  if (window.confirm("This will delete all post meta, are you sure?")) {
    fetch(ClearPostMeta.ajaxurl, {
      credentials: "include"
    })
    .then(function(res) {
      return res.json()
    })
    .then(function(json) {
      alert(json.data.message)
    })
  }
}