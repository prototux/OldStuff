import SimpleHTTPServer
import SocketServer
import serial
import sys
import cgi
import time
from xml.dom.minidom import parseString

#Serial port init
try:
    ser = serial.Serial(port='/dev/ttyACM0', baudrate=9600,)
    ser.close()
    ser.open()
    ser.isOpen()
except:
    print "Failed to connect to arduino"
    sys.exit()

#Network config
PORT_NUMBER = 8037

class MyHandler(SimpleHTTPServer.SimpleHTTPRequestHandler):
    def do_GET(s):
        SimpleHTTPServer.SimpleHTTPRequestHandler.do_GET(s)
    def do_POST(s):
		#Parse the form data posted
		form = cgi.FieldStorage(
			fp=s.rfile,
			headers=s.headers,
			environ={'REQUEST_METHOD':'POST'}
		)
		data = form['data'].value
		try:
			dom = parseString(data)
		except:
			print "Cannot parse data: %s" % form['data'].value

		#manage lights and sleep 100ms to wait return from serial

		content = "<?xml version=\"1.0\" ?>"
		content += "<sayu version=\"0.1\">"
		content += "<result>OK</result>>"
		if (dom.getElementsByTagName('light')):
			content += s.manageLights(dom.getElementsByTagName('light'))
		else:
			content += s.manageCoffee(dom.getElementsByTagName('coffee'))
		content += "</sayu>"

		s.send_response(200)
		s.send_header("Content-Type", "text/xml")
		s.send_header("Content-Length", len(content))
		s.send_header("Access-Control-Allow-Headers", "*")
		s.end_headers()
		s.wfile.write(content)
    def manageLights(s, lights):
        xmlResult = ""
        serReady = 0
        for light in lights:
            command = 'R'
            lightID = light.getAttribute('id')
            lightID = lightID.encode('ascii','ignore')
            command += "ABCDEFGHIJKLMNOPQRSTUVWXYZ"[int(lightID)]
            lightStatus = light.firstChild.data
            print "Appel lumiere: ", int(lightID),":",lightStatus
            if (lightStatus == "ON"):
                command += 'O'
            else:
                command += 'F'
            ser.write(command)
            resultToParse = ser.readline()
            resultParsed = s.parseResult(resultToParse)
            relayID = "R"+str(lightID)
            xmlResult += "<lightstatus id=\""+str(lightID)+"\">"
            xmlResult += str(resultParsed[relayID])
            xmlResult += "</lightstatus>"
        return xmlResult
    def manageCoffee(s, cups):
    	print "Appel cafe %s tasses" % cups[0].firstChild.data
    	cupsQty = cups[0].firstChild.data
        ser.write("REO")
    	time.sleep(3+1.85*int(cupsQty))
        resultToParse = ser.readline()
    	print "Cafe fini!", resultToParse
        ser.write("REF")
    	return "";
    def parseResult(s, strToParse):
		returnArray = {"R0":"0"}
		if (strToParse.find(":") != -1):
			result = strToParse.split(":")
			try:
				returnArray[str(result[1])] = str(result[2])
			except:
				print "Error reading the arduilol!"
		return returnArray

class MyServer(SocketServer.TCPServer):
    allow_reuse_address = True

if __name__ == '__main__':
    httpd = MyServer(("", PORT_NUMBER), MyHandler)
    print "Server Starts - %s" % (PORT_NUMBER)
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
		httpd.server_close()
		httpd.socket.close()
		print "Server Stops - %s" % (PORT_NUMBER)