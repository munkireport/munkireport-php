#!/usr/local/munkireport/munkireport-python2
#
# Script to run the munkireport-php GitHub release workflow as outlined here:
#https://github.com/autopkg/autopkg/wiki/Packaging-AutoPkg-For-Release-on-GitHub
#
# This includes tagging and setting appropriate release notes for the release,
# uploading the actual built package, and incrementing the version number for
# the next version to be released.
#
# Requires an OAuth token with push access to the repo. Currently the GitHub
# Releases API is in a 'preview' status, and this script does very little error
# handling.
"""See docstring for main() function."""

import json
import optparse
import os
import plistlib
import re
import subprocess
import sys
import tempfile
import urllib2

from distutils.version import LooseVersion
from pprint import pprint
from shutil import rmtree
from time import strftime

class GitHubAPIError(BaseException):
    """Base error for GitHub API interactions."""
    pass


def api_call(endpoint, token, baseurl='https://api.github.com', data=None,
             json_data=True, additional_headers=None):
    '''endpoint: of the form '/repos/username/repo/etc'.
    token: the API token for Authorization.
    baseurl: the base URL for the API endpoint. for asset uploads this ends up
             needing to be overridden.
    data: takes a standard python object and serializes to json for a POST,
          unless json_data is False.
    additional_headers: a dict of additional headers for the API call'''
    if data and json_data:
        data = json.dumps(data, ensure_ascii=False)
    headers = {'Accept': 'application/vnd.github.v3+json',
               'Authorization': 'token %s' % token}
    if additional_headers:
        for header, value in additional_headers.items():
            headers[header] = value

    req = urllib2.Request(baseurl + endpoint, headers=headers)
    try:
        results = urllib2.urlopen(req, data=data)
    except urllib2.HTTPError as err:
        print >> sys.stderr, "HTTP error making API call!"
        print >> sys.stderr, err
        error_json = err.read()
        error = json.loads(error_json)
        print >> sys.stderr, "API message: %s" % error['message']
        sys.exit(1)
    if results:
        try:
            parsed = json.loads(results.read())
            return parsed
        except BaseException as err:
            print >> sys.stderr, err
            raise GitHubAPIError
    return None

def get_version_file_path():
    return 'app/helpers/site_helper.php'

def get_commit_count():
    return int(get_version(True))

def get_version(commit_count=False):
    try:
        helper = get_version_file_path()
        if commit_count:
            pattern = re.compile("\$GLOBALS\['version'\] = '.+\.(\d+)'")
        else:
            pattern = re.compile("\$GLOBALS\['version'\] = '(.+)\.\d+'")
        for i, line in enumerate(open(helper)):
            for match in re.finditer(pattern, line):
                return '%s' % match.groups()
    except BaseException:
        sys.exit("Couldn't determine current munkireport-php version!")

def clean_version(version):
    return re.sub("[^0-9\.].*", "", version)

def set_version(version):
    helper = get_version_file_path()
    search = "(\$GLOBALS\['version'\] = ')[^']+"
    replace = r'\g<1>%s' % version
    with open(helper, 'r+') as f:
        content = re.sub(search, replace, f.read())
        f.seek(0)
        f.truncate()
        f.write(content)

def get_version_from_string(str):
    return str.split(" ", 1)[0]

def run_command(cmd):
    print ' '.join(cmd)
    subprocess.check_call(cmd)

