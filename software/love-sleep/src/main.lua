local fd = nil
local data = {}
local x = 0
local offset = 1 

local play = false

local dataXZoom = 1
local dataYZoom = 1

local totalX = 0
local totalY = 0
local totalZ = 0

local meanX = 0
local meanY = 0
local meanZ = 0

local minX = 0
local minY = 0
local minZ = 0

local maxX = 0
local maxY = 0
local maxZ = 0

local middleX = 0
local middleY = 0
local middleZ = 0

local baselineX = 0
local baselineY = 0
local baselineZ = 0

function love.load()
	love.window.setMode(1280, 1000, {})
	fd = io.open("/mnt/DATALOG.TXT","r")
	str = fd:read("*line")
	while str do
		local elems = {}
		str:gsub("%d+", function(i) table.insert(elems, i) end)
		table.insert(data, elems)

		elems[1] = tonumber(elems[1])
		elems[2] = tonumber(elems[2])
		elems[3] = tonumber(elems[3])

		totalX = totalX + elems[1]
		totalY = totalY + elems[2]
		totalZ = totalZ + elems[3]
		
		if minX == 0 or elems[1] < minX then minX = elems[1] end
		if minY == 0 or elems[2] < minY then minY = elems[2] end
		if minZ == 0 or elems[3] < minZ then minZ = elems[3] end

        if elems[1] > maxX then maxX = elems[1] end
        if elems[2] > maxY then maxY = elems[2] end
        if elems[3] > maxZ then maxZ = elems[3] end

		str = fd:read("*line")
	end
	meanX = totalX/#data
	meanY = totalY/#data
	meanZ = totalY/#data

	middleX = (maxX-minX)/2
	middleY = (maxY-minY)/2
	middleZ = (maxZ-minZ)/2

	print("X => min: "..minX.." max: "..maxX.." mean: "..meanX)
	print("Y => min: "..minY.." max: "..maxY.." mean: "..meanY)
	print("Z => min: "..minZ.." max: "..maxZ.." mean: "..meanZ)
end

function love.update(dt)
	if love.keyboard.isDown("a") then
		if offset-100/dataXZoom > 1 then
			offset = offset - 100/dataXZoom
		else
			offset = 1
		end
    end

    if love.keyboard.isDown("d") then
		if offset+1280/dataXZoom+100/dataXZoom < #data then
			offset = offset + 100/dataXZoom
		else
			offset = #data-1280
		end
    end

    if love.keyboard.isDown("q") then
        if offset > 1 then
            offset = offset - 1
        end
    end

    if love.keyboard.isDown("e") then
        if offset+1280 < #data then
            offset = offset + 1
        end
    end

    if love.keyboard.isDown("z") then
        if offset-1000 > 1 then
            offset = offset - 1000
		else
			offset = 1
        end
    end

    if love.keyboard.isDown("c") then
        if offset+1280 < #data then
			if offset+1280+1000 > #data then
				offset = #data-1280-1
			else
				offset = offset + 1000
			end
        end
    end

	if love.keyboard.isDown("x") then
		offset = #data - 1280
	end

	if love.keyboard.isDown("p") then
		dataXZoom = dataXZoom + 1
	end

	if love.keyboard.isDown("m") then
		if dataXZoom > 1 then
			dataXZoom = dataXZoom - 1
		end
	end

	if love.keyboard.isDown(" ") then
		if play == true then
			play = false
		else
			play = true
		end
	end

    if love.keyboard.isDown("escape") then
        love.event.quit()
    end

	if play then
		offset = offset + 100
	end

	baselineX, baselineY, baselineZ = getBaselines()
end

function getYX(baseY)
	return  500+(baseY-(minX+middleX))*dataYZoom
end

function getYY(baseY)
    return  500+(baseY-(minY+middleY))*dataYZoom
end

function getYZ(baseY)
    return  500+(baseY-(minZ+middleZ))*dataYZoom
end

