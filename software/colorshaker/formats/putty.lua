local format = {}

format.name = "PuTTY registry file"
format.extension = ".reg"

function format.color(color, utils)
	return color[1]..","..color[2]..","..color[3]
end

return format
