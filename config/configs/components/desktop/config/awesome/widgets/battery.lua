-- Getting the env
local helpers = require("vicious.helpers")
local beautiful = require("beautiful")
local naughty = require("naughty")
local wibox = require("wibox")

-- The widget
local battery = {}

local warned = false
local state = nil
local percent = nil
local time = nil

battery.icon = wibox.widget.imagebox()
battery.text = wibox.widget.textbox()

function battery.worker(format, warg)
    if not warg then return end

    local battery = helpers.pathtotable("/sys/class/power_supply/"..warg)
    local battery_state = {
        ["Full\n"]        = "Full",
        ["Unknown\n"]     = "Unknown",
        ["Charged\n"]     = "Charged",
        ["Charging\n"]    = "Charging",
        ["Discharging\n"] = "Discharging"
    }

    -- Check if the battery is present
    if battery.present ~= "1\n" then
        return {battery_state["Unknown\n"], 0, "N/A"}
    end


    -- Get state information
    state = battery_state[battery.status] or battery_state["Unknown\n"]

    -- Get capacity information
    if battery.charge_now then
        remaining, capacity = battery.charge_now, battery.charge_full
    elseif battery.energy_now then
        remaining, capacity = battery.energy_now, battery.energy_full
    else
        return {battery_state["Unknown\n"], 0, "N/A"}
    end

    -- Calculate percentage (but work around broken BAT/ACPI implementations)
    percent = math.min(math.floor(remaining / capacity * 100), 100)


    -- Get charge information
    if battery.current_now then
        rate = tonumber(battery.current_now)
    elseif battery.power_now then
        rate = tonumber(battery.power_now)
    else
        return {state, percent, "N/A"}
    end

    -- Calculate remaining (charging or discharging) time
	time = "N/A"

    if rate ~= nil and rate ~= 0 then
        if state == "Charging" then
            timeleft = (tonumber(capacity) - tonumber(remaining)) / tonumber(rate)
        elseif state == "Discharging" then
            timeleft = tonumber(remaining) / tonumber(rate)
        else
            return {state, percent, time}
        end

        -- Calculate time
        local hoursleft   = math.floor(timeleft)
        local minutesleft = math.floor((timeleft - hoursleft) * 60 )

        time = string.format("%02d:%02d", hoursleft, minutesleft)
    end

    return {state, percent, time}
end

function battery.format(widget, args)
    -- Display the time remaining + icon
    if args[1] == "Discharging" or args[1] == "Full" then
        if (args[2] <= 10) then
            -- Warn that the battery is in critical state
            if (not warned) then
                naughty.notify({ preset = naughty.config.presets.critical, text = "Low battery!"})
                warned = false
            end
            battery.icon:set_image(beautiful.battery_critical)
            return "<span color=\"#e52222\">"..args[3].."</span>"
        end

        if (args[2] > 10 and args[2] <= 30) then
            battery.icon:set_image(beautiful.battery_low)
            return "<span color=\"#e52222\">"..args[3].."</span>";
        end

        if (args[2] > 30 and args[2] <= 50) then
            battery.icon:set_image(beautiful.battery_medium)
            return "<span color=\"#fc951e\">"..args[3].."</span>";
        end

        if (args[2] > 50) then
            battery.icon:set_image(beautiful.battery_high)
            return "<span color=\"#a6e32d\">"..args[3].."</span>";
        end
    elseif args[1] == "Charging" then
         battery.icon:set_image(beautiful.battery_plugged)
        return "<span color=\"#1e80cc\">"..args[2].."</span>";
    elseif args[1] == "Charged" then
        battery.icon:set_image(beautiful.battery_high)
        return ""
    elseif args[1] == "Unknown" then
        battery.icon:set_image(beautiful.battery_unknown)
        return "<span color=\"#f2f2f2\">--</span>"
    else
        return ""
    end
end

return battery
