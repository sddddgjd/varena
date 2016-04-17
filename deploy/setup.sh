#!/bin/bash

vagrant plugin install vagrant-vbguest
ansible-galaxy -p roles -r requirements.yml install
vagrant up
