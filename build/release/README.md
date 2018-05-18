# Release instructions

- Update CHANGELOG.md
- push to GH
- merge wip into master
- push to GH
- run release script

```
build/release/make_munkireport_release.py --next-version=3.0.2 --token=SECRETTOKEN --user-repo=munkireport/munkireport-php
```