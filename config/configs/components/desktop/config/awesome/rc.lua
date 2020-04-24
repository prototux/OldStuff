-- Pixie config file

-- Awesome libs
local gears = require("gears")
local awful = require("awful")
awful.rules = require("awful.rules")
awful.autofocus = require("awful.autofocus")
local wibox = require("wibox")
local beautiful = require("beautiful")
local naughty = require("naughty")
local menubar = require("menubar")
local vicious = require("vicious")

-- Custom widgets
local widgets = {}
widgets.battery = require("widgets.battery")
widgets.volume = require("widgets.volume")

-- Used in notifications
function round(x) if x%2 ~= 0.5 then return math.floor(x+0.5) end return x-0.5 end

-- Error handling
if awesome.startup_errors then naughty.notify({preset = naughty.config.presets.critical, title = "Startup error", text = awesome.startup_errors}) end
awesome.connect_signal("debug::error", function (err) naughty.notify({preset = naughty.config.presets.critical, title = "Awesome error", text = err}) end)

-- Init the theme
beautiful.init(awful.util.getdir("config").."/theme.lua")

-- Default software, mapped to alternatives
terminal = "x-terminal-emulator"
editor = "editor"
editor_cmd = terminal.." -e "..editor
browser = "x-www-browser"

-- WTF? shouldn't be in the theme?
font = "Source Code Pro 12"

-- Mod4 -> Windows/Super
modkey = "Mod4"

-- Layouts list (floating, tile right, tile left, tile bottom, tile top, max, fullscreen)
local layouts = {awful.layout.suit.floating, awful.layout.suit.tile, awful.layout.suit.tile.left, awful.layout.suit.tile.bottom, awful.layout.suit.tile.top, awful.layout.suit.max, awful.layout.suit.max.fullscreen}

