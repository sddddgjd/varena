#!/bin/bash
#
# Configuration script to be run when a new client is first cloned

# Create a copy of the config file unless it already exists
if [ ! -e varena2.conf ]
then
  cp varena2.conf.sample varena2.conf
  echo "*** Please remember to edit varena2.conf according to your needs"
fi

# Create a copy of .htaccess unless it already exists
if [ ! -e www/.htaccess ]
then
  cp www/.htaccess.sample www/.htaccess
  echo "*** Please remember to edit www/.htaccess according to your needs"
fi

# Make some directories world-writable
chmod 777 templates_c
chmod 777 uploads/attachments
