#==========================================
# Debug mode
#==========================================
DEBUG = true
# DEBUG = false


#==========================================
# Default variables
#==========================================


#==========================================
# Default helper
#==========================================
xx = (x) -> DEBUG && console.log x
float = (val) -> parseFloat val.replace 'px', ''
headerTo = (path) -> window.location = path


#==========================================
# Events
#==========================================
$ ->
  $.ajaxSetup headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

  # NProgress setting
  NProgress.configure
    showSpinner: false