-- Set wallpapers (1: laptop screen, 2: vga port // left screen // videoprojector 3: display port // right screen)
gears.wallpaper.maximized(os.getenv("HOME").."/.local/share/wallpapers/laptop.png", 1, true)
if screen.count() == 3 then
	-- Work or home
	gears.wallpaper.maximized(os.getenv("HOME").."/.local/share/wallpapers/octavia.png", 2, true)
	gears.wallpaper.maximized(os.getenv("HOME").."/.local/share/wallpapers/vinyl.png", 3, true)
end

-- Set tags icons/labels
tags = {}   
tags[1] = awful.tag({"", "", "", "", "", "", "", "", ""}, 1, layouts[1])
if screen.count() >= 2 then
	tags[2] = awful.tag({"1", "2", "3", "4", "5", "6", "7", "8", "9"}, 2, layouts[1])
end
if screen.count() >= 3 then
	tags[3] = awful.tag({"a", "b", "c", "d", "e", "f", "g", "h", "i"}, 3, layouts[1])
end

-- Date and clock
timewidget = wibox.widget.textbox()
vicious.register(timewidget, vicious.widgets.date, '<span color="#f2f2f2">%d/%m %H:%M</span>', 20)

-- Wifi signal icon
neticon = wibox.widget.imagebox()
vicious.register(neticon, vicious.widgets.wifi, function(widget, args)
    local sigstrength = tonumber(args["{link}"])
    if sigstrength > 69 then
        neticon:set_image(beautiful.net_wifi_high)
    elseif sigstrength > 40 and sigstrength < 70 then
        neticon:set_image(beautiful.net_wifi_medium)
	elseif sigstrengh == 0 then
		neticon:set_image(beautiful.net_none)
    else
        neticon:set_image(beautiful.net_wifi_low)
    end
end, 120, 'wlan0')

-- Battery and volumes (custom widgets)
vicious.register(widgets.battery.text, widgets.battery.worker, widgets.battery.format, 30, "BAT0")
vicious.register(widgets.volume.text, widgets.volume.worker, widgets.volume.format, 1, "test")

-- Create a wibox for each screen and add it
panel = {}
prompt = {}
layoutbox = {}
taglist = {}
taglist.buttons = awful.util.table.join(awful.button({}, 1, awful.tag.viewonly), awful.button({modkey}, 1, awful.client.movetotag), awful.button({}, 3, awful.tag.viewtoggle), awful.button({modkey}, 3, awful.client.toggletag), awful.button({}, 4, function(t) awful.tag.viewnext(awful.tag.getscreen(t)) end), awful.button({}, 5, function(t) awful.tag.viewprev(awful.tag.getscreen(t)) end))

tasklist = {}
tasklist.buttons = awful.util.table.join(awful.button({}, 1, function (c)
	if c == client.focus then
		c.minimized = true
	else
		c.minimized = false
		if not c:isvisible() then awful.tag.viewonly(c:tags()[1]) end
		client.focus = c
        c:raise()
    end
end))

-- Build the panel for each screen
for s = 1, screen.count() do
    prompt[s] = awful.widget.prompt()

    -- Create an imagebox widget which will contains an icon indicating which layout we're using.
    -- We need one layoutbox per screen.
    layoutbox[s] = awful.widget.layoutbox(s)
    layoutbox[s]:buttons(awful.util.table.join(awful.button({}, 1, function() awful.layout.inc(layouts, 1) end), awful.button({}, 3, function() awful.layout.inc(layouts, -1) end)))

    taglist[s] = awful.widget.taglist(s, awful.widget.taglist.filter.all, taglist.buttons)
    tasklist[s] = awful.widget.tasklist(s, awful.widget.tasklist.filter.currenttags, tasklist.buttons)
    
	-- Create the top bar
	panel[s] = awful.wibox({position = "top", screen = s, height = "20"})

    -- Widgets that are aligned to the left
    local left_layout = wibox.layout.fixed.horizontal()
    left_layout:add(taglist[s])
    left_layout:add(prompt[s])

    -- Widgets that are aligned to the right
    local right_layout = wibox.layout.fixed.horizontal()
    if s == 1 then
		right_layout:add(wibox.widget.systray())
		right_layout:add(widgets.volume.icon)
		right_layout:add(widgets.volume.text)
		right_layout:add(widgets.battery.icon)
		right_layout:add(widgets.battery.text)
		right_layout:add(neticon)
		right_layout:add(timewidget)
	end
	right_layout:add(layoutbox[s])

    -- Now bring it all together (with the tasklist in the middle)
    local layout = wibox.layout.align.horizontal()
    layout:set_left(left_layout)
    layout:set_middle(tasklist[s])
    layout:set_right(right_layout)

    panel[s]:set_widget(layout)
end

-- Keybindings
globalkeys = awful.util.table.join(
	-- Volume keys
	awful.key({}, "XF86AudioMute", widgets.volume.toggle),
	awful.key({}, "XF86AudioLowerVolume", widgets.volume.lower),
	awful.key({}, "XF86AudioRaiseVolume", widgets.volume.raise),
	awful.key({}, "XF86AudioMicMute", function()  awful.util.spawn("pactl set-source-mute 1 toggle", false) end),

	-- Brightness
	awful.key({}, "XF86MonBrightnessUp", function() awful.util.spawn("xbacklight -inc 20", false) naughty.notify({text = "Backlight: "..round(awful.util.pread("xbacklight -get"))}) end),
	awful.key({}, "XF86MonBrightnessDown", function() awful.util.spawn("xbacklight -dec 20", false) end),
	
	-- I should do something with this...
	--awful.key({}, "XF86WLAN", function() naughty.notify({text = "Wlan sw"}) end),

	-- Switching between windows
	awful.key({modkey}, "l", function() awful.client.focus.bydirection("right") if client.focus then client.focus:raise() end end),
	awful.key({modkey}, "h", function() awful.client.focus.bydirection("left") if client.focus then client.focus:raise() end end),
	awful.key({modkey}, "j", function() awful.client.focus.bydirection("down") if client.focus then client.focus:raise() end end),
	awful.key({modkey}, "k", function() awful.client.focus.bydirection("up") if client.focus then client.focus:raise() end end),

	-- Swap windows and switch between screens
	awful.key({modkey, "Shift"}, "j", function() awful.client.swap.byidx(1) end),
	awful.key({modkey, "Shift"}, "k", function() awful.client.swap.byidx(-1) end),
	awful.key({modkey, "Control"}, "j", function() awful.screen.focus_relative(1) end),
	awful.key({modkey, "Control"}, "k", function() awful.screen.focus_relative(-1) end),

	-- Spawn terminal
	awful.key({modkey}, "Return", function() awful.util.spawn(terminal) end),

	-- Restart awesome
	awful.key({modkey, "Shift"}, "r", awesome.restart),

	-- Increment/Decrement size
	awful.key({modkey}, "i", function() awful.tag.incmwfact(0.05) end),
	awful.key({modkey}, "u", function() awful.tag.incmwfact(-0.05) end),

	--
	awful.key({modkey, "Shift"}, "h", function() awful.tag.incnmaster(1) end),
	awful.key({modkey, "Shift"}, "l", function() awful.tag.incnmaster(-1) end),
	awful.key({modkey, "Control"}, "h", function() awful.tag.incncol(1) end),
	awful.key({modkey, "Control"}, "l", function() awful.tag.incncol(-1) end),

	-- Switch layout
	awful.key({modkey}, "space", function() awful.layout.inc(layouts, 1) end),
	awful.key({modkey, "Shift"}, "space", function() awful.layout.inc(layouts, -1) end),

	-- Prompt
	awful.key({modkey}, "r", function() prompt[mouse.screen]:run() end)
)

clientkeys = awful.util.table.join(
	awful.key({modkey}, "f", function(c) c.fullscreen = not c.fullscreen end),
	awful.key({modkey}, "q", function(c) c:kill() end),
	awful.key({modkey}, "o", awful.client.movetoscreen),
	awful.key({modkey}, "p", function(c) awful.util.spawn(os.getenv("HOME").."/.pwd/pwd.sh") end),
    awful.key({modkey, "Shift"}, "p", function(c) awful.util.spawn(os.getenv("HOME").."/.pwd/pwd.sh a") end),
	awful.key({modkey}, "n", function(c) c.minimized = true end),
	awful.key({modkey}, "m", function (c) c.maximized_horizontal = not c.maximized_horizontal c.maximized_vertical = not c.maximized_vertical end)
)

-- Add tag-specific keys
for i = 1, 9 do
    globalkeys = awful.util.table.join(globalkeys, awful.key({modkey}, "#"..i+9, function() local tag = awful.tag.gettags(mouse.screen)[i] if tag then awful.tag.viewonly(tag) end end),
    awful.key({modkey, "Shift"}, "#"..i+9, function () local tag = awful.tag.gettags(client.focus.screen)[i] if client.focus and tag then awful.client.movetotag(tag) end end))
end

-- Click to focus, click and modkey to move, right click and modkey to resize
clientbuttons = awful.util.table.join(awful.button({}, 1, function(c) client.focus = c; c:raise() end), awful.button({modkey}, 1, awful.mouse.client.move), awful.button({modkey}, 3, awful.mouse.client.resize))

-- Set keys
root.keys(globalkeys)

-- Set rules
awful.rules.rules = {
    {rule = {}, properties = {border_width = beautiful.border_width, border_color = beautiful.border_normal, focus = awful.client.focus.filter, keys = clientkeys, buttons = clientbuttons}},

	-- Application-specific rules
    {rule = {class = "gimp"}, properties = {floating = true}},
	{rule = {class = "URxvt"}, properties = {size_hints_honor = false}}
}

-- Signal function to execute when a new client appears.
client.connect_signal("manage", function(c, startup) c:connect_signal("mouse::enter", function(c) if awful.layout.get(c.screen) ~= awful.layout.suit.magnifier and awful.client.focus.filter(c) then client.focus = c end end) end)

-- uzbl prompt helper
function uzbl_prompt(promptname, text, socket, command)
    if command then command = command.." " else command = "" end
    awful.prompt.run({prompt=promptname, text=text}, prompt[mouse.screen].widget, function(input) awful.util.spawn_with_shell(string.format("echo '%s%s' | socat - unix-connect:%s", command, input, socket)) end)
end
