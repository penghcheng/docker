local template = require "resty.template"
template.render("view.html", { message = "Hello, World!" })
