-- Sets file management
local sets = {}

-- Contains all sets data
local data = {}

-- Parse clrmame dat file
local function parse(raw)
	local games = {}
	for game in raw:gmatch("game %((.-)\n%)") do
		local data = {}
		data.name = game:match("name \"(.-)\"")
		data.description = game:match("description \"(.-)\"")
		data.roms = {}
		for name, size, crc, md5, sha1 in game:gmatch("rom %( name \"(.-)\" size (.-) crc (.-) md5 (.-) sha1 (.-) %)") do
			local rom = {}
			rom.name = name
			rom.size = size
			rom.crc = crc:lower()
			rom.md5 = md5:lower()
			rom.sha1 = sha1:lower()
			table.insert(data.roms, rom)
		end
		table.insert(games, data)
	end
	return games
end

-- Add a set to the list, and parse it
function sets.add(name, file)
	local set = {}
	set.name = name
	set.filepath = file
	local f = io.open(file, "r")
	if f then
		local raw = f:read("*all")
		f:close()
		set.games = parse(raw)
	else
		print("File "..file.." is empty or doesn't exist")
	end
	table.insert(data, set)
end

function sets.getAll()
	return data
end

function sets.get(name)
	for k, v in pairs(data) do
		if v.name == name then
			return v
		end
	end
end

return sets
