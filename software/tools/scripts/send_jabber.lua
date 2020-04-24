#!/usr/bin/env lua
require "verse".init("client");
c = verse.new();

-- ID and password...
local jid, password = "your@jabber.id", "YourP4$$WoRD"
local recipient = arg[2] ~= nil and arg[1] or jid
local message = arg[2] or arg[1]

-- We need at least one argument
if not message then
    error("ENOMESSAGE")
end

-- Hooks
c:hook("authentication-failure", function(err) error("ELOGIN: "..tostring(err.condition)) end)
c:hook("disconnected", function() os.exit() end)
c:hook("ready", function()
    c:send(verse.message({to = recipient, type = "chat"}):body(message))
    c:close()
end)

-- Connect
c:connect_client(jid, password)
verse.loop()
