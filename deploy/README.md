# Quickstart
- install [Vagrant](https://www.vagrantup.com/downloads.html)
- install [Ansible](https://docs.ansible.com/ansible/intro_installation.html)
- install [VirtualBox](https://www.virtualbox.org/wiki/Linux_Downloads)
- run ./setup.sh
- wait a few minutes for setup to complete
- if all goes well http://localhost:1080 should point to a working deployment.
- login with username: *admin@localhost* and password *admin*
- `vagrant provision` to re-run the ansible setup.

If you screw something up just `vagrant destroy jessie` and `vagrant up`.
