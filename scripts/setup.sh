#!/bin/bash
#
# Configuration script to be run when a new client is first cloned

# for OS X compatibility, do not use readlink
cd `dirname $0`
CWD=`pwd`
ROOT_DIR=`dirname $CWD`
cd $ROOT_DIR
echo "The root of your client appears to be $ROOT_DIR"

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

# Symlink hooks unless they already exist
if [ ! -e .git/hooks/pre-commit ]; then
  echo "* symlinking scripts/git-hooks/pre-commit.php as .git/hooks/pre-commit"
  ln -s $ROOT_DIR/scripts/git-hooks/pre-commit.php .git/hooks/pre-commit
else
  echo "* .git/hooks/pre-commit already exists, skipping"
fi

if [ ! -e .git/hooks/post-merge ]; then
  echo "* symlinking scripts/git-hooks/post-merge.sh as .git/hooks/post-merge"
  ln -s $ROOT_DIR/scripts/git-hooks/post-merge.sh .git/hooks/post-merge
else
  echo "* .git/hooks/post-merge already exists, skipping"
fi

# Make some directories world-writable
chmod 777 templates_c
chmod 777 uploads/attachments
