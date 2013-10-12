#!/bin/bash

if [ ! -d .git ]; then 
	echo 'Run this script from the root of the repository';
	exit 1
fi

if [ ! -f build/git/post-commit ]; then 
	echo 'post-commit not found, exiting';
	exit 1
fi

echo 'Creating hooks directory'
mkdir -p .git/hooks

echo 'Symlinking post-commit'
ln -s ../../build/git/post-commit .git/hooks/post-commit

