#!/usr/bin/python

import os
import subprocess
import sys
import socket, struct

network_service_list = []

def bashCommand(script):
    try:
        return subprocess.check_output(script)
    except (subprocess.CalledProcessError, OSError), err:
        return "[* Error] **%s** [%s]" % (err, str(script))

def get_network_info():
    networkservices = bashCommand(['networksetup', '-listallnetworkservices']).split('\n')[:-1]
    for network in networkservices:
        if "asterisk" in network:
            pass
        else:
            network_service_list.append('Service: %s' % network)
            network_info = bashCommand(['networksetup', '-getinfo', network]).split('\n')[:-1]
            for info in network_info:
                network_service_list.append(info)


def get_tunnel_info():
    ## Things to do:
    ## 1: Make the parser smarter so in case things aren't where we expect, we don't ship bad info
    ## 2: Allow the function to loop over all 'utun' addresses if there are multiple
    
    tunnel_ip = bashCommand(['ifconfig', 'utun1'])
    tunnel_ip = tunnel_ip.split()

    ## get address info from parsed output
    def netmask_to_cidr(netmask):
        return sum([bin(int(x)).count('1') for x in netmask.split('.')])

    def get_network_addr(ip, cidr):
        ip = ip.split('.')
        cidr = int(cidr)

        mask = [0, 0, 0, 0]
        for i in range(cidr):
            mask[i/8] = mask[i/8] + (1 << (7 - i % 8))

        net = []
        for i in range(4):
            net.append(int(ip[i]) & mask[i])

        broad = list(net)
        brange = 32 - cidr
        for i in range(brange):
            broad[3 - i/8] = broad[3 - i/8] + (1 << (i % 8))

        return ".".join(map(str, net))

    ipv4_address = tunnel_ip[5]
    subnet_mask = socket.inet_ntoa(struct.pack('!L', int(tunnel_ip[9], 16)))
    cidr = netmask_to_cidr(subnet_mask)
    router_address = get_network_addr(ipv4_address, cidr)

    if not 'fe80' in tunnel_ip[11]:
        ipv6_address = tunnel_ip[11]
    else:
        ipv6_address = "none"

    network_service_list.append('Service: Tunnel Interface')
    network_service_list.append('DHCP Configuration')
    network_service_list.append('IP address: %s' % str(ipv4_address))
    network_service_list.append('Subnet mask: %s' % str(subnet_mask))
    network_service_list.append('Router: %s' % str(router_address))
    network_service_list.append('Ethernet Address: 00:00:00:00:00:00')
    network_service_list.append('Client ID:')
    network_service_list.append('IPv6: Automatic')
    network_service_list.append('IPv6 IP address: %s' % str(ipv6_address))
    network_service_list.append('IPv6 Router: none')

def main():
    '''Main'''
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)

    # Get network adapter info
    get_network_info()

    # Get VPN/Tunnel info
    # Need to add checks to this function to ensure we actually want to call it
    # otherwise, we might have a parsing error
    get_tunnel_info()

    # Write network results to cache
    network_file = open(os.path.join(cachedir, 'networkinfo.txt'), 'w+')
    for network in network_service_list:
        network_file.write(network)
        network_file.write('\n')
    network_file.close()

if __name__ == "__main__":
    main()
