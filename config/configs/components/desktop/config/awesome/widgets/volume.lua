-- Getting the env
local beautiful = require("beautiful")
local wibox = require("wibox")
local awful = require("awful")

-- The widget
local volume = {}

local level = 0
local muted = false

volume.icon = wibox.widget.imagebox()
volume.text = wibox.widget.textbox()

function volume.worker(format, warg)
	muted = awful.util.pread("pacmd list-sinks | awk '/muted: /{ print $2 }'") == "yes\n" and true or false
	level = volume.get()
	return {} 
end

function volume.format(widget, args)
    if muted == true or level == 0 then
        volume.icon:set_image(beautiful.volume_mute)
		return "<span color=\"#e52222\">--</span>"
    elseif level >= 50 then
        volume.icon:set_image(beautiful.volume_high)
		return "<span color=\"#a6e32d\">"..level.."</span>"
    elseif level >= 30 and level < 50 then
        volume.icon:set_image(beautiful.volume_medium)
		return "<span color=\"#1e80cc\">"..level.."</span>"
    else
        volume.icon:set_image(beautiful.volume_low)
		return "<span color=\"#fc951e\">"..level.."</span>"
    end
end

function volume.set(level)
	awful.util.spawn("pactl set-sink-volume 0 "..level.."%", false)
end

function volume.get()
    -- Re-read level to avoid race condition-ish situation
    return tonumber(awful.util.pread("pacmd list-sinks | awk -F'[[:blank:]:%]+' 'NF == 16 && $2 == \"volume\" { print $6; exit }'"))
end

function volume.raise()
	volume.set(volume.get() <= 95 and "+5" or "100")
	volume.update()
end

function volume.lower()
	volume.set("-5")
	volume.update()
end

function volume.toggle()
	awful.util.spawn("pactl set-sink-mute 0 toggle", false)
	volume.update()
end

function volume.update()
	vicious.force({volume.text})
end

return volume
