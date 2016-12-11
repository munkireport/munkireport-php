#!/usr/bin/python

import os
import json
import subprocess

def curl(url):
    p1 = subprocess.Popen(['curl', '--silent', url], stdout=subprocess.PIPE)
    return p1.communicate()[0]

bootstrap_base_url='https://raw.githubusercontent.com/twbs/bootstrap/master/dist/'
bootswatch_url='https://bootswatch.com/api/3.json'
basedir = os.path.dirname(os.path.dirname(os.path.realpath(__file__)))

print 'Getting bootstrap files'
css = curl(bootstrap_base_url + 'css/bootstrap.min.css')
with open(os.path.join(basedir, 'assets', 'css', 'bootstrap.min.css'),"w+") as f:
    f.write(css)
js = curl(bootstrap_base_url + 'js/bootstrap.min.js')
with open(os.path.join(basedir, 'assets', 'js', 'bootstrap.min.js'),"w+") as f:
    f.write(js)

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
