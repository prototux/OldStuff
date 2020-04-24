local clrmame = {}

function clrmame.detect(raw)
	if raw:match("game %((.-)\n%)") then
		return true
	end
	return false
end

-- Parse clrmame dat file
function clrmame.parse(raw)
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


return clrmame