def main():
    """Builds and pushes a new munkireport-php release from an existing Git
    clone of munkireport-php.

    Requirements:

    API token:
    You'll need an API OAuth token with push access to the repo. You can create a
    Personal Access Token in your user's Account Settings:
    https://github.com/settings/applications
    """
    usage = __doc__
    parser = optparse.OptionParser(usage=usage)
    parser.add_option('-t', '--token',
                      help="GitHub API OAuth token. Required.")
    parser.add_option('-v', '--next-version',
                      help=("Next version to which munkireport-php will be "
                            "incremented. Required."))
    parser.add_option('-p', '--prerelease',
                      help=("Mark this release as a pre-release, applying "
                            "a given suffix to the tag, i.e. 'RC1'"))
    parser.add_option('--dry-run', action='store_true',
                      help=("Don't actually push any changes to "
                            "Git remotes, and skip the actual release "
                            "creation. Useful for testing changes "
                            "to this script. Any GitHub API calls made "
                            "are read-only."))
    parser.add_option('--user-repo', default='munkireport/munkireport-php',
                      help=("Alternate org/user and repo to use for "
                            "the release, useful for testing. Defaults to "
                            "'munkireport/munkireport-php'."))
    parser.add_option('--release-icon', default='',
                    help=("Add optional release emoji."))
    opts = parser.parse_args()[0]
    if not opts.next_version:
        sys.exit("Option --next-version is required!")
    if not opts.token:
        sys.exit("Option --token is required!")
    next_version = opts.next_version
    if opts.dry_run:
        print "Running in 'dry-run' mode.."
    publish_user, publish_repo = opts.user_repo.split('/')
    token = opts.token
    release_icon = opts.release_icon

    # ensure our OAuth token works before we go any further
    api_call('/users/%s' % publish_user, token)

    # set up some paths and important variables
    munkireport_root = tempfile.mkdtemp('', 'mr_release_')
    changelog_path = os.path.join(munkireport_root, 'CHANGELOG.md')

    # clone Git master
    run_command(
        ['git', 'clone', #'--depth',  '1',
         'https://github.com/%s/%s' % (publish_user, publish_repo),
         munkireport_root])
    os.chdir(munkireport_root)
    
    branch = 'wip'
    
    run_command(['git', 'checkout', branch])
        
    # get the current munkireport-php version
    current_version = get_version()
    tag_name = 'v%s' % current_version
    if opts.prerelease:
        tag_name += opts.prerelease
        current_version += opts.prerelease

    # print "Current munkireport-php version: %s" % current_version
    # if LooseVersion(next_version) < LooseVersion(current_version):
    #     sys.exit(
    #         "Next version (gave %s) must be greater than current version %s!"
    #         % (next_version, current_version))

    published_releases = api_call(
        '/repos/%s/%s/releases' % (publish_user, publish_repo), token)
    for rel in published_releases:
        if rel['tag_name'] == tag_name:
            print >> sys.stderr, (
                "There's already a published release on GitHub with the tag "
                "{0}. It should first be manually removed. "
                "Release data printed below:".format(tag_name))
            pprint(rel, stream=sys.stderr)
            sys.exit()

    # write today's date in the changelog
    with open(changelog_path, 'r') as fdesc:
        changelog = fdesc.read()
    release_date = strftime('(%B %d, %Y)')
    new_changelog = re.sub(r'### \[.+\]', '### [%s]' % current_version, changelog, 1)
    new_changelog = re.sub(r'(\(Unreleased\))', r'\1 %s' % release_icon, new_changelog)
    new_changelog = re.sub(r'\(Unreleased\)', release_date, new_changelog)
    new_changelog = re.sub('...HEAD', '...v%s' % current_version, new_changelog)
    with open(changelog_path, 'w') as fdesc:
        fdesc.write(new_changelog)

    # commit and push the new release
    run_command(['git', 'add', changelog_path])
    run_command(
        ['git', 'commit', '-m', 'Release version %s.' % current_version])
    run_command(['git', 'tag', tag_name])
    if not opts.dry_run:
        run_command(['git', 'push', 'origin', branch])
        run_command(['git', 'push', '--tags', 'origin', branch])

    # extract release notes for this new version
    notes_rex = r"(?P<current_ver_notes>\#\#\# \[.+?)\#\#\#"
    match = re.search(notes_rex, new_changelog, re.DOTALL)
    if not match:
        sys.exit("Couldn't extract release notes for this version!")
    release_notes = match.group('current_ver_notes')

    # prepare release metadata
    release_data = dict()
    release_data['tag_name'] = tag_name
    release_data['target_commitish'] = branch
    release_data['name'] = "Munkireport " + current_version
    release_data['body'] = release_notes
    release_data['draft'] = False
    if opts.prerelease:
        release_data['prerelease'] = True

    # create the release
    if not opts.dry_run:
        create_release = api_call(
            '/repos/%s/%s/releases' % (publish_user, publish_repo),
            token,
            data=release_data)
        if create_release:
            print "Release successfully created. Server response:"
            pprint(create_release)
            print

    # increment version
    print "Incrementing version to %s.." % next_version
    set_version('%s.%s' % (clean_version(next_version), get_commit_count() + 1))

    # increment changelog
    new_changelog = "### [{0}](https://github.com/{1}/{2}/compare/v{3}...wip) (Unreleased)\n\n".format(
        next_version,
        publish_user,
        publish_repo,
        current_version) + new_changelog
    with open(changelog_path, 'w') as fdesc:
        fdesc.write(new_changelog)

    # commit and push increment
    run_command(['git', 'add', get_version_file_path(), changelog_path])
    run_command(
        ['git', 'commit', '-m',
         'Bumping to v%s for development.' % next_version])
    if not opts.dry_run:
        run_command(['git', 'push', 'origin', branch])
        run_command(['git', 'checkout', 'master'])
        run_command(['git', 'merge', tag_name])
        run_command(['git', 'push', 'origin', 'master'])
    else:
        print ("Ended dry-run mode. Final state of the munkireport-php repo can be "
               "found at: %s" % munkireport_root)
    # clean up


if __name__ == '__main__':
    main()
