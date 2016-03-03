#!/bin/bash
#
# Configuration script to be run when a new client is first cloned

# Create a copy of the config file unless it already exists
if [ ! -e varena2.conf ]
then
  cp varena2.conf.sample varena2.conf
  echo "*** Please remember to edit varena2.conf according to your needs"
fi

# Make some directories world-writable
chmod 777 templates_c
