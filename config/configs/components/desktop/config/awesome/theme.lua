theme = {}

theme_dir = os.getenv("HOME").."/.config/awesome"

-- Fonts
theme.font = "Source Code Pro 10"
theme.taglist_font = "FontAwesome 12"

-- Colors
theme.bg_normal = "#222222"
theme.bg_focus = "#1E2320"
theme.bg_urgent = "#3F3F3F"
theme.bg_systray = theme.bg_normal

theme.fg_normal = "#AAAAAA"
theme.fg_focus = "#0099CC"
theme.fg_urgent = "#3F3F3F"

-- Borders
theme.border_width = 1
theme.border_normal = "#000000"
theme.border_focus = "#535d6c"
theme.border_marked = "#91231c"

-- Set the taglist indicators to nil
theme.taglist_squares_sel = nil
theme.taglist_squares_unsel = nil

-- These may be replaced in the future...
theme.titlebar_close_button_normal = "/usr/share/awesome/themes/default/titlebar/close_normal.png"
theme.titlebar_close_button_focus = "/usr/share/awesome/themes/default/titlebar/close_focus.png"

theme.titlebar_ontop_button_normal_inactive = "/usr/share/awesome/themes/default/titlebar/ontop_normal_inactive.png"
theme.titlebar_ontop_button_focus_inactive = "/usr/share/awesome/themes/default/titlebar/ontop_focus_inactive.png"
theme.titlebar_ontop_button_normal_active = "/usr/share/awesome/themes/default/titlebar/ontop_normal_active.png"
theme.titlebar_ontop_button_focus_active = "/usr/share/awesome/themes/default/titlebar/ontop_focus_active.png"

theme.titlebar_sticky_button_normal_inactive = "/usr/share/awesome/themes/default/titlebar/sticky_normal_inactive.png"
theme.titlebar_sticky_button_focus_inactive = "/usr/share/awesome/themes/default/titlebar/sticky_focus_inactive.png"
theme.titlebar_sticky_button_normal_active = "/usr/share/awesome/themes/default/titlebar/sticky_normal_active.png"
theme.titlebar_sticky_button_focus_active = "/usr/share/awesome/themes/default/titlebar/sticky_focus_active.png"

theme.titlebar_floating_button_normal_inactive = "/usr/share/awesome/themes/default/titlebar/floating_normal_inactive.png"
theme.titlebar_floating_button_focus_inactive = "/usr/share/awesome/themes/default/titlebar/floating_focus_inactive.png"
theme.titlebar_floating_button_normal_active = "/usr/share/awesome/themes/default/titlebar/floating_normal_active.png"
theme.titlebar_floating_button_focus_active = "/usr/share/awesome/themes/default/titlebar/floating_focus_active.png"

theme.titlebar_maximized_button_normal_inactive = "/usr/share/awesome/themes/default/titlebar/maximized_normal_inactive.png"
theme.titlebar_maximized_button_focus_inactive = "/usr/share/awesome/themes/default/titlebar/maximized_focus_inactive.png"
theme.titlebar_maximized_button_normal_active = "/usr/share/awesome/themes/default/titlebar/maximized_normal_active.png"
theme.titlebar_maximized_button_focus_active = "/usr/share/awesome/themes/default/titlebar/maximized_focus_active.png"

-- Set layout icons:
theme.layout_floating = theme_dir.."/icons/layouts/floating.png"
theme.layout_tilebottom = theme_dir.."/icons/layouts/tilebottom.png"
theme.layout_tileleft  = theme_dir.."/icons/layouts/tileleft.png"
theme.layout_tile = theme_dir.."/icons/layouts/tile.png"
theme.layout_tiletop = theme_dir.."/icons/layouts/tiletop.png"
theme.layout_max = theme_dir.."/icons/layouts/maximized.png"
theme.layout_fullscreen = theme_dir.."/icons/layouts/fullscreen.png"

-- Network status icon 
theme.net_wifi_high = theme_dir.."/icons/network/wifi_high.png"
theme.net_wifi_medium = theme_dir.."/icons/network/wifi_medium.png"
theme.net_wifi_low = theme_dir.."/icons/network/wifi_none.png"
theme.net_none = theme_dir.."/icons/network/none.png"

-- Battery icon (more to be added)
theme.battery_plugged = theme_dir.."/icons/battery/plugged.png"
theme.battery_high = theme_dir.."/icons/battery/high.png"
theme.battery_medium = theme_dir.."/icons/battery/medium.png"
theme.battery_low = theme_dir.."/icons/battery/low.png"
theme.battery_critical = theme_dir.."/icons/battery/critical.png"
theme.battery_unknown = theme_dir.."/icons/battery/unknown.png"


-- Volume icons
theme.volume_mute = theme_dir.."/icons/volume/mute.png"
theme.volume_high = theme_dir.."/icons/volume/high.png"
theme.volume_medium = theme_dir.."/icons/volume/medium.png"
theme.volume_low = theme_dir.."/icons/volume/low.png"

return theme