function getTime(milliseconds)
	local totalseconds = math.floor(milliseconds / 1000)
	milliseconds = milliseconds % 1000
	local seconds = totalseconds % 60
	local minutes = math.floor(totalseconds / 60)
	local hours = math.floor(minutes / 60)
	minutes = minutes % 60
	return hours, minutes, seconds, milliseconds
end

function getBaseline(index)
	local values = {}
    local min = 0
    local max = 0
    for x = 0, 1280 do
        if values[data[offset+x][index]] == nil then
            values[data[offset+x][index]] = 1
        else
            values[data[offset+x][index]] = values[data[offset+x][index]] + 1
        end
        if min == 0 or data[offset+x][index] < min then min = data[offset+x][index] end
        if data[offset+x][index] > max then max = data[offset+x][index] end
    end

    local baselines = {}
    for i = min, max do
        if values[i] ~= nil and values[i] > 150 then
            table.insert(baselines, i)
        end
    end

    local total = 0
    for i = 1, #baselines do
        total = total + baselines[i]
    end
    return math.floor(total/#baselines)
end

function getBaselines()
	return getBaseline(1), getBaseline(2), getBaseline(3)
end

function getMovements()

end

function love.draw()
	local i = 1

	love.graphics.setColor(255, 0, 0)
	love.graphics.print("X Axis", 10, 20)
	love.graphics.setColor(0, 255, 0)
	love.graphics.print("Y Axis", 10, 40)
	love.graphics.setColor(0, 0, 255)
	love.graphics.print("Z Axis", 10, 60)
	love.graphics.setColor(255, 255, 255)
	love.graphics.print("Offset: "..offset.."/"..#data, 10, 100)
	love.graphics.print("X Zoom: "..dataXZoom.."\nY zoom: "..dataYZoom, 10, 120)
	love.graphics.print("Data=> X="..data[offset+(1280/2)][1]..", Y="..data[offset+(1280/2)][2].." Z="..data[offset+(1280/2)][3], 10, 150)

	local hours,minutes,seconds,milliseconds = getTime((offset+(1280/2))*100)
	love.graphics.print(string.format("%02d:%02d:%02d:%03d", hours, minutes, seconds, milliseconds), 10, 170)

	if play then love.graphics.print("PLAY", 10, 170) end

	love.graphics.line(1280/2, 300, 1280/2, 700)

	local triggerValue = 5

	-- Display the graph
	for x = 2, 1280, dataXZoom do
		-- Display the movement zones
		love.graphics.setColor(200, 200, 50)
        if (data[offset+i][1] > baselineX+triggerValue or data[offset+i][1] < baselineX-triggerValue) or (data[offset+i][2] > baselineY+triggerValue or data[offset+i][2] < baselineY-triggerValue) or (data[offset+i][3] > baselineZ+triggerValue or data[offset+i][3] < baselineZ-triggerValue) then
			love.graphics.rectangle("fill", x-dataXZoom, 0, dataXZoom, 1000)
        end

		-- Display timescale
        love.graphics.setColor(200, 200, 200)
        local hours,minutes,seconds,milliseconds = getTime((offset+i)*100)
        if seconds == 0 and milliseconds == 0 then
            love.graphics.line(x, 0, x, 980)
            love.graphics.print(string.format("%02d:%02d", hours, minutes), x-18, 985)
        end

		love.graphics.setColor(255, 0, 0)
		love.graphics.line(x-dataXZoom, getYX(data[offset+i-1][1]), x, getYX(data[offset+i][1]))
        love.graphics.setColor(0, 255, 0)
		love.graphics.line(x-dataXZoom, getYY(data[offset+i-1][2]), x, getYY(data[offset+i][2]))
        love.graphics.setColor(0, 0, 255)
		love.graphics.line(x-dataXZoom, getYZ(data[offset+i-1][3]), x, getYZ(data[offset+i][3]))

		i = i + 1
	end
end
