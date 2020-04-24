local format = {}

format.name = "Mintty config file"
format.extension = ".minttyrc"

function format.color(color, utils)
	return color[1]..","..color[2]..","..color[3]
end

return format
