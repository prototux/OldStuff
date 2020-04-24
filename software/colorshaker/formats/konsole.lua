local format = {}

format.name = "Konsole config"
format.extension = ".colorscheme"

function format.color(color, utils)
	return color[1]..","..color[2]..","..color[3]
end

return format
