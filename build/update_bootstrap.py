#!/usr/local/munkireport/munkireport-python2

import os
import json
import subprocess
import re

def curl(url):
    p1 = subprocess.Popen(['curl', '--silent', url], stdout=subprocess.PIPE)
    return p1.communicate()[0]

def find_body_color(css_data):
    urls = re.findall(r'body.*{[^}]+\scolor:\s?(.+);', css_data)
    return urls[0]
    
def find_background_color(css_data):
    urls = re.findall(r'body.*{[^}]+\sbackground-color:\s?(.+);', css_data)
    return urls[0]

def write_override(fileName, bodyColor, backgroundColor):
    with open(fileName, "w+") as f:
        f.write("text, svg .nvd3.nv-pie .nv-pie-title, .nvd3 .nv-discretebar .nv-groups text, .nvd3 .nv-multibarHorizontal .nv-groups text{\n")
        f.write("   fill: %s;\n" % bodyColor)
        f.write("}\n")
        f.write(".nvd3 .nv-axis path{stroke: %s}\n" % bodyColor)
        f.write(".nvd3.nv-pie path{stroke: %s}\n" % backgroundColor)
        f.write(".nvtooltip, .nvtooltip table{\n")
        f.write("   color: %s;\n" % bodyColor)
        f.write("   background-color: %s;\n" % backgroundColor)
        f.write("}\n")


bootstrap_base_url='https://raw.githubusercontent.com/twbs/bootstrap/master/dist/'
bootswatch_url='https://bootswatch.com/api/3.json'
basedir = os.path.dirname(os.path.dirname(os.path.realpath(__file__)))

print 'Getting bootstrap files'
css = curl(bootstrap_base_url + 'css/bootstrap.min.css')
with open(os.path.join(basedir, 'assets', 'themes', 'Default', 'bootstrap.min.css'),"w+") as f:
    f.write(css)
js = curl(bootstrap_base_url + 'js/bootstrap.min.js')
with open(os.path.join(basedir, 'assets', 'js', 'bootstrap.min.js'),"w+") as f:
    f.write(js)
fileName = os.path.join(basedir, 'assets', 'themes', 'Default', 'nvd3.override.css')
write_override(fileName, '#333', '#fff')

print 'Getting themes'
jsondata = curl(bootswatch_url)
data = json.loads(jsondata)

for item in data['themes']:
    item_dir = os.path.join(basedir, 'assets', 'themes', item['name'])
    if not os.path.isdir(item_dir):
        os.mkdir(item_dir)
    
    # Get css
    print 'Updating %s' % item['name']
    css_data = curl(item['cssMin'])
    
    with open(os.path.join(item_dir, 'bootstrap.min.css'),"w+") as f:
        f.write(css_data)

print 'Creating nvd3 styles'
for item in data['themes']:
    item_dir = os.path.join(basedir, 'assets', 'themes', item['name'])
    if not os.path.isdir(item_dir):
        os.mkdir(item_dir)
    
    # Get css
    print 'Updating %s' % item['name']
    css_data = curl(item['css'])
    
    bodyColor = find_body_color(css_data)
    backgroundColor = find_background_color(css_data)

    print 'color: %s; background-color: %s' % (bodyColor, backgroundColor)
    fileName = os.path.join(item_dir, 'nvd3.override.css')
    write_override(fileName, bodyColor, backgroundColor)
