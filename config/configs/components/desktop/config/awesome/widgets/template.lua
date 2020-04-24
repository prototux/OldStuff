-- Getting the env
local wibox = require("wibox")
local beautiful = require("beautiful")

-- The widget
local m = {}

m.icon = wibox.widget.imagebox()
m.text = wibox.widget.textbox()

function m.worker(format, warg)
end

function m.format(widget, args)
end

return m
