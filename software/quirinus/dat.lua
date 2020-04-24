-- Datfiles detection and parsing tool
local dat = {}

local formats = {}

function dat.init()
	local files = {}
	for filename in lfs.dir("./formats") do
		if not filename:match("^(%.).*") then
			local module = filename:match("(.*)%..*$")
			formats[module] = require("formats/"..module)
			print("Found and added module "..module)
		end
	end
end

function dat.detectFormat(raw)
	for name, module in pairs(formats) do
		if module.detect(raw) then
			print("Detected format "..name)
		end
	end
end

return dat
